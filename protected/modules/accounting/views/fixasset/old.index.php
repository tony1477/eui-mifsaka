<table id="dg-fixasset" style="width:100%;height:97%"></table>
<div id="tb-fixasset">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-fixasset').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-fixasset').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-fixasset').edatagrid('cancelRow')"></a>
	<?php }?>
        <?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvefixasset()"></a>
        <?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelpoheader()"></a>
        <?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdffixasset()"></a>
  <?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchfixasset" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-fixasset').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-fixasset',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
                url: '<?php echo $this->createUrl('fixasset/index',array('grid'=>true)) ?>',
                saveUrl: '<?php echo $this->createUrl('fixasset/save',array('grid'=>true)) ?>',
                updateUrl: '<?php echo $this->createUrl('fixasset/save',array('grid'=>true)) ?>',
                destroyUrl: '<?php echo $this->createUrl('fixasset/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-fixasset').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'fixassetid',
		editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'fixassetid',
title:'<?php echo GetCatalog('fixassetid') ?>',
sortable: true,
width:'30px',
formatter: function(value,row,index){
					return value;
					}},
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
						onChange:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var row = $("#dg-fixasset").datagrid("getEditor", {index: index, field:"poheaderid"});
								var poheaderid = $(row.target).combogrid("getValue");
								var productid = $("#dg-fixasset").datagrid("getEditor", {index: index, field:"productid"});
								var url = "<?php echo Yii::app()->createUrl('purchasing/poheader/indexdetail',array('grid'=>true)) ?>&id="+poheaderid;
								$(productid.target).combogrid("reload",url);
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'poheaderid',title:'<?php echo GetCatalog('poheaderid')?>',width:50},
							{field:'pono',title:'<?php echo GetCatalog('pono')?>',width:50}
						]]
				}	
			},
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.pono;
					}},
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
								var productid = $("#dg-fixasset").datagrid("getEditor", {index: index, field:"productid"});
								var qty = $("#dg-fixasset").datagrid("getEditor", {index: index, field:"qty"});
								var price = $("#dg-fixasset").datagrid("getEditor", {index: index, field:"price"});
								var currencyid = $("#dg-fixasset").datagrid("getEditor", {index: index, field:"currencyid"});
								var currencyrate = $("#dg-fixasset").datagrid("getEditor", {index: index, field:"currencyrate"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('purchasing/poheader/generatefixasset') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue"),},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(qty.target).combogrid('setValue',data.qty);
										$(price.target).combogrid('setValue',data.price);
										$(currencyid.target).combogrid('setValue',data.currencyid);
										$(currencyrate.target).combogrid('setValue',data.currencyrate);
									},
									'cache':false});
							}
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo GetCatalog('productid')?>',width:50},
							{field:'productname',title:'<?php echo GetCatalog('productname')?>',width:50},
						]]
				}	
			},
width:'250px',
sortable: true,
formatter: function(value,row,index){
						return row.productname;
					}},
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
			}
		},
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'price',
title:'<?php echo GetCatalog('price') ?>',
editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'0',
			}
		},
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'nilairesidu',
title:'<?php echo GetCatalog('nilairesidu') ?>',
editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'0',
			}
		},
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
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
title:'<?php echo GetCatalog('currencyrate') ?>',
editor:{
			type:'numberbox',
			options:{
				precision:0,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'0',
			}
		},
sortable: true,
width:'50px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'accakum',
title:'<?php echo GetCatalog('accakum') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						pagination:true,
						idField:'accountid',
						textField:'accountname',
						url:'<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true)) ?>',
						fitColumns:true,
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>',width:50},
							{field:'accountname',title:'<?php echo GetCatalog('accountname')?>',width:200},
						]]
				}	
			},
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.accakumname;
					}},
{
field:'accbiaya',
title:'<?php echo GetCatalog('accbiaya') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',						
						idField:'accountid',
						textField:'accountname',
						url:'<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							trx:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>',width:50},
							{field:'accountname',title:'<?php echo GetCatalog('accountname')?>',width:200},
						]]
				}	
			},
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.accbiayaname;
					}},
{
field:'accperolehan',
title:'<?php echo GetCatalog('accperolehan') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',						
						idField:'accountid',
						textField:'accountname',
						url:'<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							trx:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>',width:50},
							{field:'accountname',title:'<?php echo GetCatalog('accountname')?>',width:200},
						]]
				}	
			},
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.accperolehanname;
					}},
{
field:'umur',
title:'<?php echo GetCatalog('umur') ?>',
editor:{
			type:'numberbox',
			options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'0',
			}
		},
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'acckorpem',
title:'<?php echo GetCatalog('acckorpem') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',						
						idField:'accountid',
						textField:'accountname',
						url:'<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							trx:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>',width:50},
							{field:'accountname',title:'<?php echo GetCatalog('accountname')?>',width:200},
						]]
				}	
			},
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.acckorpemname;
					}},
{
field:'metode',
title:'<?php echo GetCatalog('metode') ?>',
editor:{
			type:'combobox',
			options:{
								panelWidth:450,
								mode:'remote',
								fitColumns:true,
								required:true,
								idField:'id',
								textField:'name',
								data:[
										{id: 1, name: "Garis Lurus"},
										{id: 2, name: "Penyusutan Aktiva Tetap Menurun Ganda"},
										{id: 3, name: "Penyusutan Aktiva Tetap Jumlah Angka Tahun"},
										{id: 4, name: "Penyusutan Aktiva Tetap Satuan Jam Kerja"}
								],
								onChange:function(value){
							var index = $(this).closest('tr.datagrid-row').attr('datagrid-row-index');
							var row = $('#dg-fixasset').edatagrid('getRows')[index];
							row['tmpname'] = value;
							var data = $(this).combobox('getData');
							for(var i=0; i<data.length; i++){
								var d = data[i];
								if (d['id'] === value){
									row['tmpname'] = d['name'];
									return;
								}
							}
						}
		},
	},
sortable: true,
width:'100px',
formatter: function(value,row){
						return row.name;
					}},
{
field:'recordstatusfixasset',
title:'<?php echo GetCatalog('recordstatus') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchfixasset(value){
	$('#dg-fixasset').edatagrid('load',{
	fixassetid:value,
        poheaderid:value,
        productid:value,
        qty:value,
        price:value,
        nilairesidu:value,
        currencyid:value,
        currencyrate:value,
        accakum:value,
        accbiaya:value,
        accperolehan:value,
        umur:value,
        acckorpem:value,
        metode:value,
        recordstatus:value,
	});
}
function downpdffixasset() {
	var ss = [];
	var rows = $('#dg-fixasset').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.fixassetid);
	}
	window.open('<?php echo $this->createUrl('fixasset/downpdf') ?>?id='+ss);
}
function downxlsfixasset() {
	var ss = [];
	var rows = $('#dg-fixasset').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.fixassetid);
	}
	window.open('<?php echo $this->createUrl('fixasset/downxls') 

?>?id='+ss);
}
</script>
