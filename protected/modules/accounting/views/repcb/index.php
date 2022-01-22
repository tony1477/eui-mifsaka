<table id="dg-repcb" style="width:100%;height:97%"></table>
<div id="tb-repcb">
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepcb()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('cbid')?></td>
<td><input class="easyui-textbox" id="repcb_search_cbid" style="width:150px"></td>
<td><?php echo GetCatalog('company')?></td>
<td><input class="easyui-textbox" id="repcb_search_company" style="width:150px"></td>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="repcb_search_docdate" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('cashbankno')?></td>
<td><input class="easyui-textbox" id="repcb_search_cashbankno" style="width:150px"></td>
<td><?php echo GetCatalog('receiptno')?></td>
<td><input class="easyui-textbox" id="repcb_search_receiptno" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="repcb_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchrepcb()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repcb_search_cbid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcb();
			}
		}
	})
});
$('#repcb_search_company').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcb();
			}
		}
	})
});
$('#repcb_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcb();
			}
		}
	})
});
$('#repcb_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcb();
			}
		}
	})
});
$('#repcb_search_cashbankno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcb();
			}
		}
	})
});
$('#repcb_search_receiptno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcb();
			}
		}
	})
});
$('#dg-repcb').edatagrid({
		singleSelect: false,
		toolbar:'#tb-repcb',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-cbacc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvcbacc = $(this).datagrid('getRowDetail',index).find('table.ddv-cbacc');
			ddvcbacc.datagrid({
				url:'<?php echo $this->createUrl('repcb/indexacc',array('grid'=>true)) ?>?id='+row.cbid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				pagination:true,
				loadMsg:'',
				height:'auto',
				width:'100%',
				showFooter:true,
				columns:[[
					{field:'debplantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
					{field:'accdebitname',title:'<?php echo GetCatalog('accdebitname') ?>'},
					{field:'description',title:'<?php echo GetCatalog('description') ?>'},
					{field:'fullname',title:'<?php echo GetCatalog('employee') ?>'},
					{field:'customername',title:'<?php echo GetCatalog('customer') ?>'},
					{field:'suppliername',title:'<?php echo GetCatalog('supplier') ?>'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>',align:'right'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'chequeno',title:'<?php echo GetCatalog('chequeno') ?>'},
					{field:'tglcair',title:'<?php echo GetCatalog('tglcair') ?>',formatter: function(value,row,index){if (value == '01-01-1970'){return '';} else {return value;}}},
					{field:'tgltolak',title:'<?php echo GetCatalog('tgltolak') ?>',formatter: function(value,row,index){if (value == '01-01-1970'){return '';} else {return value;}}},
                    {field:'credplantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
					{field:'acccreditname',title:'<?php echo GetCatalog('acccreditname') ?>'},
				]],
				onResize:function(){
						$('#dg-repcb').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repcb').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repcb').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('repcb/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repcb').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cbid',
		editing: false,
		columns:[[
		{
field:'cbid',
title:'<?php echo GetCatalog('cbid') ?>',
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
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
editor:'text',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'cashbankno',
title:'<?php echo GetCatalog('cashbankno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'companyid',
title:'<?php echo GetCatalog('companyname') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return row.companyname;
					}}, 
{
	field:'isin',
	title:'<?php echo GetCatalog('isin') ?>',
	align:'center',
	width:'100px',
	sortable: true,
	formatter: function(value,row,index){
						if (value == 1){
										return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
										return '';
						}
}},
{
field:'receiptno',
title:'<?php echo GetCatalog('receiptno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'headernote',
title:'<?php echo GetCatalog('headernote') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return row.recordstatusname;
					}},
		]]
});
function searchrepcb(value){
	$('#dg-repcb').edatagrid('load',{
	cbid:$('#repcb_search_cbid').val(),
	docdate:$('#repcb_search_docdate').val(),
	cashbankno:$('#repcb_search_cashbankno').val(),
	companyid:$('#repcb_search_company').val(),
	headernote:$('#repcb_search_headernote').val(),
	receiptno:$('#repcb_search_receiptno').val()
	});
};

function clearFormrepcb(){
		$('#ff-repcb-modif').form('clear');
};

function cancelFormrepcb(){
		$('#dlg-repcb').dialog('close');
};
$('#ff-repcb-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-repcb').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-cbacc').datagrid({
			queryParams: {
				id: $('#cbid').val()
			}
		});
		if (data.isin == 1)
				{
						$('#isin').prop('checked', true);
				} else
				{
						$('#isin').prop('checked', false);
				}
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
function downpdfrepcb() {
	var ss = [];
	var rows = $('#dg-repcb').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbid);
	}
	window.open('<?php echo $this->createUrl('repcb/downpdf') ?>?id='+ss);
}
function downxlsrepcb() {
	var ss = [];
	var rows = $('#dg-repcb').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbid);
	}
	window.open('<?php echo $this->createUrl('repcb/downxls') ?>?id='+ss);
}

$('#dg-cbacc').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'cbaccid',
	editing: true,
	toolbar:'#tb-cbacc',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('repcb/searchacc',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('repcb/saveacc',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('repcb/saveacc',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('repcb/purgeacc',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-cbacc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-cbacc').edatagrid('getSelected');
		if (row)
		{
			row.cbid = $('#cbid').val();
		}
	},
	columns:[[
	{
		field:'cbaccid',
		title:'<?php echo GetCatalog('cbaccid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'cbid',
		title:'<?php echo GetCatalog('cbid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
  {
		field:'debitaccid',
		title:'<?php echo GetCatalog('accdebitname') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'accountid',
						textField:'accountname',
						url:'<?php echo $this->createUrl('account/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							trx:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('accdebitname')?>'},
						]]
				}	
			},
    width:'200px',
		sortable: true,
		formatter: function(value,row,index){
							return row.accdebitname;
		}
	},
	{
		field:'description',
		title:'<?php echo GetCatalog('description') ?>',
    editor:{
			type:'textbox',
			options: {
				required:true
			}
		},
		width:'150px',		
		sortable: true,		
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'amount',
		title:'<?php echo GetCatalog('amount') ?>',
		sortable: true,
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
		width:'150px',
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
						required:true,
						pagination:true,
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
		field:'employeeid',
		title:'<?php echo GetCatalog('employee') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'employeeid',
						textField:'fullname',
						url:'<?php echo Yii::app()->createUrl('hr/employee/indexcompany',array('grid'=>true,'combo'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
                        onBeforeLoad: function(param){
							 param.companyid = $('#companyid').combogrid('getValue');
				        },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'employeeid',title:'<?php echo GetCatalog('employeeid')?>'},
							{field:'fullname',title:'<?php echo GetCatalog('fullname')?>'},
						]]
				}	
			},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.fullname;
		}
	},
	{
		field:'currencyrate',
		title:'<?php echo GetCatalog('ratevalue') ?>',
		editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'1'
			}
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'chequeid',
		title:'<?php echo GetCatalog('chequeno') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'chequeid',
						textField:'chequeno',
						url:'<?php echo Yii::app()->createUrl('accounting/repcb/indexcheque',array('grid'=>true)) ?>',
						fitColumns:true,
						//required:true,
						pagination:true,
						queryParams:{
							trx:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'chequeid',title:'<?php echo GetCatalog('chequeid')?>'},
							{field:'chequeno',title:'<?php echo GetCatalog('chequeno')?>'},
						]]
				}	
			},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.chequeno;
		}
	},
	{
		field:'tglcair',
		title:'<?php echo GetCatalog('tglcair') ?>',
		width:'100px',
		editor:{
			type:'datebox',
			options:{parser: dateparser,
			formatter: dateformatter,}
		},
		sortable: true,
		formatter: function(value,row,index){
						if (value == '01-01-1970'){
										return '';
						} else {
										return value;
						}
		}
	},
	{
		field:'tgltolak',
		title:'<?php echo GetCatalog('tgltolak') ?>',
		width:'100px',
		editor:{
			type:'datebox',
			options:{parser: dateparser,
			formatter: dateformatter,}
		},
		sortable: true,
		formatter: function(value,row,index){
						if (value == '01-01-1970'){
										return '';
						} else {
										return value;
						}
		}
	},
	{
		field:'creditaccid',
		title:'<?php echo GetCatalog('acccreditname') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'accountid',
						textField:'accountname',
						url:'<?php echo $this->createUrl('account/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							trx:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('acccreditname')?>'},
						]]
				}	
			},
    width:'200px',
		sortable: true,
		formatter: function(value,row,index){
							return row.acccreditname;
		}
	},
	]]
});

</script>
