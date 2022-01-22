<table id="dg-balancesheet" style="width:auto;height:400px">
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
		<input class="easyui-datebox" type="text" id="dlg_search_bsdate" name="dlg_search_bsdate" data-options="formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'" />
		<!--<a href="javascript:void(0)" title="Generate" class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="generatebs()"></a>-->
		<!--<a href="javascript:void(0)" title="PDF Neraca" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfbalancesheet()"></a>-->
		<!--<a href="javascript:void(0)" title="Excel Neraca" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsbalancesheet()"></a>-->
		<a href="javascript:void(0)" title="PDF Rasio Keuangan" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downratiopdfbalancesheet()"></a>
<?php }?>

	<!--<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchbalancesheet" style="width:150px">-->
</div>

<script type="text/javascript">
$('#dg-balancesheet').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-balancesheet',
		pagination: false,
		fitColumns:false,
		ctrlSelect:true,
		autoSave:false,
    url: '',
    saveUrl: '<?php echo $this->createUrl('balancesheet/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('balancesheet/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('balancesheet/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-balancesheet').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'repneracaid',
		editing: '<?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>',
		columns:[[
{
	field:'recordstatus',
	title:'',
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
	show('Message','Loading...');
	jQuery.ajax({'url':'<?php echo $this->createUrl('balancesheet/generatebs') ?>',
		'data':{			
			'companyname':$('#dlg_search_companyid').combogrid('getValue'),
			'bsdate':$('#dlg_search_bsdate').datebox('getValue')},
		'type':'post','dataType':'json',
		'success':function(data)
		{
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
	window.open('<?php echo $this->createUrl('balancesheet/downpdf') ?>?company='+companyid+'&date='+bsdate+'&per='+10);
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
	window.open('<?php echo $this->createUrl('balancesheet/downxls') ?>?company='+companyid+'&date='+bsdate+'&per='+10);
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
	window.open('<?php echo $this->createUrl('downratiopdf') ?>?company='+companyid+'&date='+bsdate+'&per='+10);
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
