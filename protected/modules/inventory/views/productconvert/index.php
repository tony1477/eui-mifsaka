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
<table id="dg-productconvert" style="width:100%;height:97%"></table>
<div id="tb-productconvert">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addProductconvert()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editProductconvert()"></a>
	<?php }?>
        <?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveproductconvert()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelproductconvert()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfproductconvert()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsproductconvert()"></a>
        <a href="javascript:void(0)" title="Generate Barcode"class="easyui-linkbutton" iconCls="icon-genbarcode" plain="true" onclick="generatebarcode()"></a>
		<a href="javascript:void(0)" title="Print External Jahit"class="easyui-linkbutton" iconCls="icon-ean13" plain="true" onclick="downqr()"></a>
		<a href="javascript:void(0)" title="Print External Sticker"class="easyui-linkbutton" iconCls="icon-code128" plain="true" onclick="downsticker()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('productconvertid')?></td>
<td><input class="easyui-textbox" id="productconvert_search_productconvertid" style="width:150px"></td>
<td><?php echo GetCatalog('productname')?></td>
<td><input class="easyui-textbox" id="productconvert_search_productname" style="width:150px"></td>
<td><?php echo GetCatalog('uom')?></td>
<td><input class="easyui-textbox" id="productconvert_search_uomcode" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('sloc')?></td>
<td><input class="easyui-textbox" id="productconvert_search_sloc" style="width:150px"></td>
<td><?php echo GetCatalog('storagebin')?></td>
<td><input class="easyui-textbox" id="productconvert_search_storagebin" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchproductconvert()"></a></td>
</tr>
</table>
</div>

<div id="dlg-productconvert" class="easyui-dialog" title="Konversi" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormProductconvert();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-productconvert').dialog('close');
			}
		},
	]	
	">
        <form id="ff-productconvert-modif" method="post" data-options="novalidate:true">
        <input type="hidden" name="productconvertid" id="productconvertid"/>
                <table cellpadding="5">
			<tr>
				<td><?php echo getCatalog('sloccode')?></td>
				<td><select class="easyui-combogrid" id="slocid" name="slocid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'slocid',
								required: true,
								textField: 'sloccode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
								method: 'get',
                                mode: 'remote',
								onHidePanel: function(){
                                    $('#storagebinid').combogrid('grid').datagrid({
                                        queryParams: {slocid: $('#slocid').combogrid('getValue')}});                               
											$('#storagebinid').combogrid('grid').datagrid('reload');
										},
										columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocid') ?>',width:80},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('productname')?></td>
				<td><select class="easyui-combogrid" id="productconversionid" name="productconversionid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'productconversionid',
								required: true,
								textField: 'productname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/productconversion/index',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								queryParams:{
									trx:true,
								},
								onHidePanel: function(){
										jQuery.ajax({'url':'<?php echo $this->createUrl('productconvert/generateconv') ?>',
												'data':{
															'id':$('#productconversionid').combogrid('getValue'),
															'slocid':$('#slocid').combogrid('getValue'),
															'qty':$('#qty').numberbox('getValue'),
															'hid':$('#productconvertid').val()
														},
												'type':'post',
												'dataType':'json',
												'success':function(data)
												{
													$('#uomid').combogrid('setValue',data.uomid);    
													$('#dg-productconvertdetail').edatagrid('reload');
												} ,
												'cache':false});
								},
								columns: [[
										{field:'productconversionid',title:'<?php echo getCatalog('productconversionid') ?>'},
										{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
            </tr>
            <tr>
				<td><?php echo getCatalog('qty')?></td>
				<td><input class="easyui-numberbox" type="text" id="qty" name="qty" data-options="
                        required:true,
                        precision:4,
                        decimalSeparator:',',
                        groupSeparator:'.',
                        value:'0',
                        required:true,
                        onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('inventory/productconvert/generateconv') ?>',
									'data':{
                                           'id':$('#productconversionid').combogrid('getValue'),
								            'slocid':$('#slocid').combogrid('getValue'),
											'qty':$('#qty').numberbox('getValue'),
											'hid':$('#productconvertid').val()
                                            },
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$('#uomid').combogrid('setValue',data.uomid);    
				                        $('#dg-productconvertdetail').edatagrid('reload');
									},
									'cache':false});
							}
						},
					">
				</td>
			</tr>
			<tr>
				<td><?php echo getCatalog('uomcode')?></td>
				<td><select class="easyui-combogrid" id="uomid" name="uomid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'unitofmeasureid',
								required: true,
								textField: 'uomcode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								queryParams:{
									combo:true,
								},
								columns: [[
										{field:'unitofmeasureid',title:'<?php echo getCatalog('uomid') ?>',width:80},
										{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>                        
			<tr>
				<td><?php echo getCatalog('storagebin')?></td>
				<td><select class="easyui-combogrid" id="storagebinid" name="storagebinid" style="width:250px" data-options="
								panelWidth: 500,
								mode: 'remote',
								method: 'get',
								idField: 'storagebinid',
								required: true,
								textField: 'description',
								pagination:true,
								queryParams:{
									combo:true,
									slocid:1
								},
								onBeforeLoad: function(param) {
									param.slocid = $('#slocid').combogrid('getValue')
								},
								url: '<?php echo Yii::app()->createUrl('common/storagebin/index',array('grid'=>true)) ?>',
								columns: [[
										{field:'storagebinid',title:'<?php echo getCatalog('storagebinid') ?>',width:80},
										{field:'description',title:'<?php echo getCatalog('description') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
		</table>
        </form>
        <div class="easyui-tabs" style="width:auto;height:400px">
                <div title="Detail" style="padding:5px">
			<table id="dg-productconvertdetail"  style="width:100%">
			</table>
			<div id="tb-productconvertdetail">
                                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-productconvertdetail').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-productconvertdetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-productconvertdetail').edatagrid('cancelRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#productconvert_search_productconvertid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductconvert();
			}
		}
	})
});
$('#productconvert_search_productname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductconvert();
			}
		}
	})
});
$('#productconvert_search_sloc').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductconvert();
			}
		}
	})
});
$('#productconvert_search_storagebin').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductconvert();
			}
		}
	})
});
$('#productconvert_search_uomcode').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductconvert();
			}
		}
	})
});
$('#dg-productconvert').edatagrid({
		singleSelect: false,
		toolbar:'#tb-productconvert',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
                autoRowHeight:true,
                onDblClickRow: function (index,row) {
			editProductconvert(index);
		},
                rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
                view: detailview,
                detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-productconvertdetail"></table></div>';
		},
                onExpandRow: function(index,row){
                        var ddvproductconvertdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-productconvertdetail');
                        ddvproductconvertdetail.datagrid({
                                url:'<?php echo $this->createUrl('productconvert/indexdetail',array('grid'=>true)) ?>?id='+row.productconvertid,
                                fitColumns:true,
                                singleSelect:true,
                                rownumbers:true,
                                loadMsg:'<?php echo getCatalog('pleasewait') ?>',
								pagination:true,
                                height:'auto',
                                width:'100%',
																showFooter:true,
                                columns:[[
					{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
					{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
					{field:'storagebin',title:'<?php echo getCatalog('storagebin') ?>'},
				]],
                                onResize:function(){
						$('#dg-productconvert').datagrid('fixDetailRowHeight',index);
				},
                                onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-productconvert').datagrid('fixDetailRowHeight',index);
						},0);
				}
                        });
                        $('#dg-productconvert').datagrid('fixDetailRowHeight',index);
                },
                url: '<?php echo $this->createUrl('productconvert/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-productconvert').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'productconvertid',
		editing: false,
		columns:[[
{
field:'productconvertid',
title:'<?php echo getCatalog('productconvertid') ?>',
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
field:'productconversionid',
title:'<?php echo getCatalog('productconversion') ?>',
sortable: true,
width:'350px',
formatter: function(value,row,index){
						return row.productname;
					}},
{
field:'productconvertno',
title:'<?php echo getCatalog('productconvertno') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
                        return value;
					}},
{
field:'qty',
title:'<?php echo getCatalog('qty') ?>',
editor:'text',
width:'60px',
sortable: true,
formatter: function(value,row,index){
						return '<div style="text-align:right">'+value+'</div>';
					}},
{
field:'uomid',
title:'<?php echo getCatalog('uom') ?>',
editor:'text',
sortable: true,
width:'60px',
formatter: function(value,row,index){
						return row.uomcode;
					}},
{
field:'slocid',
title:'<?php echo getCatalog('sloc') ?>',
editor:'text',
sortable: true,
width:'300px',
formatter: function(value,row,index){
						return row.sloccode;
					}},
{
field:'storagebinid',
title:'<?php echo getCatalog('storagebin') ?>',
editor:'text',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.storagebin;
					}},
{
field:'recordstatus',
title:'<?php echo getCatalog('recordstatus') ?>',
align:'left',
sortable: true,
width:'130px',
formatter: function(value,row,index){
						return row.recordstatusproductconvert;
					}},
		]]
});
function searchproductconvert(value){
	$('#dg-productconvert').edatagrid('load',{
	productconvertid:$('#productconvert_search_productconvertid').val(),
	productid:$('#productconvert_search_productname').val(),
	uomid:$('#productconvert_search_uomcode').val(),
	slocid:$('#productconvert_search_sloc').val(),
	storagebinid:$('#productconvert_search_storagebin').val()
	});
}
function approveproductconvert() {
	var ss = [];
	var rows = $('#dg-productconvert').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productconvertid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('productconvert/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-productconvert').edatagrid('reload');				
		},
		'cache':false});
};
function cancelproductconvert() {
	var ss = [];
	var rows = $('#dg-productconvert').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productconvertid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('productconvert/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-productconvert').edatagrid('reload');				
		},
		'cache':false});
};
function addProductconvert() {
		$('#dlg-productconvert').dialog('open');
		$('#ff-productconvert-modif').form('clear');
		$('#ff-productconvert-modif').form('load','<?php echo $this->createUrl('productconvert/getdata') ?>');
};
function editProductconvert($i) {
	var row = $('#dg-productconvert').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appconvert') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-productconvert').dialog('open');
		$('#ff-productconvert-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormProductconvert(){
	$('#ff-productconvert-modif').form('submit',{
		url:'<?php echo $this->createUrl('productconvert/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
                        $('#dg-productconvert').datagrid('reload');
                        $('#dlg-productconvert').dialog('close');
                        }
                }
	});	
};
function clearFormProductconvert(){
		$('#ff-productconvert-modif').form('clear');
};
function cancelFormProductconvert(){
		$('#dlg-productconvert').dialog('close');
};
$('#ff-productconvert-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-productconvertdetail').datagrid({
			queryParams: {
				id: $('#productconvertid').val()
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
function downpdfproductconvert() {
	var ss = [];
	var rows = $('#dg-productconvert').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productconvertid);
	}
	window.open('<?php echo $this->createUrl('productconvert/downpdf') ?>?id='+ss);
}
function generatebarcode() {
    $('.ajax-loader').css('visibility', 'visible');
	var ss = [];
	var rows = $('#dg-productconvert').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.productconvertid);
	}
    jQuery.ajax({'url':'<?php echo $this->createUrl('productconvert/generatebarcode') ?>',
        'data':{'id':ss},'type':'post','dataType':'json',
        'success':function(data){
	        $('.ajax-loader').css("visibility", "hidden");
	        show('Message',data.msg);
        },
	'cache':false});
};
function downqr() {
	var ss = [];
	var rows = $('#dg-productconvert').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.productconvertid);
	}
	window.open('<?php echo $this->createUrl('productconvert/downean13') ?>?id='+ss);
};
	
function downsticker() {
	var ss = [];
	var rows = $('#dg-productconvert').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.productconvertid);
	}
    window.open('<?php echo $this->createUrl('productconvert/downsticker') ?>?id='+ss);
};

function downxlsproductconvert() {
	var ss = [];
	var rows = $('#dg-productconvert').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productconvertid);
	}
	window.open('<?php echo $this->createUrl('productconvert/downxls') ?>?id='+ss);
}

$('#dg-productconvertdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'productconvertdetailid',
	editing: true,
	toolbar:'#tb-productconvertdetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('productconvert/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('productconvert/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('productconvert/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('productconvert/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-productconvertdetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-productconvertdetail').edatagrid('getSelected');
		if (row)
		{
			row.productconvertid = $('#productconvertid').val();
		}
	},
	columns:[[
	{
		field:'productconvertid',
		title:'<?php echo getCatalog('productconvertid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productconvertdetailid',
		title:'<?php echo getCatalog('productconvertdetailid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'productconvesiondetailid',
		title:'<?php echo getCatalog('productconversiondetailid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productid',
		title:'<?php echo getCatalog('productname') ?>',
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
                        readonly:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo getCatalog('productid')?>',width:50},
							{field:'productname',title:'<?php echo getCatalog('productname')?>',width:200},
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
                readonly:true,
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
		field:'uomid',
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
						required:true,
                        readonly:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'unitofmeasureid',title:'<?php echo getCatalog('unitofmeasureid')?>'},
							{field:'uomcode',title:'<?php echo getCatalog('uomcode')?>'},
						]]
				}	
			},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
        {
		field:'storagebinid',
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
							slocid: 1
						},
						onBeforeLoad: function(param) {
							param.slocid = $('#slocid').combogrid('getValue');
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>'},
							{field:'description',title:'<?php echo getCatalog('description')

?>'},
						]]
				}	
			},
		width:'120px',
		sortable: true,
		formatter: function(value,row,index){
							return row.storagebin;
		}
	},
	]]
});
</script>
