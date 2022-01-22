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
<table id="dg-balancesheet" style="width:auto;height:97%">
</table>
<div id="tb-balancesheet">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-balancesheet').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-balancesheet').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-balancesheet').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-balancesheet').edatagrid('destroyRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	<select class="easyui-combogrid" id="dlg_search_companyid" name="dlg_search_companyid" style="width:250px" data-options="
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
		<input class="easyui-datebox" type="text" id="dlg_search_bsdate" name="dlg_search_bsdate" data-options="formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'"></input>
		<a href="javascript:void(0)" title="Generate" class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="generatebs()"></a>
		<a href="javascript:void(0)" title="PDF Neraca" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfbalancesheet()"></a>
		<a href="javascript:void(0)" title="Excel Neraca" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsbalancesheet()"></a>
		<a href="javascript:void(0)" title="PDF Rasio Keuangan" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downratiopdfbalancesheet()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formBalancesheet" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileBalancesheet" id="FileBalancesheet" style="display:inline">
			<input type="submit" value="Upload" name="submitBalancesheet" style="display:inline">
		</form>
	<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchbalancesheet" style="width:150px">
</div>

<script type="text/javascript">
$("#formBalancesheet").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('accounting/balancesheet/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data);
			$('#dg-balancesheet').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#dg-balancesheet').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-balancesheet',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoSave:false,
	url: '<?php echo $this->createUrl('//accounting/balancesheet/index',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('//accounting/balancesheet/save',array('grid'=>true)) ?>',
	updateUrl: '<?php echo $this->createUrl('//accounting/balancesheet/save',array('grid'=>true)) ?>',
	destroyUrl: '<?php echo $this->createUrl('//accounting/balancesheet/purge',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg,'info');
		$('#dg-balancesheet').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg,'error');
		$('#dg-balancesheet').edatagrid('reload');
	},
	idField:'repneracaid',
	editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
	columns:[[
	{
		field:'repneracaid',
		title:'<?php echo GetCatalog('repneracaid') ?>',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'companyid',
		title:'<?php echo GetCatalog('company') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'companyid',
				textField:'companyname',
				url:'<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				loadMsg: '<?php echo GetCatalog('pleasewait')?>',
				columns:[[
					{field:'companyid',title:'<?php echo GetCatalog('companyid')?>',width:'50px'},
					{field:'companyname',title:'<?php echo GetCatalog('companyname')?>',width:'200px'},
				]]
			}	
		},
		width:'320px',
		sortable: true,
		formatter: function(value,row,index){
			return row.companyname;
	}},
	{
		field:'accountid',
		title:'<?php echo GetCatalog('accountname') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'accountid',
				textField:'accountname',
				url:'<?php echo $this->createUrl('account/index',array('grid'=>true)) ?>',
				fitColumns:true,
				required:true,
				pagination:true,
				queryParams:{
					combo:true
				},
				loadMsg: '<?php echo GetCatalog('pleasewait')?>',
				columns:[[
					{field:'accountid',title:'<?php echo GetCatalog('accountid')?>',width:'80px'},
					{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>',width:'150px'},
					{field:'accountname',title:'<?php echo GetCatalog('accountname')?>',width:'200px'},
					{field:'accounttypename',title:'<?php echo GetCatalog('accounttypename')?>',width:'200px'},
				]]
			}	
		},
		width:'250px',
		sortable: true,
		formatter: function(value,row,index){
							return row.accountname;
		}
	},
	{
		field:'isdebet',
		title:'<?php echo GetCatalog('isdebet') ?>',
		align:'center',
		width:'100px',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		formatter: function(value,row,index){
			if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
			} else {
							return '';
			}
	}},
	{
		field:'accformula',
		title:'<?php echo GetCatalog('accformula') ?>',
		editor:'text',
		fitColumns:true,
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
			return value;
		}},
	{
		field:'aftacc',
		title:'<?php echo GetCatalog('aftacc') ?>',
		editor:'numberbox',
		width:'60px',
		sortable: true,
		formatter: function(value,row,index){
			if (value > 0){return value} else {return ''};
	}},
	{
		field:'nourut',
		title:'<?php echo GetCatalog('nourut') ?>',
		editor:'numberbox',
		width:'60px',
		sortable: true,
		formatter: function(value,row,index){
			if (value > 0){return value} else {return ''};
	}},
	{
		field:'recordstatus',
		title:'<?php echo GetCatalog('recordstatus') ?>',
		align:'center',
		width:'80px',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		formatter: function(value,row,index){
			if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
			} else {
							return '';
			}
	}},
	]]
});
function searchbalancesheet(value){
	$('#dg-balancesheet').edatagrid('load',{
		repneracaid:value,
		companyid:value,
		accountid:value,
		isdebet:value,
		nourut:value,
		recordstatus:value,
	});
}
function generatebs() {
	$('.ajax-loader').css('visibility', 'visible');
	jQuery.ajax({'url':'<?php echo $this->createUrl('//accounting/balancesheet/generatebs') ?>',
		'data':{			
			'companyname':$('#dlg_search_companyid').combogrid('getValue'),
			'bsdate':$('#dlg_search_bsdate').datebox('getValue')},
		'type':'post','dataType':'json',
		'success':function(data)
		{
				$('.ajax-loader').css("visibility", "hidden");
			show('Message',data.msg);
			$('#dg-balancesheet').edatagrid('reload');				
		} ,
	'cache':false});
}
function downpdfbalancesheet() {
	var ss = [];
	var rows = $('#dg-balancesheet').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.repneracaid);
	}
	var bsdate = $('#dlg_search_bsdate').datebox('getValue');
	var companyid = $('#dlg_search_companyid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/balancesheet/downpdf') ?>?company='+companyid+'&date='+bsdate+'&per='+10);
}
function downxlsbalancesheet() {
	var ss = [];
	var rows = $('#dg-balancesheet').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.repneracaid);
	}
	var bsdate = $('#dlg_search_bsdate').datebox('getValue');
	var companyid = $('#dlg_search_companyid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/balancesheet/downxls') ?>?company='+companyid+'&date='+bsdate+'&per='+10);
}
function downratiopdfbalancesheet() {
	var ss = [];
	var rows = $('#dg-balancesheet').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.repneracaid);
	}
	var bsdate = $('#dlg_search_bsdate').datebox('getValue');
	var companyid = $('#dlg_search_companyid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/balancesheet/downratiopdf') ?>?company='+companyid+'&date='+bsdate+'&per='+10);
}
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
</script>