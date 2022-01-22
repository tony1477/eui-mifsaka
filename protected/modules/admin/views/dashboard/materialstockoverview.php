<table id="gridstock"></table>
<div id="tb-gridstock">
<?php echo getCatalog('product')?>
<input class="easyui-textbox" id="home_search_product" style="width:150px">
<?php echo getCatalog('sloc')?>
<input class="easyui-textbox" id="home_search_sloc" style="width:150px">
<?php echo getCatalog('storagebin')?>
<input class="easyui-textbox" id="home_search_storagebin" style="width:150px">
<?php echo getCatalog('uom')?>
<input class="easyui-textbox" id="home_search_uom" style="width:150px">
<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchhomestock()"></a>
</div>
<script type="text/javascript">
$('#home_search_product').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchhomestock();
			}
		}
	})
});
$('#home_search_sloc').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchhomestock();
			}
		}
	})
});
$('#home_search_storagebin').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchhomestock();
			}
		}
	})
});
$('#home_search_uom').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchhomestock();
			}
		}
	})
});
	$('#gridstock').datagrid({
		iconCls: 'icon-edit',	
		singleSelect: true,
		idField:'usertodoid',
		toolbar:'#tb-gridstock',
		editing: true,
		pagination:true,
		fit: true,
		fitColumn: true,
		url: '<?php echo Yii::app()->createUrl('inventory/productstock/indexhome',array('grid'=>true)) ?>',
		view: detailview,
		detailFormatter:function(index,row){
			return '<div style="padding:2px"><table class="ddv-homedetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvpodetail = $(this).datagrid('getRowDetail',index).find('table.ddv-homedetail');
			ddvpodetail.datagrid({
				url:'<?php echo Yii::app()->createUrl('inventory/productstock/indexdetail',array('grid'=>true)) ?>?id='+row.productstockid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				pagination:true,
				height:'auto',
				width:'auto',
				showFooter:true,
				columns:[[
					{field:'referenceno',title:'<?php echo getCatalog('referenceno') ?>'},
					{field:'transdate',title:'<?php echo getCatalog('transdate') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>'},
				]],
				onResize:function(){
						$('#gridstock').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#gridstock').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#gridstock').datagrid('fixDetailRowHeight',index);
		},
		rowStyler:function(index,row){
			if ((row.qty < row.minstock)) {
				return 'background-color:red;color:white;';
			} else 
			if ((row.qty >= row.minstock) && (row.qty <= row.orderstock)) {
				return 'background-color:yellow;color:black;';
			}	else 
			if ((row.qty > row.orderstock) && (row.qty <= row.maxstock)) {
				return 'background-color:green;color:black;';
			} else {
				return 'background-color:white;color:black;';
			}
	},
		columns:[[
			{
					field:'productstockid',
					title:'<?php echo getCatalog('productstockid') ?>',
					sortable: true,
					width:'50px',
					formatter: function(value,row,index){
						return value;
					},
			},
			{
					field:'productid',
					title:'<?php echo getCatalog('product') ?>',
					sortable: true,
					width:'500px',
					formatter: function(value,row,index){
						return row.productname;
					},
			},				
			{
					field:'slocid',
					title:'<?php echo getCatalog('sloc') ?>',
					sortable: true,
					width:'260px',
					formatter: function(value,row,index){
						return row.sloccode+' - '+row.slocdesc;
					},
			},
			{
					field:'storagebinid',
					title:'<?php echo getCatalog('storagebin') ?>',
					sortable: true,
					width:'120px',
					formatter: function(value,row,index){
						return row.description;
					},
			},
			{
					field:'qty',
					title:'<?php echo getCatalog('qty') ?>',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
						return '<div style="text-align:right">'+value+'</div>';
					},
			},
			{
					field:'qtyinprogress',
					title:'<?php echo getCatalog('qtyinprogress') ?>',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
						return '<div style="text-align:right">'+value+'</div>';
					},
			},
			{
					field:'uomcode',
					title:'<?php echo getCatalog('uom') ?>',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					},
			},
		]]
	});
	function searchhomestock(){
		$('#gridstock').datagrid('load',{
			product:$('#home_search_product').val(),
			sloc:$('#home_search_sloc').val(),
			storagebin:$('#home_search_storagebin').val(),
			unitofmeasure:$('#home_search_uom').val(),
		});
	};
</script>