<table id="dg-ekspedisi" style="width:100%;height:97%"></table>
<div id="tb-ekspedisi">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addEkspedisi()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editEkspedisi()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveekspedisi()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelekspedisi()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfekspedisi()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchekspedisi" style="width:150px">
</div>

<div id="dlg-ekspedisi" class="easyui-dialog" title="Ekspedisi" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormEkspedisi();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-ekspedisi').dialog('close');
			}
		},
	]	
	">
	<form id="ff-ekspedisi-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="ekspedisiid" id="ekspedisiid"></input>
		<table cellpadding="5">
                        <tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
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
								url: '<?php echo Yii::app()->createUrl('admin/company/indexcombo',array('grid'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('ekspedisi')?></td>
				<td><select class="easyui-combogrid" id="addressbookid" name="addressbookid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'addressbookid',
								required: true,
								textField: 'fullname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/supplier/index',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								queryParams:{
									combo:true,
								},
								columns: [[
										{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>',width:80},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
                        <tr>
				<td><?php echo GetCatalog('amount')?></td>
				<td><input class="easyui-numberbox" type="text" id="amount" name="amount" value="1" data-options="required:true,precision:4, decimalSeparator:',', groupSeparator:'.'" ></td>
			</tr>
                        <tr>
				<td><?php echo GetCatalog('currency')?></td>
				<td><select class="easyui-combogrid" id="currency" name="currencyid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'currencyid',
								required: true,
								textField: 'currencyname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('admin/currency/index',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								queryParams:{
									combo:true,
								},
								columns: [[
										{field:'currencyid',title:'<?php echo GetCatalog('currencyid') ?>',width:80},
										{field:'currencyname',title:'<?php echo GetCatalog('currencyname') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('rate')?></td>
				<td><input class="easyui-numberbox" type="text" name="currencyrate" data-options="required:true,precision:4, decimalSeparator:',', groupSeparator:'.'" ></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="PO" style="padding:5px">
				<table id="dg-ekspedisipo"  style="width:100%">
				</table>
				<div id="tb-ekspedisipo">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-ekspedisipo').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-ekspedisipo').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-ekspedisipo').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-ekspedisipo').edatagrid('destroyRow')"></a>
				</div>
			</div>
      <div title="Material" style="padding:5px">
				<table id="dg-eksmat"  style="width:100%">
				</table>
				<div id="tb-eksmat">
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-eksmat').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-eksmat').edatagrid('cancelRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$('#dg-ekspedisi').edatagrid({
		singleSelect: false,
		toolbar:'#tb-ekspedisi',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editEkspedisi(index);
		},
    view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-ekspedisipo"></table><table class="ddv-eksmat"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvekspedisipo = $(this).datagrid('getRowDetail',index).find('table.ddv-ekspedisipo');
			var ddveksmat = $(this).datagrid('getRowDetail',index).find('table.ddv-eksmat');
			ddvekspedisipo.datagrid({
				url:'<?php echo $this->createUrl('ekspedisi/indexpo',array('grid'=>true)) ?>?id='+row.ekspedisiid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				pagination:true,
				loadMsg:'',
				height:'auto',
				width:'600px',
				columns:[[
					{field:'pono',title:'<?php echo GetCatalog('pono') ?>'},
          {field:'docdate',title:'<?php echo GetCatalog('docdate') ?>'},
					{field:'supplier',title:'<?php echo GetCatalog('supplier') ?>'},
				]],
				onResize:function(){
						$('#dg-ekspedisi').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-ekspedisi').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			ddveksmat.datagrid({
				url:'<?php echo $this->createUrl('ekspedisi/indexmaterial',array('grid'=>true)) ?>?id='+row.ekspedisiid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				pagination:true,
				loadMsg:'',
				height:'auto',
				width:'800px',
				columns:[[
					{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo GetCatalog('qty') ?>'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
					{field:'expense',title:'<?php echo GetCatalog('expense') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currencyname') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('rate') ?>'},
				]],
				onResize:function(){
						$('#dg-ekspedisi').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-ekspedisi').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-ekspedisi').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('ekspedisi/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-ekspedisi').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'ekspedisiid',
		editing: false,
		columns:[[
		{
field:'ekspedisiid',
title:'<?php echo GetCatalog('ekspedisiid') ?>',
sortable: true,
width:'60px',
formatter: function(value,row,index){
					return value;
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
field:'companyid',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
					return row.companyname;
					}},
					{
field:'ekspedisino',
title:'<?php echo GetCatalog('ekspedisino') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'addressbookid',
title:'<?php echo GetCatalog('ekspedisi') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.supplier;
					}},
{
field:'amount',
title:'<?php echo GetCatalog('amount') ?>',
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
					}},
{
field:'currencyid',
title:'<?php echo GetCatalog('currency') ?>',
sortable: true,
width:'70px',
formatter: function(value,row,index){
						return row.currencyname;
					}},
{
field:'currencyrate',
title:'<?php echo GetCatalog('ratevalue') ?>',
sortable: true,
width:'70px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatusekspedisi',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'center',
width:'120px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchekspedisi(value){
	$('#dg-ekspedisi').edatagrid('load',{
	ekspedisiid:value,
        docdate:value,
        addressbookid:value,
				ekspedisino:value,
        amount:value,
        currencyid:value,
        currencyrate:value,
        recordstatus:value,
	});
}
function approveekspedisi() {
	var ss = [];
	var rows = $('#dg-ekspedisi').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ekspedisiid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('ekspedisi/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-ekspedisi').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelekspedisi() {
	var ss = [];
	var rows = $('#dg-ekspedisi').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ekspedisiid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('ekspedisi/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-ekspedisi').edatagrid('reload');				
		} ,
		'cache':false});
};
function addEkspedisi() {
		$('#dlg-ekspedisi').dialog('open');
		$('#ff-ekspedisi-modif').form('clear');
		$('#ff-ekspedisi-modif').form('load','<?php echo $this->createUrl('ekspedisi/getdata') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editEkspedisi($i) {
	var row = $('#dg-ekspedisi').datagrid('getSelected');
	if(row) {
		$('#dlg-ekspedisi').dialog('open');
		$('#ff-ekspedisi-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormEkspedisi(){
	$('#ff-ekspedisi-modif').form('submit',{
		url:'<?php echo $this->createUrl('ekspedisi/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-ekspedisi').datagrid('reload');
        $('#dlg-ekspedisi').dialog('close');
			}
    }
	});	
};

function clearFormEkspedisi(){
		$('#ff-ekspedisi-modif').form('clear');
};

function cancelFormEkspedisi(){
		$('#dlg-ekspedisi').dialog('close');
};
$('#ff-ekspedisi-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-ekspedisipo').datagrid({
			queryParams: {
				id: $('#ekspedisiid').val()
			}
		});
                $('#dg-eksmat').datagrid({
			queryParams: {
				id: $('#ekspedisiid').val()
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
function downpdfekspedisi() {
	var ss = [];
	var rows = $('#dg-ekspedisi').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ekspedisiid);
	}
	window.open('<?php echo $this->createUrl('ekspedisi/downpdf') ?>?id='+ss);
}
function downxlsekspedisi() {
	var ss = [];
	var rows = $('#dg-ekspedisi').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ekspedisiid);
	}
	window.open('<?php echo $this->createUrl('ekspedisi/downxls') ?>?id='+ss);
}

$('#dg-ekspedisipo').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'ekspedisipoid',
	editing: true,
	toolbar:'#tb-ekspedisipo',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('ekspedisi/searchpo',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('ekspedisi/savepo',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('ekspedisi/savepo',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('ekspedisi/purgepo',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.message);
		$('#dg-ekspedisipo').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.message);
	},
	onBeforeSave: function(index){
		var row = $('#dg-ekspedisipo').edatagrid('getSelected');
		if (row)
		{
			row.ekspedisiid = $('#ekspedisiid').val();
		}
	},
	onAfterSave: function(index){
		$('#dg-eksmat').edatagrid('reload');
	},
        columns:[[
	{
		field:'ekspedisiid',
		title:'<?php echo GetCatalog('ekspedisiid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'ekspedisipoid',
		title:'<?php echo GetCatalog('ekspedisipoid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'poheaderid',
		title:'<?php echo GetCatalog('pono') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'poheaderid',
						textField:'pono',
						url:'<?php echo Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true)) ?>',
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
								var poheaderid = $("#dg-ekspedisipo").datagrid("getEditor", {index: index, field:"poheaderid"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('purchasing/poheader/getdata') ?>',
									'data':{'poheaderid':$(poheaderid.target).combogrid("getValue"),},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										
									},
									'cache':false});
							}
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'poheaderid',title:'<?php echo GetCatalog('poheaderid')?>'},
							{field:'pono',title:'<?php echo GetCatalog('pono')?>'},
							{field:'docdate',title:'<?php echo GetCatalog('docdate')?>'},
							{field:'fullname',title:'<?php echo GetCatalog('supplier')?>'},
						]]
				}	
			},
                width:'150px',
		sortable: true,
		formatter: function(value,row,index){
							return row.pono;
		}
	},
	{
		field:'docdate',
		title:'<?php echo GetCatalog('docdate') ?>',
		sortable: true,
                width:'150px',
		formatter: function(value,row,index){
							return row.docdate;
		}
	},
        {
		field:'supplier',
		title:'<?php echo GetCatalog('supplier') ?>',
		sortable: true,
                width:'150px',
		formatter: function(value,row,index){
							return row.supplier;
		}
	},
	]]
});

$('#dg-eksmat').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'eksmatid',
	editing: true,
	toolbar:'#tb-eksmat',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('ekspedisi/searchmaterial',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('ekspedisi/savematerial',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('ekspedisi/savematerial',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('ekspedisi/purgematerial',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.message);
		$('#dg-eksmat').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.message);
	},
	onBeforeSave: function(index){
		var row = $('#dg-eksmat').edatagrid('getSelected');
		if (row)
		{
			row.ekspedisiid = $('#ekspedisiid').val();
		}
	},
        columns:[[
	{
		field:'ekspedisiid',
		title:'<?php echo GetCatalog('ekspedisiid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'ekspedisipoid',
		title:'<?php echo GetCatalog('ekspedisipoid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'eksmatid',
		title:'<?php echo GetCatalog('eksmatid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productid',
		title:'<?php echo GetCatalog('productname') ?>',
    width:'150px',
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
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo GetCatalog('productid')?>',width:50},
							{field:'productname',title:'<?php echo GetCatalog('productname')?>',width:200},
						]]
				}	
			},
		sortable: true,
		formatter: function(value,row,index){
							return row.productname;
		}
	},
        {
		field:'qty',
		title:'<?php echo GetCatalog('qty') ?>',
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
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
        {
		field:'uomid',
		title:'<?php echo GetCatalog('uom') ?>',
		width:'100px',
readonly:true,		
		sortable: true,
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
        {
		field:'expense',
		title:'<?php echo GetCatalog('expense') ?>',
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
		field:'currencyrate',
		title:'<?php echo GetCatalog('ratevalue') 

?>',
		editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:'.',
				groupSeparator:',',
				required:true,
			}
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	]]
});
</script>
