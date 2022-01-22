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
<table id="dg-productplan"  style="width:1300px;height:97%"></table>
<input type="hidden" name="bomid" id="bomid" />
<div id="tb-productplan">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addProductplan()" id="addproductplan"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editProductplan()" id="editproductplan"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveProductplan()" id="approveproductplan"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelProductplan()" id="rejectproductplan"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfproductplan()"></a>
		<a href="javascript:void(0)" title="Export Ke XLS"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsproductplan()"></a>
		<!--<a href="javascript:void(0)" title="Generate Barcode"class="easyui-linkbutton" iconCls="icon-genbarcode" plain="true" onclick="generatebarcode()"></a>
		<a href="javascript:void(0)" title="Print External Jahit"class="easyui-linkbutton" iconCls="icon-ean13" plain="true" onclick="downean13()"></a>
		<a href="javascript:void(0)" title="Print External KB Poin"class="easyui-linkbutton" iconCls="icon-barsticker" plain="true" onclick="downkbpoin()"></a>
		<a href="javascript:void(0)" title="Print Internal"class="easyui-linkbutton" iconCls="icon-code128" plain="true" onclick="downcode128()"></a>-->
		<a href="javascript:void(0)" title="List Material Yang Tidak ada di Gudang Sumber"class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="downpdf1()"></a>
		<a href="javascript:void(0)" title="List Material Yang Tidak ada di Gudang Tujuan"class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="downpdf2()"></a>
		<a href="javascript:void(0)" title="List Material Yang Satuannya Tidak sama dengan Data Gudang Sumber"class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="downpdf3()"></a>
		<a href="javascript:void(0)" title="List Material Yang Satuannya Tidak sama dengan Data Gudang Tujuan"class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="downpdf4()"></a>
	<?php }?>
	<table>
	<tr>
	<td><?php echo GetCatalog('productplanid')?></td>
	<td><input class="easyui-textbox" id="productplan_search_productoutputid" style="width:50px"></td>
	<td><?php echo GetCatalog('company')?></td>
	<td><input class="easyui-textbox" id="productplan_search_company" style="width:150px"></td>
	<td><?php echo GetCatalog('productplanno')?></td>
	<td><input class="easyui-textbox" id="productplan_search_productplanno" style="width:150px"></td>
	</tr>
	<tr>
	<td><?php echo GetCatalog('productplandate')?></td>
	<td><input class="easyui-textbox" id="productplan_search_productplandate" style="width:150px"></td>
	<td><?php echo GetCatalog('sono')?></td>
	<td><input class="easyui-textbox" id="productplan_search_sono" style="width:150px"></td>
	<td><?php echo GetCatalog('customer')?></td>
	<td><input class="easyui-textbox" id="productplan_search_customer" style="width:150px"></td>
	</tr>
	<tr>
	<td><?php echo GetCatalog('foreman')?></td>
	<td><input class="easyui-textbox" id="productplan_search_foreman" style="width:150px"></td>
    <td><?php echo GetCatalog('description')?></td>
	<td><input class="easyui-textbox" id="productplan_search_description" style="width:150px"></td>
	</tr>
    <tr>
	<td><?php echo GetCatalog('productdetail')?></td>
	<td><input class="easyui-textbox" id="productplan_search_productdetail" style="width:150px"></td>
    <td><?php echo GetCatalog('productplanfg')?></td>
	<td><input class="easyui-textbox" id="productplan_search_productfg" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchproductplan()"></a>
    </td>
	</tr>
	</table>
</div>

<div id="dlg-productplan" class="easyui-dialog" title="<?php echo GetCatalog('productplan') ?>" style="width:auto;height:600px" 
closed="true" data-options="
resizable:true,
modal:true,
toolbar: [
{
text:'<?php echo GetCatalog('save')?>',
iconCls:'icon-save',
handler:function(){
submitFormProductplan();
}
},
{
text:'<?php echo GetCatalog('cancel')?>',
iconCls:'icon-cancel',
handler:function(){
$('.ajax-loader').css('visibility', 'hidden');
$('#dlg-productplan').dialog('close');
}
},
]	
">
	<form id="ff-productplan-modif" method="post" data-options="novalidate:true">
		<input type="hidden" name="productplanid" id="productplanid" />
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('productplandate')?></td>
				<td><input class="easyui-datebox" type="text" id="productplandate" name="productplandate" data-options="formatter:dateformatter,required:true,parser:dateparser"/></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
					panelWidth: 500,
					required: true,
					idField: 'companyid',
					textField: 'companyname',
					pagination:true,
					mode:'remote',
					url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
					method: 'get',
					required:true,
					columns: [[
					{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
					{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
					]],
					fitColumns: true
					">
					</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('soheader')?></td>
				<td><select class="easyui-combogrid" name="soheaderid" id="soheaderid" style="width:250px" data-options="
					panelWidth: 500,
					idField: 'soheaderid',
					textField: 'sono',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('order/soheader/indexcombo',array('grid'=>true)) ?>',
					method: 'get',
					mode:'remote',
					onHidePanel: function(){
							jQuery.ajax({'url':'<?php echo $this->createUrl('productplan/generatedetail') ?>',
								'data':{
										'id':$('#soheaderid').combogrid('getValue'),
										'hid':$('#productplanid').val()
									},
									'type':'post',
									'dataType':'json',
								'success':function(data)
								{
									$('#dg-productplanfg').edatagrid('reload');				
								},
								'cache':false});
					},
					columns: [[
					{field:'sono',title:'<?php echo GetCatalog('sono') ?>',width:120},
					{field:'pocustno',title:'<?php echo GetCatalog('pocustno') ?>',width:120},
					{field:'customername',title:'<?php echo GetCatalog('customername') ?>',width:200},
					]],
					fitColumns: true
					">
					</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('pptype')?></td>
				<td> 
                    <input class="easyui-radiobutton" name="pptype" id="pptype" value="0" > UMUM    
                    <br />
                    <input class="easyui-radiobutton" name="pptype" id="pptype" value="1" > FOREMAN
                </td>
			</tr>
            <tr>
				<td><?php echo GetCatalog('foreman')?></td>
				<td><select class="easyui-combogrid" id="employeeid" name="employeeid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'employeeid',
								required: false,
								textField: 'foremanname',
								pagination:true,
								mode:'remote',
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
				<td><input class="easyui-textbox" type="textarea" name="description" data-options="required:true,multiline:true" style="width:300px;height:100px"/></td>
			</tr>
		</table>
	</form>
	<div class="easyui-tabs" style="width:auto;height:400px">
		<div title="FG" style="padding:5px">
			<table id="dg-productplanfg"  style="width:1000px;height:300px">
			</table>
			<div id="tb-productplanfg">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-productplanfg').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-productplanfg').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-productplanfg').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-productplanfg').edatagrid('destroyRow')"></a>
			</div>
		</div>
		<div title="Detail" style="padding:5px">
			<table id="dg-productplandetail"  style="width:1000px;height:300px">
			</table>
			<div id="tb-productplandetail">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-productplandetail').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-productplandetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-productplandetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-productplandetail').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#addproductplan').click(function(){
    tampil_loading();
});
    
$('#editproductplan').click(function(){
    tampil_loading();
});
    
$('#approveproductplan').click(function(){
    tampil_loading();
});

$('#rejectproductplan').click(function(){
    tampil_loading();
});

function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css("visibility", "hidden");
}
    
$('#productplan_search_productoutputid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_company').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_productplanno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_productplandate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_sono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_foreman').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_description').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_productdetail').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
$('#productplan_search_productfg').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchproductplan();
			}
		}
	})
});
	$('#dg-productplan').edatagrid({
		singleSelect: false,
		toolbar:'#tb-productplan',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		view: detailview,
		detailFormatter:function(index,row){
			return '<div style="padding:2px"><table class="ddv-productplanfg"></table><table class="ddv-productplandetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvproductplanfg = $(this).datagrid('getRowDetail',index).find('table.ddv-productplanfg');
			var ddvproductplandetail = $(this).datagrid('getRowDetail',index).find('table.ddv-productplandetail');
			ddvproductplanfg.datagrid({
				url:'<?php echo $this->createUrl('productplan/indexfg',array('grid'=>true)) ?>?id='+row.productplanid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				title:'Material / Service - FG',
				height:'auto',
				onSelect:function(index,row){
					ddvproductplandetail.edatagrid('load',{
						productplanfgid: row.productplanfgid,
						productplanid: row.productplanid
					})
				},
				width:'100%',
				pagination:true,
				columns:[[
				{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
				{field:'qty',title:'<?php echo GetCatalog('qty') ?>',align:'right'},
				{field:'qtyres',title:'<?php echo GetCatalog('qtyOP') ?>',align:'right',
					formatter: function(value,row,index){
						if (row.wqtyres == 1) {
							return '<div style="background-color:red;color:white">'+value+'</div>';
						} else {
							return value;
						}
					}},
				{field:'stock',title:'<?php echo GetCatalog('stock') ?>',align:'right',
					formatter: function(value,row,index){
						if (row.wstock == 1) {
							return '<div style="background-color:red;color:white">'+value+'</div>';
						} else {
							return value;
						}
					}},
				{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
				{field:'sloccode',title:'<?php echo GetCatalog('sloc') ?>'},
				{field:'bomversion',title:'<?php echo GetCatalog('bomversion') ?>'},
				{field:'description',title:'<?php echo GetCatalog('description') ?>'},
				{field:'startdate',title:'<?php echo GetCatalog('startdate') ?>'},
				{field:'enddate',title:'<?php echo GetCatalog('enddate') ?>'},
				{field:'machinecode',title:'<?php echo GetCatalog('machinecode') ?>',hidden:<?php echo GetMenuAuth('spp-plastik')?>},
				{field:'operator',title:'<?php echo GetCatalog('operator') ?>',hidden:<?php echo GetMenuAuth('spp-plastik')?>},
				]],
				onResize:function(){
					$('#dg-productplan').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
					setTimeout(function(){
						$('#dg-productplan').datagrid('fixDetailRowHeight',index);
					},0);
				}
			});
			ddvproductplandetail.datagrid({
				url:'<?php echo $this->createUrl('productplan/indexdetail',array('grid'=>true)) ?>?id='+row.productplanid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				title:'Material / Service - Detail',
				height:'auto',
				width:'1200',
				pagination:true,
				columns:[[
				{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
				{field:'qty',title:'<?php echo GetCatalog('qty') ?>',align:'right'},
				{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
				{field:'fromsloccode',title:'<?php echo GetCatalog('fromsloc') ?>'},
				{field:'stockfrom',title:'<?php echo GetCatalog('stockfrom') ?>',align:'right'},
				{field:'tosloccode',title:'<?php echo GetCatalog('tosloc') ?>'},
				{field:'stockto',title:'<?php echo GetCatalog('stockto') ?>',align:'right'},
				{field:'dipakai',title:'<?php echo GetCatalog('dipakai') ?>',align:'right'},
				{field:'bomversion',title:'<?php echo GetCatalog('bomversion') ?>'},
				{field:'description',title:'<?php echo GetCatalog('description') ?>'},
				]],
				onResize:function(){
					$('#dg-productplan').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
					setTimeout(function(){
						$('#dg-productplan').datagrid('fixDetailRowHeight',index);
					},0);
				}
			});
			$('#dg-productplan').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('productplan/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-productplan').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'productplanid',
		editing: false,
		columns:[[
		{field:'_expander',expander:true,width:24,fixed:true},
		{
			field:'productplanid',
			title:'<?php echo GetCatalog('productplanid') ?>',
			sortable: true,
			width:'50px',
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
					field:'productplanno',
					title:'<?php echo GetCatalog('productplanno') ?>',
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
						if (row.warna == 1) {
				return '<div style="background-color:cyan;color:black">'+value+'</div>';
			} else { return value; }
					}},
					{
						field:'productplandate',
						title:'<?php echo GetCatalog('productplandate') ?>',
						sortable: true,
						width:'150px',
						formatter: function(value,row,index){
							return value;
						}},
                    {
						field:'pp-employeeid',
						title:'<?php echo GetCatalog('foreman') ?>',
						sortable: true,
						width:'150px',
						formatter: function(value,row,index){
							return row.foremanname;
						}},
						{
							field:'soheaderid',
							title:'<?php echo GetCatalog('soheader') ?>',
							sortable: true,
							width:'100px',
							formatter: function(value,row,index){
								return row.sono;
							}},
							{
								field:'customername',
								title:'<?php echo GetCatalog('customer') ?>',
								sortable: true,
								width:'200px',
								formatter: function(value,row,index){
									return row.customername;
								}},
								{
									field:'description',
									title:'<?php echo GetCatalog('description') ?>',
									sortable: true,
									width:'250px',
									formatter: function(value,row,index){
										return value;
									}},
									{
										field:'recordstatusproductplan',
										title:'<?php echo GetCatalog('recordstatus') ?>',
										sortable: true,
										width:'150px',
										formatter: function(value,row,index){
											return value;
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
	
	function downean13() {
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		window.open('<?php echo $this->createUrl('productplan/downean13') ?>?id='+ss);
	};
	
	function downsticker() {
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		window.open('<?php echo $this->createUrl('productplan/downsticker') ?>?id='+ss);
	};
	
	function downkbpoin() {
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		window.open('<?php echo $this->createUrl('productplan/downkbpoin') ?>?id='+ss);
	};
	
	function downcode128() {
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		window.open('<?php echo $this->createUrl('productplan/downcode128') ?>?id='+ss);
	};
    function downpdf1() {
	var ss = [];
	var rows = $('#dg-productplan').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
	}
	window.open('<?php echo $this->createUrl('productplan/downpdf1') ?>?id='+ss);
    };
    
    function downpdf2() {
	var ss = [];
	var rows = $('#dg-productplan').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
	}
	window.open('<?php echo $this->createUrl('productplan/downpdf2') ?>?id='+ss);
    };
    
    function downpdf3() {
	var ss = [];
	var rows = $('#dg-productplan').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
	}
	window.open('<?php echo $this->createUrl('productplan/downpdf3') ?>?id='+ss);
    };
	
       function downpdf4() {
	var ss = [];
	var rows = $('#dg-productplan').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
	}
	window.open('<?php echo $this->createUrl('productplan/downpdf4') ?>?id='+ss);
    };
	
	function generatebarcode() {
         $('.ajax-loader').css('visibility', 'visible');
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		jQuery.ajax({'url':'<?php echo $this->createUrl('productplan/generatebarcode') ?>',
			'data':{'id':ss},'type':'post','dataType':'json',
			'success':function(data)
			{
				$('.ajax-loader').css("visibility", "hidden");
				show('Message',data.msg);
			} ,
		'cache':false});
	};
	
	function searchproductplan(){
		$('#dg-productplan').edatagrid('load',{
			productplanid:$('#productplan_search_productoutputid').val(),
			company:$('#productplan_search_company').val(),
			productplanno:$('#productplan_search_productplanno').val(),
			sono:$('#productplan_search_sono').val(),
			customer:$('#productplan_search_customer').val(),
			foreman:$('#productplan_search_foreman').val(),
			productplandate:$('#productplan_search_productplandate').val(),
			description:$('#productplan_search_description').val(),
			productdetail:$('#productplan_search_productdetail').val(),
			productfg:$('#productplan_search_productfg').val(),
		});
	};
	
	function approveProductplan() {
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		jQuery.ajax({'url':'<?php echo $this->createUrl('productplan/approve') ?>',
			'data':{'id':ss},'type':'post','dataType':'json',
			'success':function(data)
			{
				tutup_loading();
                show('Message',data.message);
				$('#dg-productplan').edatagrid('reload');				
			} ,
		'cache':false});
	};
	
	function cancelProductplan() {
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		jQuery.ajax({'url':'<?php echo $this->createUrl('productplan/delete') ?>',
			'data':{'id':ss},'type':'post','dataType':'json',
			'success':function(data)
			{
                tutup_loading();
				show('Message',data.message);
				$('#dg-productplan').edatagrid('reload');				
			} ,
		'cache':false});
	};
	
	function downpdfproductplan() {
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		window.open('<?php echo $this->createUrl('productplan/downpdf') ?>?id='+ss);
	};
	function downxlsproductplan() {
		var ss = [];
		var rows = $('#dg-productplan').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.productplanid);
		}
		window.open('<?php echo $this->createUrl('productplan/downxls') ?>?id='+ss);
	};
	
	function addProductplan() {
		$('#dlg-productplan').dialog('open');
		$('#ff-productplan-modif').form('clear');
		$('#ff-productplan-modif').form('load','<?php echo $this->createUrl('productplan/GetData') ?>');
		$('#productplandate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
	};
	
	function editProductplan() {
		var row = $('#dg-productplan').datagrid('getSelected');
		var docmax = <?php echo CheckDoc('appprodplan') ?>;
	var docstatus = row.recordstatus;
		if(row) {
			if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-productplan').dialog('open');
			$('#ff-productplan-modif').form('load',row);
		}
		}
		else {
			show('Message','chooseone');
		}
	};
	
	function submitFormProductplan(){
		$('#ff-productplan-modif').form('submit',{
			url:'<?php echo $this->createUrl('productplan/save') ?>',
			onSubmit:function(){
                tutup_loading();
				return $(this).form('enableValidation').form('validate');
			},
			success:function(data){
				var data = eval('(' + data + ')');  // change the JSON string to javascript object
				show('Pesan',data.message)
				if (data.success == true){
					$('#dg-productplan').datagrid('reload');
					$('#dlg-productplan').dialog('close');
				}
			}
		});	
	};
	
	function clearFormProductplan(){
		$('#ff-productplan-modif').form('clear');
	};
	
	function cancelFormProductplan(){
		$('#dlg-productplan').dialog('close');
        tutup_loading();
	};
	
	$('#ff-productplan-modif').form({
		onLoadSuccess: function(data) {
			$('#dg-productplanfg').datagrid({
				queryParams: {
					id: $('#productplanid').val()
				}
			});
			$('#dg-productplandetail').datagrid({
				queryParams: {
					id: $('#productplanid').val()
				}
			});
		}
	});
	
	$('#dg-productplanfg').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: true,
		idField:'productplanfgid',
		editing: true,
		toolbar:'#tb-productplanfg',
		fitColumn: true,
		pagination:true,
		url: '<?php echo $this->createUrl('productplan/searchdetailfg',array('grid'=>true)) ?>',
		saveUrl: '<?php echo $this->createUrl('productplan/savedetailfg',array('grid'=>true))?>',
		updateUrl: '<?php echo $this->createUrl('productplan/savedetailfg',array('grid'=>true))?>',
		destroyUrl: '<?php echo $this->createUrl('productplan/purgedetailfg',array('grid'=>true))?>',
		onSuccess: function(index,row){
			show('Pesan',row.message);
			$('#dg-productplanfg').edatagrid('reload');
			$('#dg-productplandetail').edatagrid('reload');
		},
		onError: function(index,row){
			show('Pesan',row.message);
			$('#dg-productplanfg').edatagrid('reload');
		},
		onBeforeSave: function(index){
			var row = $('#dg-productplanfg').edatagrid('getSelected');
			if (row)
			{
				row.productplanid = $('#productplanid').val();
				row.companyid = $('#companyid').combogrid('getValue');
			}
		},
		onSelect:function(index,row){
			$('#dg-productplandetail').edatagrid('load',{
				id: row.productplanid,
				productplanfgid: row.productplanfgid
			})
		},
		onDestroy: function(index,row) {
			$('#dg-productplandetail').edatagrid('reload');
		},
		columns:[[
		{
			field:'productplanid',
			title:'<?php echo GetCatalog('productplanid') ?>',
			hidden:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:'<?php echo GetCatalog('companyid') ?>',
			hidden:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productplanfgid',
			title:'<?php echo GetCatalog('productplanfgid') ?>',
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
					panelWidth:700,
					mode : 'remote',
					method:'get',
					idField:'productid',
					textField:'productname',
					url:'<?php echo Yii::app()->createUrl('production/bom/index',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					queryParams:{
						trxpplan:true
					},
					onBeforeLoad: function(param) {
						 param.companyid = $('#companyid').combogrid('getValue');
					},
					onChange:function(newValue,oldValue) {
						if ((newValue !== oldValue) && (newValue !== ''))
						{
							var tr = $(this).closest('tr.datagrid-row');
							var index = parseInt(tr.attr('datagrid-row-index'));
							var productid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"productid"});
							var uomid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"uomid"});
							var slocid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"slocid"});
							var bomid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"bomid"});
							jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdatapp') ?>',
								'data':{'productid':$(productid.target).combogrid("getValue"),
									'companyid':$('#companyid').combogrid('getValue')},
								'type':'post','dataType':'json',
								'success':function(data)
								{
									$(uomid.target).combogrid('setValue',data.uomid);
									$(slocid.target).combogrid('setValue',data.slocid);
									$(bomid.target).combogrid('setValue',data.bomid);
								} ,
							'cache':false});
						}
					},
					required:true,
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
					{field:'productid',title:'<?php echo GetCatalog('productid')?>',width:50},
					{field:'productname',title:'<?php echo GetCatalog('productname')?>',width:200},
                    {field:'bomid',title:'<?php echo GetCatalog('bomid')?>',width:50},
					{field:'bomversion',title:'<?php echo GetCatalog('bomversion')?>',width:400},
					]],
                    onSelect : function(index,row)
                    {
                        
                        //console.log(row.bomversion);
                        var desc = row.bomversion; 
                        //console.log(desc);
                        $("input[name='bomid']").val(row.bomid);
                        
                    },
                    onHidePanel: function()
                    {
                     jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('production/bom/index',array('grid'=>true)) ?>',
                    'data':{'bomid':$('#bomid').val()},
                    'type':'post','dataType':'json',
                    'success':function(data)
                    {
                       //console.log(data.bomid);
                        
                        var rowss = $('#dg-productplanfg').edatagrid('getSelected');
                        if (rowss){
                            var index = $('#dg-productplanfg').edatagrid('getRowIndex', rowss);
                        }
                        
                        var nilai2 = $('#dg-productplanfg').datagrid('getRows'); 

                        //console.log(rowss);
                        var nilai = $('#bomid').val();
                        var bomid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"bomid"});
                         $(bomid.target).combogrid('setValue',nilai);
                    } ,
                    'cache':false});
                 },	
				}	
			},
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
			}
		},
		/*{
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
					url:'<?php echo Yii::app()->createUrl('production/bom/index',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					queryParams:{
						combo:true
					},
					onChange:function(newValue,oldValue) {
						if ((newValue !== oldValue) && (newValue !== ''))
						{
							var tr = $(this).closest('tr.datagrid-row');
							var index = parseInt(tr.attr('datagrid-row-index'));
							var productid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"productid"});
							var uomid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"uomid"});
							var slocid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"slocid"});
							var bomid = $("#dg-productplanfg").datagrid("getEditor", {index: index, field:"bomid"});
							jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdatapp') ?>',
								'data':{'productid':$(productid.target).combogrid("getValue"),
									'companyid':$('#companyid').combogrid('getValue')},
								'type':'post','dataType':'json',
								'success':function(data)
								{
									$(uomid.target).combogrid('setValue',data.uomid);
									$(slocid.target).combogrid('setValue',data.slocid);
									$(bomid.target).combogrid('setValue',data.bomid);
								} ,
							'cache':false});
						}
					},
					required:true,
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
		},*/
		{
			field:'qty',
			title:'<?php echo GetCatalog('qty') ?>',
			editor:{
				type: 'numberbox',
				options:{
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'120px',
			sortable: true,
			required:true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'stock',
			title:'<?php echo GetCatalog('stock') ?>',
			width:'120px',
			sortable: true,
			required:true,
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
					queryParams:{
						combo:true
					},
					required:true,
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
					url:'<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					required:true,
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
			field:'bomid',
			title:'<?php echo GetCatalog('bom') ?>',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:450,
					mode : 'remote',
					method:'get',
					idField:'bomid',
					readonly:true,
					textField:'bomversion',
					url:'<?php echo $this->createUrl('bom/index',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					queryParams:{
						combo:true
					},
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
					{field:'bomid',title:'<?php echo GetCatalog('bomid')?>'},
					{field:'bomversion',title:'<?php echo GetCatalog('bomversion')?>'},
					{field:'productname',title:'<?php echo GetCatalog('productname')?>'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.bomversion;
			}
		},
		{
			field:'startdate',
			title:'<?php echo GetCatalog('startdate') ?>',
			sortable: true,
			editor: {
			type: 'datebox',
			options:{
			formatter:dateformatter,
			required:true,
			parser:dateparser
			}
		},
			width:'100px',			
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'enddate',
			title:'<?php echo GetCatalog('enddate') ?>',
			sortable: true,
			editor: {
			type: 'datebox',
			options:{
			formatter:dateformatter,
			required:true,
			parser:dateparser
			}
		},
			width:'100px',			
			formatter: function(value,row,index){
				return value;
			}
		},
        {
			field:'machineid',
			title:'<?php echo GetCatalog('machine') ?>',
			sortable: true,
            hidden:<?php echo GetMenuAuth('spp-plastik')?>,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:450,
					mode : 'remote',
					method:'get',
					idField:'machineid',
					textField:'machinecode',
					url:'<?php echo Yii::app()->createUrl('common/machine/index',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					queryParams:{
						combo:true
					},
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
					{field:'machineid',title:'<?php echo GetCatalog('machineid')?>'},
					{field:'machinename',title:'<?php echo GetCatalog('machinename')?>'},
					{field:'machinecode',title:'<?php echo GetCatalog('machinecode')?>'},
					]]
				}	
			},
			width:'100px',			
			formatter: function(value,row,index){
				return row.machinecode;
			}
		},
        {
			field:'employeeid',
			title:'<?php echo GetCatalog('operator') ?>',
			sortable: true,
            hidden:<?php echo GetMenuAuth('spp-plastik')?>,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:450,
					mode : 'remote',
					method:'get',
					idField:'employeeid',
					textField:'fullname',
					url:'<?php echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					queryParams:{
						combo:true
					},
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
					{field:'emoployeeid',title:'<?php echo GetCatalog('emoployeeid')?>'},
					{field:'fullname',title:'<?php echo GetCatalog('fullname')?>'},
					{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
					]]
				}	
			},
			width:'100px',			
			formatter: function(value,row,index){
				return row.fullname;
			}
		},
		{
			field:'description',
			title:'<?php echo GetCatalog('description') ?>',
			editor:'text',
			sortable: true,
			width:'250px',
			required:true,
			formatter: function(value,row,index){
				return value;
			}
		},
		]]
	});
	
	$('#dg-productplandetail').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: true,
		idField:'productplandetailid',
		editing: true,
		toolbar:'#tb-productplandetail',
		fitColumn: true,
		pagination:true,
		url: '<?php echo $this->createUrl('productplan/searchdetail',array('grid'=>true)) ?>',
		saveUrl: '<?php echo $this->createUrl('productplan/savedetail',array('grid'=>true))?>',
		updateUrl: '<?php echo $this->createUrl('productplan/savedetail',array('grid'=>true))?>',
		destroyUrl: '<?php echo $this->createUrl('productplan/purgedetail',array('grid'=>true))?>',
		onSuccess: function(index,row){
			show('Pesan',row.message);
			$('#dg-productplandetail').edatagrid('reload');
		},
		onError: function(index,row){
			show('Pesan',row.message);
			$('#dg-productplandetail').edatagrid('reload');
		},
		onBeforeSave: function(index){
			var pplan = $('#dg-productplan').edatagrid('getSelected');
			var row = $('#dg-productplandetail').edatagrid('getSelected');
			var ppfg = $('#dg-productplanfg').edatagrid('getSelected');
			if (pplan)
			{
				row.productplanid = $('#productplanid').val()
			}
			if (ppfg)
			{
				row.productplanfgid = ppfg.productplanfgid
			}
		},
		columns:[[
		{
			field:'productplandetailid',
			title:'<?php echo GetCatalog('productplandetailid') ?>',
			sortable: true,
			hidden:true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productplanid',
			title:'<?php echo GetCatalog('productplanid') ?>',
			hidden:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productplanfgid',
			title:'<?php echo GetCatalog('productplanfgid') ?>',
			sortable: true,
			hidden:true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productid',
			title:'<?php echo GetCatalog('product') ?>',
			sortable: true,
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
								var productid = $("#dg-productplandetail").datagrid("getEditor", {index: index, field:"productid"});
								var unitofmeasureid = $("#dg-productplandetail").datagrid("getEditor", {index: index, field:"uomid"});
								var slocid = $("#dg-productplandetail").datagrid("getEditor", {index: index, field:"fromslocid"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdataproduct') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue"),
                                           'companyid':$('#companyid').combogrid("getValue")},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(unitofmeasureid.target).combogrid('setValue',data.uomid);
										$(slocid.target).combogrid('setValue',data.slocid);
									} ,
									'cache':false});
							}
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo GetCatalog('productid')?>',width:50},
							{field:'productname',title:'<?php echo GetCatalog('productname')?>',width:200},
							{field:'sloccode',title:'<?php echo GetCatalog('sloccode')?>',width:200},
							{field:'qty',title:'<?php echo GetCatalog('qty')?>',width:200},
							{field:'uomcode',title:'<?php echo GetCatalog('uomcode')?>',width:200},
						]]
				}	
			},
			width:'250px',
			formatter: function(value,row,index){
				return row.productname;
			}
		},
		{
			field:'qty',
			title:'<?php echo GetCatalog('qty') ?>',
			sortable: true,
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
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'uomid',
			title:'<?php echo GetCatalog('uom') ?>',
			required:true,
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
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'unitofmeasureid',title:'<?php echo GetCatalog('unitofmeasureid')?>',width:50},
							{field:'uomcode',title:'<?php echo GetCatalog('uomcode')?>',width:200},
						]]
				}	
			}, 
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
			}
		},
		{
			field:'fromslocid',
			title:'<?php echo GetCatalog('fromsloc') ?>',
			editor:{
				type:'combogrid',
			options:{
			panelWidth:450,
			mode : 'remote',
			method:'get',
			idField:'slocid',
			textField:'sloccode',
			url:'<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
			fitColumns:true,
			pagination:true,
			required:true,
			width:'180px',
			loadMsg: '<?php echo GetCatalog('pleasewait')?>',
			columns:[[
			{field:'slocid',title:'<?php echo GetCatalog('slocid')?>'},
			{field:'sloccode',title:'<?php echo GetCatalog('sloccode')?>'},
			{field:'description',title:'<?php echo GetCatalog('description')?>'},
			]]
			}	
			},
			sortable: true,
			formatter: function(value,row,index){
			return row.fromsloccode;
			}
			},
			{
			field:'stockfrom',
			title:'<?php echo GetCatalog('stockfrom') ?>',
			sortable: true,
			formatter: function(value,row,index){
			return row.stockfrom;
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
			url:'<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
			fitColumns:true,
			pagination:true,
			required:true,
			width:'180px',
			loadMsg: '<?php echo GetCatalog('pleasewait')?>',
			columns:[[
			{field:'slocid',title:'<?php echo GetCatalog('slocid')?>'},
			{field:'sloccode',title:'<?php echo GetCatalog('sloccode')?>'},
			{field:'description',title:'<?php echo GetCatalog('description')?>'},
			]]
			}	
			},
			sortable: true,
			formatter: function(value,row,index){
			return row.tosloccode;
			}
			},
            {
                field:'stockto',
                title:'<?php echo GetCatalog('stockto') ?>',
                sortable: true,
                formatter: function(value,row,index){
                return row.stockto;
                }
			},
            {
                field:'dipakai',
                title:'<?php echo GetCatalog('dipakai') ?>',
                sortable: true,
                formatter: function(value,row,index){
                return row.dipakai;
                }
			},
			{
			field:'bomid',
			title:'<?php echo GetCatalog('bom') ?>',
			editor:{
			type:'combogrid',
			options:{
			panelWidth:450,
			mode : 'remote',
			method:'get',
			idField:'bomid',
			textField:'bomversion',
			url:'<?php echo $this->createUrl('bom/index',array('grid'=>true)) ?>',
			fitColumns:true,
			pagination:true,
			queryParams:{
			combo:true
			},
			width:'280px',
			loadMsg: '<?php echo GetCatalog('pleasewait')?>',
			columns:[[
			{field:'bomid',title:'<?php echo GetCatalog('bomid')?>'},
			{field:'bomversion',title:'<?php echo GetCatalog('bomversion')?>'},
			{field:'productname',title:'<?php echo GetCatalog('productname')?>'},
			]]
			}	
			},
			sortable: true,
			formatter: function(value,row,index){
			return row.bomversion;
			}
			},
			{
			field:'reqdate',
			title:'<?php echo GetCatalog('startdate') ?>',
			sortable: true,
			editor:{
			type: 'datebox',
			parser: dateparser,
			formatter: dateformatter,
			required:true,
			},
width:'100px',
			formatter: function(value,row,index){
			return value;
			}
			},
			{
			field:'description',
			title:'<?php echo GetCatalog('description') 

?>',
			editor:'text',
			sortable: true,
			width:'250px',
			required:true,
			formatter: function(value,row,index){
			return value;
			}
			},
			]]
			});
			</script>
