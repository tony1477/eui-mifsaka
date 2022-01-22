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
<table id="dg-prheader" style="width:100%;height:97%"></table>
<div id="tb-prheader">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addPrheader()" id="addprheader"></a>
		<a href="javascript:void(0)" title="Edit"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPrheader()" id="editprheader"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveprheader()" id="approveprheader"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelprheader()" id="rejectprheader"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfprheader()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsprheader()"></a>
	<?php }?>
		<table>
<tr>
<td><?php echo getCatalog('prheaderid')?></td>
<td><input class="easyui-textbox" id="prheader_search_prheaderid" style="width:150px"></td>
<td><?php echo getCatalog('prno')?></td>
<td><input class="easyui-textbox" id="prheader_search_prno" style="width:150px"></td>
<td><?php echo getCatalog('dano')?></td>
<td><input class="easyui-textbox" id="prheader_search_dano" style="width:150px"></td>
</tr>
<tr>
<td><?php echo getCatalog('prdate')?></td>
<td><input class="easyui-textbox" id="prheader_search_prdate" style="width:150px"></td>
<td><?php echo getCatalog('sloccode')?></td>
<td><input class="easyui-textbox" id="prheader_search_slocid" style="width:150px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="prheader_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchprheader()"></a></td>
</tr>
</table>
</div>

<div id="dlg-prheader" class="easyui-dialog" title="Purchase Requisition" style="width:auto;height:600px" 
closed="true" data-options="
resizable:true,
modal:true,
toolbar: [
{
text:'<?php echo getCatalog('save')?>',
iconCls:'icon-save',
handler:function(){
submitFormPrheader();
}
},
{
text:'<?php echo getCatalog('cancel')?>',
iconCls:'icon-cancel',
handler:function(){
$('.ajax-loader').css('visibility', 'hidden');
$('#dlg-prheader').dialog('close');
}
},
]	
">
	<form id="ff-prheader-modif" method="post" data-options="novalidate:true">
		<input type="hidden" id="prheaderid" name="prheaderid"/>
		<table cellpadding="5">
			<tr>
				<td><?php echo getCatalog('prdate')?></td>
				<td><input class="easyui-datebox" type="text" id="prdate" name="prdate" data-options="formatter:dateformatter,required:true,parser:dateparser"/></td>
			</tr>	
			<tr>
				<td><?php echo getCatalog('dano')?></td>
				<td><select class="easyui-combogrid" id="deliveryadviceid" name="deliveryadviceid" style="width:250px" data-options="
					panelWidth: 500,
					idField: 'deliveryadviceid',
					textField: 'dano',
					pagination:true,
					url: '<?php echo $this->createUrl('deliveryadvice/indexdapr',array('grid'=>true)) ?>',
					method: 'get',
					mode:'remote',
					required:true,
					onHidePanel: function(){
							jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('inventory/prheader/generatedetail') ?>',
								'data':{'id':$('#deliveryadviceid').combogrid('getValue'),'hid':$('#prheaderid').val()},
								'type':'post','dataType':'json',
								'success':function(data)
								{
									$('#dg-prmaterial').datagrid({
										queryParams: {
											id: $('#prheaderid').val()
										}
									});
								} ,
								'cache':false});
					},
					columns: [[
					{field:'deliveryadviceid',title:'<?php echo getCatalog('deliveryadviceid') ?>'},
					{field:'dadate',title:'<?php echo getCatalog('dadate') ?>'},
					{field:'dano',title:'<?php echo getCatalog('dano') ?>'},
					{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
					{field:'productplanno',title:'<?php echo getCatalog('productplanno') ?>'},
					{field:'headernote',title:'<?php echo getCatalog('headernote') ?>'},
					]],
				fitColumns: true
				">
				</select></td>
				</tr>
				<tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"/></td>
				</tr>
				</table>
				</form>
        <div class="easyui-tabs" style="width:auto;height:400px">
				<div title="Detail" style="padding:5px">
				<table id="dg-prmaterial"  style="width:100%">
				</table>
				<div id="tb-prmaterial">
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-prmaterial').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-prmaterial').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-prmaterial').edatagrid('destroyRow')"></a>
				</div>
				</div>
				</div>
				</div>
				
<script type="text/javascript">
$('#addprheader').click(function(){
    tampil_loading();
});
    
$('#editprheader').click(function(){
    tampil_loading();
});
    
$('#approveprheader').click(function(){
    tampil_loading();
});

$('#rejectprheader').click(function(){
    tampil_loading();
});

function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css('visibility', 'hidden');
}

$('#prheader_search_prheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchprheader();
			}
		}
	})
});
$('#prheader_search_prno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchprheader();
			}
		}
	})
});
$('#prheader_search_dano').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchprheader();
			}
		}
	})
});
$('#prheader_search_prdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchprheader();
			}
		}
	})
});
$('#prheader_search_slocid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchprheader();
			}
		}
	})
});
$('#prheader_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchprheader();
			}
		}
	})
});
				$('#dg-prheader').edatagrid({
				singleSelect: false,
				toolbar:'#tb-prheader',
				pagination: true,
				fitColumns:true,
				ctrlSelect:true,
				autoRowHeight:true,
				onDblClickRow: function (index,row) {
				editPrheader(index);
				},
				rowStyler: function(index,row){
				if (row.count >= 1){
				return 'background-color:blue;color:#fff;font-weight:bold;';
				}
				},
				view: detailview,
				detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-prmaterial"></table></div>';
				},
				onExpandRow: function(index,row){
				var ddvprmaterial = $(this).datagrid('getRowDetail',index).find('table.ddv-prmaterial');
				ddvprmaterial.datagrid({
				url:'<?php echo $this->createUrl('prheader/indexmaterial',array('grid'=>true)) ?>?id='+row.prheaderid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo getCatalog('pleasewait') ?>',
				pagination:true,
				height:'auto',
				width:'auto',
				columns:[[
				{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right'},
{field:'poqty',title:'<?php echo getCatalog('poqty') ?>',align:'right',formatter: function(value,row,index){
	if (row.wqty == 1) {
				return '<div style="background-color:red;color:white">'+value+'</div>';
			} else {
return value;
			}
}},
{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
{field:'reqdate',title:'<?php echo getCatalog('reqdate') ?>'},
{field:'itemtext',title:'<?php echo getCatalog('itemtext') ?>'},
				]],
				onResize:function(){
				$('#dg-prheader').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
				setTimeout(function(){
				$('#dg-prheader').datagrid('fixDetailRowHeight',index);
				},0);
				}
				});
				$('#dg-prheader').datagrid('fixDetailRowHeight',index);
				},
				url: '<?php echo $this->createUrl('prheader/index',array('grid'=>true)) ?>',
				destroyUrl: '<?php echo $this->createUrl('prheader/purge',array('grid'=>true)) ?>',
				onSuccess: function(index,row){
				show('Message',row.msg);
				$('#dg-prheader').edatagrid('reload');
				},
				onError: function(index,row){
				show('Message',row.msg);
				},
				idField:'prheaderid',
				editing: false,
				columns:[[
				{
				field:'prheaderid',
				title:'<?php echo getCatalog('prheaderid') ?>',
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
				field:'prno',
				title:'<?php echo getCatalog('prno') ?>',
				sortable: true,
				width:'130px',
				formatter: function(value,row,index){
				if (row.warna == 1){
					return '<div style="background-color:cyan;color:black">'+value+'</div>';
			} else {
				return value;
			}
				}},
				{
				field:'prdate',
				title:'<?php echo getCatalog('prdate') ?>',
				sortable: true,
				width:'100px',
				formatter: function(value,row,index){
				return value;
				}},
				{
				field:'deliveryadviceid',
				title:'<?php echo getCatalog('dano') ?>',
				sortable: true,
				width:'150px',
				formatter: function(value,row,index){
				return row.dano;
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
				width:'250px',
				formatter: function(value,row,index){
				return value;
				}},
				{
				field:'recordstatusprheader',
				title:'<?php echo getCatalog('recordstatus') ?>',
				width:'130px',
				sortable: true,
				formatter: function(value,row,index){
					return value
					},
				}
				]]});
				function searchprheader(value){
				$('#dg-prheader').edatagrid('load',{
                    prheaderid:$('#prheader_search_prheaderid').val(),
                    prdate:$('#prheader_search_prdate').val(),
                    prno:$('#prheader_search_prno').val(),
                    headernote:$('#prheader_search_headernote').val(),
                    slocid:$('#prheader_search_slocid').val(),
                    deliveryadviceid:$('#prheader_search_dano').val()
				});
				}
				function approveprheader() {
				var ss = [];
				var rows = $('#dg-prheader').edatagrid('getSelections');
				for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.prheaderid);
				}
				jQuery.ajax({'url':'<?php echo $this->createUrl('prheader/approve') ?>',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
                tutup_loading();
				show('Message',data.msg);
				$('#dg-prheader').edatagrid('reload');				
				} ,
				'cache':false});
				}
				
				function cancelprheader() {
				var ss = [];
				var rows = $('#dg-prheader').edatagrid('getSelections');
				for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.prheaderid);
				}
				jQuery.ajax({'url':'<?php echo $this->createUrl('prheader/delete') ?>',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
                tutup_loading();
				show('Message',data.msg);
				$('#dg-prheader').edatagrid('reload');				
				} ,
				'cache':false});
				}
				
				function downpdfprheader() {
				var ss = [];
				var rows = $('#dg-prheader').edatagrid('getSelections');
				for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.prheaderid);
				}
				window.open('<?php echo $this->createUrl('prheader/downpdf') ?>?id='+ss);
				}
				
				function downxlsprheader() {
				var ss = [];
				var rows = $('#dg-prheader').edatagrid('getSelections');
				for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.prheaderid);
				}
				window.open('<?php echo $this->createUrl('prheader/downxls') ?>?id='+ss);
				}
				
				function addPrheader() {
				$('#dlg-prheader').dialog('open');
				$('#ff-prheader-modif').form('clear');
				$('#ff-prheader-modif').form('load','<?php echo $this->createUrl('prheader/GetData') ?>');
				$('#prdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
				}
				
				function editPrheader($i) {
				var row = $('#dg-prheader').datagrid('getSelected');
				var docmax = <?php echo CheckDoc('apppr') ?>;
	var docstatus = row.recordstatus;
				if(row) {
					if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
				$('#dlg-prheader').dialog('open');
				$('#ff-prheader-modif').form('load',row);
		}
				}
				else {
				show('Message','chooseone');
				}
				}
				
				function submitFormPrheader(){
				$('#ff-prheader-modif').form('submit',{
				url:'<?php echo $this->createUrl('prheader/save') ?>',
				onSubmit:function(){
                tutup_loading();
				return $(this).form('enableValidation').form('validate');
				},
				success:function(data){
				var data = eval('(' + data + ')');  // change the JSON string to javascript object
				show('Pesan',data.msg)
				if (data.isError == false){
        $('#dg-prheader').datagrid('reload');
        $('#dlg-prheader').dialog('close');
				}
				}
				});	
				}
				
				function clearFormPrheader(){
				$('#ff-prheader-modif').form('clear');
				}
				
				function cancelFormPrheader(){
                tutup_loading();
				$('#dlg-prheader').dialog('close');
				}
				
				$('#ff-prheader-modif').form({
				onLoadSuccess: function(data) {
				$('#dg-prmaterial').datagrid({
				queryParams: {
				id: $('#prheaderid').val()
				}
				});
				}})
				
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
				
				$('#dg-prmaterial').edatagrid({
				iconCls: 'icon-edit',	
				singleSelect: false,
				ctrlSelect:true,
				idField:'prmaterialid',
				editing: true,
				toolbar:'#tb-prmaterial',
				fitColumn: true,
				pagination:true,
				url: '<?php echo $this->createUrl('prheader/searchmaterial',array('grid'=>true)) ?>',
				updateUrl: '<?php echo $this->createUrl('prheader/savematerial',array('grid'=>true))?>',
				destroyUrl: '<?php echo $this->createUrl('prheader/purgedetail',array('grid'=>true))?>',
				onSuccess: function(index,row){
				show('Pesan',row.msg);
				$('#dg-prmaterial').edatagrid('reload');
				},
				onError: function(index,row){
				show('Pesan',row.msg);
				},
				onBeforeSave: function(index){
				var row = $('#dg-prmaterial').edatagrid('getSelected');
				if (row)
				{
				row.prheaderid = $('#prheaderid').val();
				}
				},
				columns:[[
				{
				field:'prheaderid',
				title:'<?php echo getCatalog('prheaderid') ?>',
				hidden:true,
				sortable: true,
				formatter: function(value,row,index){
				return value;
				}
				},
				{
				field:'prmaterialid',
				title:'<?php echo getCatalog('prmaterialid') ?>',
				hidden:true,
				sortable: true,
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
				readonly:true,
				queryParams:{
				combo:true
				},
				loadMsg: '<?php echo getCatalog('pleasewait')?>',
				columns:[[
				{field:'productid',title:'<?php echo getCatalog('productid')?>'},
				{field:'productname',title:'<?php echo getCatalog('productname')?>'},
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
				field:'requestedbyid',
				title:'<?php echo getCatalog('requestedby') ?>',
				editor:{
				type:'combogrid',
				options:{
				panelWidth:450,
				mode : 'remote',
				method:'get',
				idField:'requestedbyid',
				textField:'description',
				url:'<?php echo $this->createUrl('requestedby/index',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				queryParams:{
				combo:true
				},
				loadMsg: '<?php echo getCatalog('pleasewait')?>',
				columns:[[
				{field:'requestedbyid',title:'<?php echo getCatalog('requestedbyid')?>'},
				{field:'description',title:'<?php echo getCatalog('description')?>'},
				]]
				}	
				},
				width:'150px',
				sortable: true,
				formatter: function(value,row,index){
				return row.description;
				}
				},
        {
				field:'reqdate',
				title:'<?php echo getCatalog('reqdate') ?>',
				editor: {
					type:'datebox',
					options:{
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
				field:'itemtext',
				title:'<?php echo getCatalog('itemtext') 

?>',
				editor:{
					type:'textbox',
					options:{
					required:true
					}
				},
				width:'150px',
				sortable: true,
				formatter: function(value,row,index){
				return value;
				}
				},
				]]
				});
				</script>
