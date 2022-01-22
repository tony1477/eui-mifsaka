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
<table id="dg-productoutput"  style="width:1200px;height:97%"></table>
<div id="tb-productoutput">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addproductoutput()" id="addproductoutput"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editproductoutput()" id="editproductoutput"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveproductoutput()" id="approveproductoutput"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelproductoutput()" id="rejectproductoutput"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfproductoutput()"></a>
    <a href="javascript:void(0)" title="Stock yang TIDAK CUKUP"class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="downpdfminus()"></a>
	<a href="javascript:void(0)" title="Stock yang TIDAK CUKUP XLS "class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="downxlsminus()"></a>
	<?php }?>
	<table>
	<tr>
	<td><?php echo GetCatalog('productoutputid')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_productoutputid" style="width:150px"></td>
	<td><?php echo GetCatalog('productoutputno')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_productoutputno" style="width:150px"></td>
	<td><?php echo GetCatalog('productoutputdate')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_productoutputdate" style="width:150px"></td>
	</tr>
	<tr>
	<td><?php echo GetCatalog('company')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_companyid" style="width:150px"></td>
	<td><?php echo GetCatalog('productplan')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_productplanno" style="width:150px"></td>
	<td><?php echo GetCatalog('soheader')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_sono" style="width:150px"></td>
	</tr>
	<tr>
	<td><?php echo GetCatalog('customer')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_customer" style="width:150px"></td>
    <td><?php echo GetCatalog('foreman')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_foreman" style="width:150px"></td>
	<td><?php echo GetCatalog('description')?></td>
	<td><input class="easyui-textbox" id="productoutput_search_description" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchproductoutput()"></a></td>
	</tr>
	</table>
</div>

<div id="dlg-productoutput" class="easyui-dialog" title="Producton Output" style="width:auto;height:600px" 
closed="true" data-options="
resizable:true,
modal:true,
toolbar: [
{
text:'<?php echo GetCatalog('save')?>',
iconCls:'icon-save',
handler:function(){
submitFormproductoutput();
}
},
{
text:'<?php echo GetCatalog('cancel')?>',
iconCls:'icon-cancel',
handler:function(){
$('.ajax-loader').css('visibility', 'hidden');
$('#dlg-productoutput').dialog('close');
}
},
]	
">
	<form id="ff-productoutput-modif" method="post" data-options="novalidate:true">
		<input type="hidden" name="productoutputid" id="productoutputid"/>
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('productoutputdate')?></td>
				<td><input class="easyui-datebox" type="text" id="productoutputdate" name="productoutputdate" data-options="formatter:dateformatter,required:true,parser:dateparser"/></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('productplan')?></td>
				<td><select class="easyui-combogrid" name="productplanid" id="productplanid" style="width:250px" data-options="
					panelWidth: 500,
					idField: 'productplanid',
					textField: 'productplanno',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('production/productplan/indexcombo',array('grid'=>true)) ?>',
					method: 'get',
					mode:'remote',
					required:true,
					onHidePanel: function(){
						
							jQuery.ajax({'url':'<?php echo $this->createUrl('productoutput/generateplan') ?>',
								'data':{'id':$('#productplanid').combogrid('getValue'),'hid':$('#productoutputid').val()},'type':'post','dataType':'json',
								'success':function(data)
								{
                                    //console.log(data.employeeid);
                                    $('#foremanname').combogrid('setValue',data.foremanname);
                                    $('#employeeid').val(data.employeeid);
									$('#dg-productoutputfg').edatagrid('reload');		
									$('#dg-productoutputdetail').edatagrid('reload');
								} ,
								'cache':false});
						
					},
					columns: [[
					{field:'productplanid',title:'<?php echo GetCatalog('productplanid') ?>'},
					{field:'productplanno',title:'<?php echo GetCatalog('productplanno') ?>'},
					{field:'description',title:'<?php echo GetCatalog('description') ?>'},
					{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
					]],
					fitColumns: true
					">
					</select></td>
			</tr>
            <tr>
				<td><?php echo GetCatalog('foreman')?></td>
                <input type="hidden" name="employeeid" id="employeeid" value="" />
				<td><select class="easyui-combogrid" id="foremanname" name="foremanname" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'employeeid',
								required: true,
								textField: 'foremanname',
								pagination:true,
								mode:'remote',
                                readonly:true,
								method: 'get',
								url:'<?php echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ?>',
								columns: [[
										{field:'employeeid',title:'<?php echo GetCatalog('employeeid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
										{field:'oldnik',title:'<?php echo GetCatalog('oldnik') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('description')?></td>
				<td><input class="easyui-textbox" type="textarea" name="description" data-options="multiline:true,required:true" style="width:300px;height:100px"/></td>
			</tr>
		</table>
	</form>
	<div class="easyui-tabs" style="width:auto;height:600px">
		<div title="FG" style="padding:5px">
			<table id="dg-productoutputfg"  style="width:auto;height:400px">
			</table>
			<div id="tb-productoutputfg">
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-productoutputfg').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-productoutputfg').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-productoutputfg').edatagrid('destroyRow')"></a>
			</div>
		</div>
		<div title="Detail" style="padding:5px">
			<table id="dg-productoutputdetail"  style="width:auto;height:400px">
			</table>
			<div id="tb-productoutputdetail">
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-productoutputdetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-productoutputdetail').edatagrid('cancelRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#addproductoutput').click(function(){
    tampil_loading();
});
    
$('#editproductoutput').click(function(){
    tampil_loading();
});
    
$('#approveproductoutput').click(function(){
    tampil_loading();
});

$('#rejectproductoutput').click(function(){
    tampil_loading();
});

function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css("visibility", "hidden");
}

$('#productoutput_search_productoutputid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});
$('#productoutput_search_productoutputno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});
$('#productoutput_search_productoutputdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});
$('#productoutput_search_productplanno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});
$('#productoutput_search_companyid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});
$('#productoutput_search_sono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});
$('#productoutput_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});
$('#productoutput_search_foreman').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});
$('#productoutput_search_description').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductoutput();
			}
		}
	})
});

	$('#dg-productoutput').edatagrid({
		singleSelect: false,
		toolbar:'#tb-productoutput',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editproductoutput(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
				return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
			return '<div style="padding:2px"><table class="ddv-productoutputfg"></table><table class="ddv-productoutputdetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvproductoutputfg = $(this).datagrid('getRowDetail',index).find('table.ddv-productoutputfg');
			var ddvproductoutputdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-productoutputdetail');
			ddvproductoutputfg.datagrid({
				url:'<?php echo $this->createUrl('productoutput/indexfg',array('grid'=>true)) ?>?id='+row.productoutputid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				title:'Material / Service - FG',
				height:'auto',
				onSelect:function(index,row){
					ddvproductoutputdetail.edatagrid('load',{
						productoutputfgid: row.productoutputfgid
					})
				},
				width:'100%',
				columns:[[
				{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
				{field:'qtyoutput',title:'<?php echo GetCatalog('qty') ?>',align:'right'},
				{field:'stock',title:'<?php echo GetCatalog('stock') ?>',align:'right',
					formatter: function(value,row,index){
					if (row.wstock == 1) {
						return '<div style="background-color:red;color:white">'+value+'</div>';
					} else {
						return value;
				}}},
				{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
				{field:'sloccode',title:'<?php echo GetCatalog('sloc') ?>'},
				{field:'rak',title:'<?php echo GetCatalog('storagebin') ?>'},
				{field:'description',title:'<?php echo GetCatalog('description') ?>'},
				]],
				onResize:function(){
					$('#dg-productoutput').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
					setTimeout(function(){
						$('#dg-productoutput').datagrid('fixDetailRowHeight',index);
					},0);
				}
			});
			ddvproductoutputdetail.datagrid({
				url:'<?php echo $this->createUrl('productoutput/indexdetail',array('grid'=>true)) ?>?id='+row.productoutputid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				title:'Material / Service - Detail',
				height:'auto',
				fitColumns:true,
				width:'100%',
				columns:[[
				{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
				{field:'qty',title:'<?php echo GetCatalog('qty') ?>',align:'right'},
				{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
				{field:'fromsloccode',title:'<?php echo GetCatalog('fromsloc') ?>'},
				{field:'fromslocstock',title:'<?php echo GetCatalog('stockfrom') ?>',align:'right',
					formatter: function(value,row,index){
					if (row.wminfromstock == 1) {
						return '<div style="background-color:red;color:white">'+value+'</div>';
					} else {
						return value;
				}}},
				{field:'toslocstock',title:'<?php echo GetCatalog('stockto') ?>',align:'right',
					formatter: function(value,row,index){
					if (row.wmintostock == 1) {
						return '<div style="background-color:red;color:white">'+value+'</div>';
					} else {
						return value;
				}}},
				{field:'rak',title:'<?php echo GetCatalog('storagebin') ?>'},
				{field:'description',title:'<?php echo GetCatalog('description') ?>'},
				]],
				onResize:function(){
					$('#dg-productoutput').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
					setTimeout(function(){
						$('#dg-productoutput').datagrid('fixDetailRowHeight',index);
					},0);
				}
			});
			$('#dg-productoutput').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('productoutput/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-productoutput').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'productoutputid',
		editing: false,
		columns:[[
		{field:'_expander',expander:true,width:24,fixed:true},
		{
			field:'productoutputid',
			title:'<?php echo GetCatalog('productoutputid') ?>',
			sortable: true,
			width:'80px',
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
					width:'250px',
					formatter: function(value,row,index){
						return row.companyname;
					}},
							{
					field:'productoutputno',
					title:'<?php echo GetCatalog('productoutputno') ?>',
					sortable: true,
					width:'130px',
					formatter: function(value,row,index){
						return value;
					}},
					{
						field:'productoutputdate',
						title:'<?php echo GetCatalog('productoutputdate') ?>',
						sortable: true,
						width:'130px',
						formatter: function(value,row,index){
							return value;
						}},
						{
							field:'productplanid',
							title:'<?php echo GetCatalog('productplanno') ?>',
							sortable: true,
							width:'130px',
							formatter: function(value,row,index){
								return row.productplanno;
							}},
                            {
							field:'employeeid',
							title:'<?php echo GetCatalog('foreman') ?>',
							sortable: true,
							width:'130px',
							formatter: function(value,row,index){
								return row.foremanname;
							}},
							{
									field:'description',
									title:'<?php echo GetCatalog('description') ?>',
									sortable: true,
									width:'200px',
									formatter: function(value,row,index){
										return value;
									}},
									{
										field:'recordstatusproductoutput',
										title:'<?php echo GetCatalog('recordstatus') ?>',
										sortable: true,
										width:'130px',
										formatter: function(value,row,index){
											return value;
										}},
										{
							field:'addressbookid',
							title:'<?php echo GetCatalog('customer') ?>',
							sortable: true,
							width:'200px',
							formatter: function(value,row,index){
								return row.fullname;
							}},
							{
							field:'soheaderid',
							title:'<?php echo GetCatalog('soheader') ?>',
							sortable: true,
							width:'200px',
							formatter: function(value,row,index){
								return row.sono;
							}},
										]]
	});
	
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
	
	function searchproductoutput(){
		$('#dg-productoutput').edatagrid('load',{
			productoutputid:$('#productoutput_search_productoutputid').val(),
			productoutputno:$('#productoutput_search_productoutputno').val(),
			productplanno:$('#productoutput_search_productplanno').val(),
			productoutputdate:$('#productoutput_search_productoutputdate').val(),
			companyid:$('#productoutput_search_companyid').val(),
			sono:$('#productoutput_search_sono').val(),
			customer:$('#productoutput_search_customer').val(),
			foreman:$('#productoutput_search_foreman').val(),
			description:$('#productoutput_search_description').val(),
		});
	};
	
	function approveproductoutput() {
         $('.ajax-loader').css('visibility', 'visible');
		var ss = [];
		var rows = $('#dg-productoutput').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productoutputid);
		}
		jQuery.ajax({'url':'<?php echo $this->createUrl('productoutput/approve') ?>',
			'data':{'id':ss},'type':'post','dataType':'json',
			'success':function(data)
			{
				tutup_loading();
                show('Message',data.msg);
				$('#dg-productoutput').edatagrid('reload');				
			} ,
		'cache':false});
	};
	
	function cancelproductoutput() {
		var ss = [];
		var rows = $('#dg-productoutput').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productoutputid);
		}
		jQuery.ajax({'url':'<?php echo $this->createUrl('productoutput/delete') ?>',
			'data':{'id':ss},'type':'post','dataType':'json',
			'success':function(data)
			{
				tutup_loading();
				show('Message',data.msg);
				$('#dg-productoutput').edatagrid('reload');				
			} ,
		'cache':false});
	};
	
	function downpdfproductoutput() {
		var ss = [];
		var rows = $('#dg-productoutput').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productoutputid);
		}
		window.open('<?php echo $this->createUrl('productoutput/downpdf') ?>?id='+ss);
	};
    
    function downpdfminus() {
	var ss = [];
	var rows = $('#dg-productoutput').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productoutputid);
	}
	window.open('<?php echo $this->createUrl('productoutput/downpdfminus') ?>?id='+ss);
    }
	
	function downxlsproductoutput() {
		var ss = [];
		var rows = $('#dg-productoutput').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productoutputid);
		}
		window.open('<?php echo $this->createUrl('productoutput/downxls') ?>?id='+ss);
	}
	
	function downxlsminus() {
		var ss = [];
		var rows = $('#dg-productoutput').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productoutputid);
		}
		window.open('<?php echo $this->createUrl('productoutput/downxlsminus') ?>?id='+ss);
	}
	
	function addproductoutput() {
		$('#dlg-productoutput').dialog('open');
		$('#ff-productoutput-modif').form('clear');
		$('#ff-productoutput-modif').form('load','<?php echo $this->createUrl('productoutput/GetData') ?>');
	};
	
	function editproductoutput() {
		var row = $('#dg-productoutput').datagrid('getSelected');
		var docmax = <?php echo CheckDoc('appop') ?>;
	var docstatus = row.recordstatus;
		if(row) {
			if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-productoutput').dialog('open');
			$('#ff-productoutput-modif').form('load',row);
		}
		}
		else {
			show('Message','chooseone');
		}
	};
	
	function submitFormproductoutput(){
		$('#ff-productoutput-modif').form('submit',{
			url:'<?php echo $this->createUrl('productoutput/save') ?>',
			onSubmit:function(){
                tutup_loading();
				return $(this).form('enableValidation').form('validate');
			},
			success:function(data){
				var data = eval('(' + data + ')');  // change the JSON string to javascript object
				show('Pesan',data.msg)
				if (data.isError == false){
					$('#dg-productoutput').datagrid('reload');
					$('#dlg-productoutput').dialog('close');
				}
			}
		});	
	};
	
	function clearFormproductoutput(){
		$('#ff-productoutput-modif').form('clear');
	};
	
	function cancelFormproductoutput(){
		$('#dlg-productoutput').dialog('close');
        tutup_loading();
	};
	
	$('#ff-productoutput-modif').form({
		onLoadSuccess: function(data) {
			$('#dg-productoutputfg').datagrid({
				queryParams: {
					id: $('#productoutputid').val()
				}
			});
			$('#dg-productoutputdetail').datagrid({
				queryParams: {
					id: $('#productoutputid').val()
				}
			});
		}
	});
	
	$('#dg-productoutputfg').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		idField:'productoutputfgid',
		editing: true,
		ctrlSelect:true,
		toolbar:'#tb-productoutputfg',
		fitColumn: true,
		pagination:true,
		url: '<?php echo $this->createUrl('productoutput/searchdetailfg',array('grid'=>true)) ?>',
		saveUrl: '<?php echo $this->createUrl('productoutput/savedetailfg',array('grid'=>true))?>',
		updateUrl: '<?php echo $this->createUrl('productoutput/savedetailfg',array('grid'=>true))?>',
		destroyUrl: '<?php echo $this->createUrl('productoutput/purgedetailfg',array('grid'=>true))?>',
		onSuccess: function(index,row){
			show('Pesan',row.msg);
			$('#dg-productoutputfg').edatagrid('reload');
			$('#dg-productoutputdetail').edatagrid('reload');
		},
		onError: function(index,row){
			show('Pesan',row.msg);
			$('#dg-productoutputfg').edatagrid('reload');
		},
		onBeforeSave: function(index){
			var ixs = $('#dg-productoutput').edatagrid('getSelected');
			var row = $('#dg-productoutputfg').edatagrid('getSelected');
			if (ixs)
			{
				row.productoutputid = ixs.productoutputid;
			}
		},
		onSelect:function(index,row){
			$('#dg-productoutputdetail').edatagrid('load',{
				id: row.productoutputid,
				productoutputfgid: row.productoutputfgid
			})
		},
		onDestroy: function(index,row) {
			$('#dg-productoutputdetail').edatagrid('reload');
		},
		columns:[[
		{
			field:'productoutputid',
			title:'<?php echo GetCatalog('productoutputid') ?>',
			hidden:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productoutputfgid',
			title:'<?php echo GetCatalog('productoutputfgid') ?>',
			hidden:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productid',
			title:'<?php echo GetCatalog('product') ?>',
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
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
					{field:'productid',title:'<?php echo GetCatalog('productid')?>',width:50},
					{field:'productname',title:'<?php echo GetCatalog('productname')?>',width:200},
					{field:'bomversion',title:'<?php echo GetCatalog('bomversion')?>',width:200},
					]]
				}	
			},
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
			}
		},
		{
			field:'qtyoutput',
			title:'<?php echo GetCatalog('qty') ?>',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
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
			title:'<?php echo GetCatalog('uom') ?>',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:450,
					mode : 'remote',
					method:'get',
					readonly:true,
					idField:'unitofmeasureid',
					textField:'uomcode',
					url:'<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
					{field:'unitofmeasureid',title:'<?php echo GetCatalog('unitofmeasureid')?>',width:50},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode')?>',width:200},
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
			title:'<?php echo GetCatalog('sloc') ?>',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:450,
					mode : 'remote',
					method:'get',
					idField:'slocid',
					textField:'sloccode',
					url:'<?php echo Yii::app()->createUrl('common/sloc/index',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					required:true,
					readonly:true,
					queryParams:{
						combo:true
					},
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
					{field:'slocid',title:'<?php echo GetCatalog('slocid')?>',width:50},
					{field:'sloccode',title:'<?php echo GetCatalog('sloccode')?>',width:200},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
			}
		},
		{
			field:'storagebinid',
			title:'<?php echo GetCatalog('storagebin') ?>',
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
							var row = $('#dg-productoutputfg').datagrid('getSelected');
							param.slocid = row.slocid;
						},
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
					{field:'storagebinid',title:'<?php echo GetCatalog('storagebinid')?>',width:50},
					{field:'description',title:'<?php echo GetCatalog('description')?>',width:200},
					]]
				}	
			},
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.rak;
			}
		},
		{
			field:'outputdate',
			title:'<?php echo GetCatalog('outputdate') ?>',
			sortable: true,
			editor:{
				type: 'datebox',
				parser: dateparser,
				formatter: dateformatter
			},
			width:'100px',
			required:true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'description',
			title:'<?php echo GetCatalog('description') ?>',
			editor:'text',
			width:'250px',
			sortable: true,
			required:true,
			formatter: function(value,row,index){
				return value;
			}
		},
		]]
	});
	
	$('#dg-productoutputdetail').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		idField:'productoutputdetailid',
		editing: true,
		ctrlSelect:true,
		toolbar:'#tb-productoutputdetail',
		fitColumn: true,
		pagination:true,
		url: '<?php echo $this->createUrl('productoutput/searchdetail',array('grid'=>true)) ?>',
		saveUrl: '<?php echo $this->createUrl('productoutput/savedetail',array('grid'=>true))?>',
		updateUrl: '<?php echo $this->createUrl('productoutput/savedetail',array('grid'=>true))?>',
		destroyUrl: '<?php echo $this->createUrl('productoutput/purgedetail',array('grid'=>true))?>',
		onSuccess: function(index,row){
			show('Pesan',row.msg);
			$('#dg-productoutputdetail').edatagrid('reload');
		},
		onError: function(index,row){
			show('Pesan',row.msg);
			$('#dg-productoutputdetail').edatagrid('reload');
		},
		columns:[[
		{
			field:'productoutputdetailid',
			title:'<?php echo GetCatalog('productoutputdetailid') ?>',
			sortable: true,
			hidden:true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productoutputid',
			title:'<?php echo GetCatalog('productoutputid') ?>',
			hidden:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productoutputfgid',
			title:'<?php echo GetCatalog('productoutputfgid') ?>',
			sortable: true,
			hidden:true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productid',
			title:'<?php echo GetCatalog('product') ?>',
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
						required:true,
						readonly:true,
						pagination:true,
						onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $("#dg-productoutputdetail").datagrid("getEditor", {index: index, field:"productid"});
								var uomid = $("#dg-productoutputdetail").datagrid("getEditor", {index: index, field:"unitofmeasureid"});
								var slocid = $("#dg-productoutputdetail").datagrid("getEditor", {index: index, field:"slocid"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdata') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue")},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uomid);
										$(slocid.target).combogrid('setValue',data.slocid);
									} ,
									'cache':false});
							}
						},
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo GetCatalog('productid')?>'},
							{field:'productname',title:'<?php echo GetCatalog('productname')?>'},
						]]
				}	
			},
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
			}
		},
		{
			field:'qty',
			title:'<?php echo GetCatalog('qty') ?>',
			sortable: true,
			editor:{
				type:'textbox',
				options:{
					readonly:true,
				}
			},
			width:'150px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'uomid',
			title:'<?php echo GetCatalog('uom') ?>',
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
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'unitofmeasureid',title:'<?php echo GetCatalog('unitofmeasureid')?>',width:50},
							{field:'uomcode',title:'<?php echo GetCatalog('uomcode')?>',width:200},
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
			field:'toslocid',
			title:'<?php echo GetCatalog('tosloc') ?>',
			editor:{
			type:'combogrid',
			options:{
			panelWidth:450,
			mode : 'remote',
			method:'get',
			idField:'slocid',
			textField:'sloccode',
			url:'<?php echo Yii::app()->createUrl('common/sloc/index',array('grid'=>true)) ?>',
			fitColumns:true,
			pagination:true,
			required:true,
			readonly:true,
			queryParams:{
			combo:true
			},
			loadMsg: '<?php echo GetCatalog('pleasewait')?>',
			columns:[[
			{field:'slocid',title:'<?php echo GetCatalog('slocid')?>'},
			{field:'sloccode',title:'<?php echo GetCatalog('sloccode')?>'},
			]]
			}	
			},
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
			return row.tosloccode;
			}
			},
			{
			field:'storagebinid',
			title:'<?php echo GetCatalog('storagebin') ?>',
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
							var row = $('#dg-productoutputdetail').datagrid('getSelected');
							param.slocid = row.toslocid;
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo GetCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo GetCatalog('description')?>',width:200},
						]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.rak;
			}
		},
			{
			field:'description',
			title:'<?php echo GetCatalog('description') 

?>',
			editor:'text',
			sortable: true,
			required:true,
			width:'200px',
			formatter: function(value,row,index){
			return value;
			}
			},
			]]
			});
			</script>
