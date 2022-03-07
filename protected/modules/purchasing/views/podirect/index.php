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
<table id="dg-podirect" style="width:1200px;height:97%"></table>
<div id="tb-podirect">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addPoheader()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPoheader()"></a>
	<?php }?>
        <?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvepoheader()" id="approvepoheader"></a>
	<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelpoheader()" id="cancelpoheader"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfpoheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formPodirect" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FilePodirect" id="FilePodirect" style="display:inline">
			<input type="submit" value="Upload" name="submitPodirect" style="display:inline">
		</form>
	<?php }?>
    <br />XLSX PO Cabang :
    <form id="formPodirectPO" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FilePodirectPO" id="FilePodirectPO" style="display:inline">
			<input type="submit" value="Upload" name="submitPodirect" style="display:inline">
		</form>
<table>
<tr>
<td><?php echo GetCatalog('poheaderid')?></td>
<td><input class="easyui-textbox" id="podirect_search_poheaderid" style="width:50px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="podirect_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('pono')?></td>
<td><input class="easyui-textbox" id="podirect_search_pono" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('supplier')?></td>
<td><input class="easyui-textbox" id="podirect_search_supplier" style="width:150px"></td>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="podirect_search_podate" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="podirect_search_headernote" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('paymentmethod')?></td>
<td><input class="easyui-textbox" id="podirect_search_paycode" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchpodirect()"></a></td>
</tr>
</table></div>

<div id="dlg-poheader" class="easyui-dialog" title="Purchase Order" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
				submitFormPoheader();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
				$('#dlg-poheader').dialog('close');
			}
		},
	]	
	">
	<form id="ff-podirect-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="poheaderid" id="poheaderid"></input>
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			 <tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'companyid',
					required: true,
					textField: 'companyname',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
					method: 'get',
					mode: 'remote',
					onHidePanel: function(){
						jQuery.ajax({'url':'<?php echo $this->createUrl('podirect/generateaddress') ?>',
							'data':{
								'id':$('#companyid').combogrid('getValue'),
								},
								'type':'post',
								'dataType':'json',
							'success':function(data)
							{
								$('#shipto').textbox('setValue',data.shipto);
								$('#billto').textbox('setValue',data.billto);			
							},
							'cache':false});
					},
					columns: [[
							{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>',width:'80px'},
							{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:'200px'},
					]],
					fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('purchasinggroup')?></td>
				<td><select class="easyui-combogrid" id="purchasinggroupid" name="purchasinggroupid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'purchasinggroupid',
					textField: 'description',
					pagination:true,
					mode: 'remote',
					url: '<?php echo $this->createUrl('purchasinggroup/index',array('grid'=>true)) ?>',
					method: 'get',
					queryParams:{
						combo:true,
					},
					columns: [[
						{field:'purchasinggroupid',title:'<?php echo GetCatalog('purchasinggroupid') ?>',width:'80px'},
						{field:'description',title:'<?php echo GetCatalog('description') ?>',width:'200px'},
					]],
					fitColumns: true
				">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('supplier')?></td>
				<td><select class="easyui-combogrid" id="addressbookid" name="addressbookid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'addressbookid',
					required: true,
					textField: 'fullname',
					pagination:true,
					mode: 'remote',
					url: '<?php echo Yii::app()->createUrl('common/supplier/index',array('grid'=>true)) ?>',
					method: 'get',
					queryParams:{
						combo:true,
					},
					onHidePanel: function() {
							const supplier = $(this).combogrid('getValue');
							checksupplier(supplier);
					},
					columns: [[
						{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>',width:'80px'},
						{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>',width:'250px'},
					]],
					fitColumns: true
						">
				</select></td>
			</tr>
			<tr id="sp_plantid" style="display: none">
				<td><?php echo GetCatalog('plant')?></td>
				<td><select class="easyui-combogrid" id="plantid" name="plantid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'plantid',
								required: false,
								textField: 'plantcode',
								pagination:true,
								mode: 'remote',
								url: '<?php echo Yii::app()->createUrl('purchasing/poheader/getplantfromsupplier',array('grid'=>true)) ?>',
								method: 'get',
								onBeforeLoad: function(param) {
									const form = $(document.forms['ff-podirect-modif']).find('#addressbookid');
									const supplier = form.combogrid('getValue');
									param.supplierid = supplier;
								},
								onShowPanel: function(){
									const form = $(document.forms['ff-podirect-modif']).find('#companyid');
									//console.log(form.combogrid('getValue'));
									const companyid = form.combogrid('getValue');
									if(companyid=='') show('Message','Perusahaan belum dipilih'); return 0;
								},
								onHidePanel: function() {
									const plantid = $(this).combogrid('getValue');
									getPricePlant(plantid);
								},
								queryParams:{
									trxcom:true,
								},
								columns: [[
										{field:'plantid',title:'<?php echo GetCatalog('plantid') ?>',width:80},
										{field:'plantcode',title:'<?php echo GetCatalog('plantcode') ?>',width:120},
										{field:'description',title:'<?=GetCatalog('description')?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			 <tr>
				<td><?php echo GetCatalog('tax')?></td>
				<td><select class="easyui-combogrid" id="taxid" name="taxid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'taxid',
					required: true,
					textField: 'taxcode',
					pagination:true,
					mode: 'remote',
					url: '<?php echo Yii::app()->createUrl('accounting/tax/index',array('grid'=>true)) ?>',
					method: 'get',
					queryParams:{
						combo:true,
					},
					columns: [[
						{field:'taxid',title:'<?php echo GetCatalog('taxid') ?>',width:'80px'},
						{field:'taxcode',title:'<?php echo GetCatalog('taxcode') ?>',width:'150px'},
						{field:'taxvalue',title:'<?php echo GetCatalog('taxvalue') ?>',width:'100px'},
						{field:'description',title:'<?php echo GetCatalog('description') ?>',width:'200px'},
					]],
					fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('paymentmethod')?></td>
				<td><select class="easyui-combogrid" id="paymentmethodid" name="paymentmethodid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'paymentmethodid',
					required: true,
					textField: 'paycode',
					pagination:true,
					mode: 'remote',
					url: '<?php echo Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true)) ?>',
					method: 'get',
					queryParams:{
						combo:true,
					},
					columns: [[
						{field:'paymentmethodid',title:'<?php echo GetCatalog('paymentmethodid') ?>',width:'80px'},
						{field:'paycode',title:'<?php echo GetCatalog('paycode') ?>',width:'150px'},
						{field:'paydays',title:'<?php echo GetCatalog('paydays') ?>',width:'150px'},
						{field:'paymentname',title:'<?php echo GetCatalog('paymentname') ?>',width:'200px'},
					]],
					fitColumns: true">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('shipto')?></td>
				<td><input class="easyui-textbox" id="shipto" name="shipto" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('billto')?></td>
				<td><input class="easyui-textbox" id="billto" name="billto" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
	<div class="easyui-tabs" style="width:auto;height:400px">
		<div title="Detail" style="padding:5px">
			<table id="dg-podetaildirect"  style="width:100%;height:300px">
			</table>
			<div id="tb-podetaildirect">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-podetaildirect').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-podetaildirect').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-podetaildirect').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-podetaildirect').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#approvepoheader').click(function(){
    tampil_loading();
});

$('#cancelpoheader').click(function(){
    tampil_loading();
});


function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css("visibility", "hidden");
}

function suppliertoCompany(supplierid) {
	let companyid=10;
	$.ajax({
		'url':'<?= Yii::app()->createUrl('purchasing/poheader/suppliertoCompany')?>',
		'data':{'supplierid':supplierid},
		'type':'post',
		'dataType':'json',
		'success':function(data) {
			console.log(data);
			companyid = data.companyid;
		}
	});
	return companyid;
}

function checksupplier(supplierid,plant=null) {
	const hideplantid = document.querySelector('#sp_plantid');
	const form =  $(document.forms['ff-podirect-modif']).find('#plantid');
	const tax =  $(document.forms['ff-podirect-modif']).find('#taxid');
	tax.combogrid('readonly',false);
	if (plant !== null) { 
		hideplantid.style.display = 'table-row'; 
		form.combogrid({required:true}); 
		form.combogrid('setValue',plant);
		tax.combogrid('readonly',true);
		return ''; 
	}

	let getPlantid = form.combogrid('getValue');
	const plantid = getPlantid ||  form.combogrid('setValue','');
	hideplantid.style.display = 'none';
	form.combogrid({required:false});
	$('#taxid').combogrid('setValue','');
	$.ajax({
		'url':'<?= Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'trxpo'=>true))?>',
		'data':{'addressbookid':supplierid},
		'type':'post',
		'dataType':'json',
		'success': function(data) {
			if(data.rows[0].isextern==0) {
				hideplantid.style.display = 'table-row'; 
				form.combogrid({required:true});
				$('#taxid').combogrid('setValue',5);
				$('#taxid').combogrid('readonly',true);
			}
		},
		'cache':false
	});
}

function getPricePlant(plantid) {
	const form = $(document.forms['ff-podirect-modif']).find('#poheaderid');
	let plant = $(document.forms['ff-podirect-modif']).find('#plantid');
	const poheaderid = form.val();
	
	$.ajax({
		'url':'<?= Yii::app()->createUrl('purchasing/poheader/getpriceplant')?>',
		'data':{
			'poheaderid': poheaderid,
			'plantid':plantid
		},
		'type':'post',
		'dataType':'json',
		'success': function(data) {
			if(data.isError==false) {
				$('#dg-podetaildirect').datagrid('reload');
				show('Message',data.msg);
			}
			if(data.isError==true) { show('Message',data.msg); plant.combogrid('setValue',''); }
		},
		'cache':false
	});
}

$("#formPodirect").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('purchasing/podirect/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-podirect').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});

$("#formPodirectPO").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('purchasing/podirect/uploadPO') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-podirect').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#podirect_search_poheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpodirect();
			}
		}
	})
});
$('#podirect_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpodirect();
			}
		}
	})
});
$('#podirect_search_pono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpodirect();
			}
		}
	})
});
$('#podirect_search_supplier').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpodirect();
			}
		}
	})
});
$('#podirect_search_podate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpodirect();
			}
		}
	})
});
$('#podirect_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpodirect();
			}
		}
	})
});
$('#podirect_search_paycode').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpodirect();
			}
		}
	})
});
$('#dg-podirect').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-podirect',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoRowHeight:true,
	/*onDblClickRow: function (index,row) {
		editPoheader(index);
	},*/
	rowStyler: function(index,row){
		if (row.count >= 1){
				return 'background-color:blue;color:#fff;font-weight:bold;';
		}
	},
	view: detailview,
	detailFormatter:function(index,row){
		return '<div style="padding:2px"><table class="ddv-podetail"></table></div>';
	},
	onExpandRow: function(index,row){
		var ddvpodetail = $(this).datagrid('getRowDetail',index).find('table.ddv-podetail');
		ddvpodetail.datagrid({
			url:'<?php echo $this->createUrl('podirect/indexdetail',array('grid'=>true)) ?>?id='+row.poheaderid,
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:'',
			height:'auto',
			pagination : true,
			width:'100%',
			showFooter:true,
			columns:[[
				{field:'prno',title:'<?php echo GetCatalog('prno') ?>',width:'100px'},
				{field:'productname',title:'<?php echo GetCatalog('productname') ?>',width:'200px'},
				{field:'poqty',title:'<?php echo GetCatalog('poqty') ?>',align:'right',width:'120px'},
				{field:'qtyres',title:'<?php echo GetCatalog('qtysend') ?>',align:'right',width:'120px',formatter: function(value,row,index){
				if (row.wqtyres == 1) {
					return '<div style="background-color:red;color:white">'+value+'</div>';
				} else {
					return value;
				} }},
				{field:'saldoqty',title:'<?php echo GetCatalog('saldoqty') ?>',align:'right',width:'120px'},
				{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>',width:'100px'},
				{field:'sloccode',title:'<?php echo GetCatalog('sloccode') ?>',width:'100px'},
				{field:'netprice',title:'<?php echo GetCatalog('netprice') ?>',align:'right',width:'120px'},
				{field:'total',title:'<?php echo GetCatalog('total') ?>',align:'right',width:'120px'},
				{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>',width:'120px'},
				{field:'ratevalue',title:'<?php echo GetCatalog('ratevalue') ?>',width:'120px'},
				{field:'delvdate',title:'<?php echo GetCatalog('delvdate') ?>',width:'120px'},
				{field:'underdelvtol',title:'<?php echo GetCatalog('underdelvtol') ?>',align:'right',width:'100px'},
				{field:'overdelvtol',title:'<?php echo GetCatalog('overdelvtol') ?>',align:'right',width:'100px'},
				{field:'itemtext',title:'<?php echo GetCatalog('itemtext') ?>',width:'200px'},
			]],
			onResize:function(){
					$('#dg-podirect').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
					setTimeout(function(){
							$('#dg-podirect').datagrid('fixDetailRowHeight',index);
					},0);
			}
		});
		$('#dg-podirect').datagrid('fixDetailRowHeight',index);
	},
	url: '<?php echo $this->createUrl('podirect/index',array('grid'=>true)) ?>',
	destroyUrl: '<?php echo $this->createUrl('podirect/purge',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg);
		$('#dg-podirect').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg,'error');
		$('#dg-podirect').edatagrid('reload');
	},
	idField:'poheaderid',
	editing: false,
	columns:[[
	{
		field:'poheaderid',
		title:'<?php echo GetCatalog('poheaderid') ?>',
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
			return '<div style="background-color:cyan;color:black">'+value+'</div>';
		} else 
			if (row.recordstatus == 4) {
			return '<div style="background-color:blue;color:white">'+value+'</div>';
		} else 
			if (row.recordstatus == 5) {
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
		field:'pono',
		title:'<?php echo GetCatalog('pono') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
		if (row.warna == 1) {
			return '<div style="background-color:cyan;color:black">'+value+'</div>';
		} else {
			return value;
		}
	}},
	{
		field:'addressbookid',
		title:'<?php echo GetCatalog('supplier') ?>',
		sortable: true,
		width:'350px',
		formatter: function(value,row,index){
			return row.fullname;
	}},
	{
		field:'plantid',
		title:'<?php echo GetCatalog('plantcode') ?>',
		sortable: true,
		width:'80px',
		formatter: function(value,row,index){
			return row.plantcode;
	}},
	{
		field:'docdate',
		title:'<?php echo GetCatalog('docdate') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'paymentmethodid',
		title:'<?php echo GetCatalog('paymentmethod') ?>',
		sortable: true,
		width:'120px',
		formatter: function(value,row,index){
			return row.paycode;
	}},
	{
		field:'shipto',
		title:'<?php echo GetCatalog('shipto') ?>',
		sortable: true,
		width:'200px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'billto',
		title:'<?php echo GetCatalog('billto') ?>',
		sortable: true,
		width:'200px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'headernote',
		title:'<?php echo GetCatalog('headernote') ?>',
		sortable: true,
		width:'350px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'recordstatuspoheader',
		title:'<?php echo GetCatalog('recordstatus') ?>',
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
			return value;
	}},
	]]
});
function searchpodirect(value){
	$('#dg-podirect').edatagrid('load',{
		poheaderid:$('#podirect_search_poheaderid').val(),
		docdate:$('#podirect_search_podate').val(),
		addressbookid:$('#podirect_search_supplier').val(),
		headernote:$('#podirect_search_headernote').val(),
		pono:$('#podirect_search_pono').val(),
		paymentmethodid:$('#podirect_search_paycode').val(),
		companyid:$('#podirect_search_companyname').val()
	});
}
function approvepoheader() {
	var ss = [];
	var rows = $('#dg-podirect').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.poheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('podirect/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			tutup_loading();
      show('Message',data.msg);
			$('#dg-podirect').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelpoheader() {
	var ss = [];
	var rows = $('#dg-podirect').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.poheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('podirect/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			tutup_loading();
      show('Message',data.msg);
			$('#dg-podirect').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfpoheader() {
	var ss = [];
	var rows = $('#dg-podirect').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.poheaderid);
	}
	window.open('<?php echo $this->createUrl('podirect/downpdf') ?>?id='+ss);
}
function downxlspoheader() {
	var ss = [];
	var rows = $('#dg-podirect').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.poheaderid);
	}
	window.open('<?php echo $this->createUrl('podirect/downxls') ?>?id='+ss);
}
function addPoheader() {
	$('#dlg-poheader').dialog('open');
	$('#ff-podirect-modif').form('clear');
	$('#ff-podirect-modif').form('load','<?php echo $this->createUrl('podirect/GetData') ?>');
	$('#docdate').datebox({
		value: (new Date().toString('dd-MMM-yyyy'))
	});
};

function editPoheader($i) {
	var row = $('#dg-podirect').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('apppo') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-poheader').dialog('open');
		$('#ff-podirect-modif').form('load',row);
		let supplier = row.addressbookid;
		let plantid = row.plantid;
		checksupplier(supplier,plantid);
		}
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormPoheader(){
	$('#ff-podirect-modif').form('submit',{
		url:'<?php echo $this->createUrl('podirect/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-podirect').datagrid('reload');
        $('#dlg-poheader').dialog('close');
			}
      tutup_loading();
    }
	});	
}
function clearFormPoheader(){
		$('#ff-podirect-modif').form('clear');
}
function cancelFormPoheader(){
		$('#dlg-poheader').dialog('close');
}
$('#ff-podirect-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-podetaildirect').datagrid({
						queryParams: {
										id: $('#poheaderid').val()
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
$('#dg-podetaildirect').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'podetailid',
	editing: true,
	toolbar:'#tb-podetaildirect',
	fitColumn: true,
	pagination:true,
	showFooter:true,
	url: '<?php echo $this->createUrl('podirect/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('podirect/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('podirect/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('podirect/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-podetaildirect').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-podetaildirect').edatagrid('getSelected');
		if (row)
		{
			row.poheaderid = $('#poheaderid').val()
		}
	},
	onBeforeEdit: function(index,row){
		if (row.poqty != undefined) {
			var value = row.poqty;
			row.poqty = value.replace(".", "");
			var value = row.netprice;
			row.netprice = value.replace(".", "");
		}
	},
	columns:[[
	{
		field:'poheaderid',
		title:'<?php echo GetCatalog('poheaderid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'podetailid',
		title:'<?php echo GetCatalog('podetailid') ?>',
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
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'productid',
				textField:'productname',
				url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				onChange:function(newValue,oldValue) {
					if ((newValue !== oldValue) && (newValue !== ''))
					{
						const form = $(document.forms['ff-podirect-modif']).find('#plantid');
								//console.log(form.combogrid('getValue'));
						let plantid = form.combogrid('getValue');
						var tr = $(this).closest('tr.datagrid-row');
						var index = parseInt(tr.attr('datagrid-row-index'));
						var productid = $("#dg-podetaildirect").datagrid("getEditor", {index: index, field:"productid"});
						var uomid = $("#dg-podetaildirect").datagrid("getEditor", {index: index, field:"unitofmeasureid"});
						var slocid = $("#dg-podetaildirect").datagrid("getEditor", {index: index, field:"slocid"});
						var price = $("#dg-podetaildirect").datagrid("getEditor", {index: index, field:"netprice"});
						var currencyid = $("#dg-podetaildirect").datagrid("getEditor", {index: index, field:"currencyid"});
						var currencyrate = $("#dg-podetaildirect").datagrid("getEditor", {index: index, field:"ratevalue"});
						var overdelvtol = $("#dg-podetaildirect").datagrid("getEditor", {index: index, field:"overdelvtol"});
						var underdelvtol = $("#dg-podetaildirect").datagrid("getEditor", {index: index, field:"underdelvtol"});
						
						jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('purchasing/poheader/getpriceproduct') ?>',
							'data':{
								'productid':$(productid.target).combogrid("getValue"),
								'companyid':$('#companyid').combogrid("getValue"),
								'supplierid':$('#addressbookid').combogrid("getValue"),
								'plantid':plantid
							},
							'type':'post','dataType':'json',
							'success':function(data) {
								$(slocid.target).combogrid('setValue',data.slocid);
								$(uomid.target).combogrid('setValue',data.unitofmeasureid);
								$(price.target).numberbox('setValue',data.price);
								$(currencyid.target).combogrid('setValue',data.currencyid);
								$(currencyrate.target).numberbox('setValue',1);
								$(overdelvtol.target).numberbox('setValue',data.overdelvtol);
								$(underdelvtol.target).numberbox('setValue',data.underdelvtol)
							} ,
							'cache':false});
					}
				},
				queryParams:{
					combo:true
				},
				loadMsg: '<?php echo GetCatalog('pleasewait')?>',
				columns:[[
					{field:'productid',title:'<?php echo GetCatalog('productid')?>',width:'80px'},
					{field:'productname',title:'<?php echo GetCatalog('productname')?>',width:'200px'},
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
		field:'poqty',
		title:'<?php echo GetCatalog('poqty') ?>',
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
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'unitofmeasureid',
		title:'<?php echo GetCatalog('uom') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
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
					{field:'unitofmeasureid',title:'<?php echo GetCatalog('unitofmeasureid')?>',width:'80px'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode')?>',width:'150px'},
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
				panelWidth:'500px',
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
					{field:'slocid',title:'<?php echo GetCatalog('slocid')?>',width:'80px'},
					{field:'sloccode',title:'<?php echo GetCatalog('sloccode')?>',width:'150px'},
				]]
			}	
		},
		width:'100px',		
		sortable: true,
		formatter: function(value,row,index){
							return row.sloccode;
		}
	},
	{
		field:'netprice',
		title:'<?php echo GetCatalog('netprice') ?>',
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
				panelWidth:'500px',
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
					{field:'currencyid',title:'<?php echo GetCatalog('currencyid')?>',width:'80px'},
					{field:'currencyname',title:'<?php echo GetCatalog('currencyname')?>',width:'150px'},
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
				precision:2,
				decimalSeparator:',',
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
		field:'total',
		title:'<?php echo GetCatalog('total') ?>',
		width:'120px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'delvdate',
		title:'<?php echo GetCatalog('delvdate') ?>',
		editor:{
			type: 'datebox',
			required:true,
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'overdelvtol',
		title:'<?php echo GetCatalog('overdelvtol') ?>',
		editor:{
			type:'numberbox',
			options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'underdelvtol',
		title:'<?php echo GetCatalog('underdelvtol') ?>',
		editor:{
			type:'numberbox',
			options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
  {
		field:'itemtext',
		title:'<?php echo GetCatalog('itemtext') ?>',
		editor: 'textbox',
		sortable: true,
		width:'200px',
		formatter: function(value,row,index){
			return value;
		}
	},
	]]
});
</script>