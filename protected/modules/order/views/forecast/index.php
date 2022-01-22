<!-- Data Grid ( #dg-city ) -->
<table id="dg-forecast"  style="width:1100px;height:400px"></table>
<input type="hidden" name="bomid_forecast" id="bomid_forecast" />
<div id="tb-forecast">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-forecast').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" title="Simpan" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-forecast').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-forecast').edatagrid('cancelRow')"></a>
	<select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true,
								prompt:'Company'
						">
				</select>	
<input class="easyui-textbox" name="dlg_search_bulan" id="dlg_search_bulan" data-options="prompt:'Bulan'"></input>				
<input class="easyui-textbox" name="dlg_search_tahun" id="dlg_search_tahun" data-options="prompt:'Tahun'"></input>				
		<a href="javascript:void(0)" title="Generate" class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="generatefg()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-forecast').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfforecast()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsforecast()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchforecast" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-forecast').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-forecast',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
    url: '<?php echo $this->createUrl('forecast/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('forecast/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('forecast/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('forecast/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-forecast').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'forecastid',
		editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'forecastid',
title:'<?php echo GetCatalog('forecastid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
					}},
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
						combo:true
					},
					onChange:function(newValue,oldValue) {
						if ((newValue !== oldValue) && (newValue !== ''))
						{
							var tr = $(this).closest('tr.datagrid-row');
							var index = parseInt(tr.attr('datagrid-row-index'));
							var productid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"productid"});
							var uomid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"uomid"});
							var slocid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"slocid"});
							var bomid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"bomid"});
							jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdata') ?>',
								'data':{'productid':$(productid.target).combogrid("getValue")},
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
                        $("input[name='bomid_forecast']").val(row.bomid);   
                    },
                    onHidePanel: function()
                    {
                     jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('production/bom/index',array('grid'=>true)) ?>',
                    'data':{'bomid':$('#bomid_forecast').val()},
                    'type':'post','dataType':'json',
                    'success':function(data)
                    {
                        
                       var rowss = $('#dg-forecast').edatagrid('getSelected');
                        if (rowss){
                          var index = $('#dg-forecast').edatagrid('getRowIndex', rowss);
                        }

                        var nilai = $('#bomid_forecast').val();
                        var bomid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"bomid"});
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
							var productid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"productid"});
							var uomid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"uomid"});
							var slocid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"slocid"});
							var bomid = $("#dg-forecast").datagrid("getEditor", {index: index, field:"bomid"});
							jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdata') ?>',
								'data':{'productid':$(productid.target).combogrid("getValue")},
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
		field:'uomid',
		title:'<?php echo GetCatalog('uom') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'uomid',
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
							{field:'uomid',title:'<?php echo GetCatalog('unitofmeasureid')?>',width:50},
							{field:'uomcode',title:'<?php echo GetCatalog('uomcode')?>',width:200},
						]]
				}	
			},
			width:'80px',
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
						url:'<?php echo Yii::app()->createUrl('common/sloc/indextrx',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
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
			width:'100px',
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
					textField:'bomversion',
					url:'<?php echo $this->createUrl('/production/bom/index',array('grid'=>true)) ?>',
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
field:'bulan',
title:'<?php echo GetCatalog('bulan') ?>',
editor:{
			type:'numberbox',
			options:{
				required:true,
			}
		},
width:'60px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'tahun',
title:'<?php echo GetCatalog('tahun') ?>',
editor:{
			type:'numberbox',
			options:{
				required:true,
			}
		},
width:'60px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'qty',
title:'<?php echo GetCatalog('qty') ?>',
editor:{
			type:'numberbox',
			options:{
				required:true,
			}
		},
width:'60px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
	
{
field:'qtystock',
title:'<?php echo GetCatalog('qtystock') ?>',
editor:{
			type:'numberbox',
			options:{
				readonly:true,
			}
		},
width:'80px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'qtyoutput',
title:'<?php echo GetCatalog('qtyoutput') ?>',
editor:{
			type:'numberbox',
			options:{
				readonly:true,
			}
		},
width:'80px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},	


		]]
});
    
    $('#dlg_search_bulan').textbox({
				inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
                            //alert('as');
							searchforecast();
						}
					}
				})
			});
     $('#dlg_search_tahun').textbox({
				inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
                            //alert('as');
							searchforecast();
						}
					}
				})
			});
    
function searchforecast(value){
	$('#dg-forecast').edatagrid('load',{
        bulan:$("input[name='dlg_search_bulan']").val(),
        tahun:$("input[name='dlg_search_tahun']").val(),
        companyid:$("input[name='companyid']").val(),
        });
}
    
function downpdfforecast() {
	var ss = [];
	var rows = $('#dg-forecast').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.forecastid);
	}
	var companyid = $('#companyid').combogrid('getValue');
	var bulan = $('#dlg_search_bulan').val();
	var tahun = $('#dlg_search_tahun').val();
	
	window.open('<?php echo $this->createUrl('forecast/downpdf') ?>?id='+ss+'&companyid='+companyid+'&bulan='+bulan+'&tahun='+tahun);
}
function downxlsforecast() {
	var ss = [];
	var rows = $('#dg-forecast').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.forecastid);
	}
	var companyid = $('#companyid').combogrid('getValue');
	var bulan = $('#dlg_search_bulan').val();
	var tahun = $('#dlg_search_tahun').val();
	
	window.open('<?php echo $this->createUrl('forecast/downxls') ?>?id='+ss+'&companyid='+companyid+'&bulan='+bulan+'&tahun='+tahun);
}
function generatefg() {
	jQuery.ajax({'url':'<?php echo $this->createUrl('forecast/generatefg') ?>',
		'data':{			
			'companyid':$('#companyid').combogrid('getValue'),
			'bulan':$("input[name='dlg_search_bulan']").val(),
			'tahun':$("input[name='dlg_search_tahun']").val(),},
		'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-forecast').edatagrid('reload');				
		} ,
		'cache':false});
};
</script>