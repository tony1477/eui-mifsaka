<!-- Data Grid ( #dg-cheque ) -->
<input type="hidden" name="companyid" id="companyid" value="0" />
<table id="dg-cheque"  style="width:100%;height:100%"></table>
<div id="tb-cheque">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-cheque').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-cheque').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-cheque').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-cheque').edatagrid('destroyRow')"></a>
	<?php }?>
    <?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvecheque()"></a>
	<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<!-- <a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelcheque()"></a>-->
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfcheque()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlscheque()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchcheque" style="width:150px">
</div>

<script type="text/javascript">
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
$('#dg-cheque').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-cheque',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoSave:false,
	url: '<?php echo $this->createUrl('cheque/index',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cheque/save',array('grid'=>true)) ?>',
	updateUrl: '<?php echo $this->createUrl('cheque/save',array('grid'=>true)) ?>',
	destroyUrl: '<?php echo $this->createUrl('cheque/purge',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg);
		$('#dg-cheque').edatagrid('reload');
	},
    onBeforeSave: function(index,row) {
        $("#companyid").val('');
    },
	onError: function(index,row){
		show('Message',row.msg);
	},
	idField:'chequeid',
	editing: '<?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>',
	columns:[[
	{
					field:'chequeid',
					title:'<?php echo GetCatalog('chequeid') ?>',
					//hidden:true,
					readonly:true,
					sortable: true,
					formatter: function(value,row,index){
															return value;
					}
	 },
	 {
					field:'companyid',
					title:'<?php echo GetCatalog('companyname') ?>',
					editor:{
                          type:'combogrid',
                          options:{
                                  panelWidth:450,
                                  mode : 'remote',
                                  method:'get',
                                  idField:'companyid',
                                  textField:'companyname',
                                  url:'<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
                                  fitColumns:true,
                                  required:true,
                                  pagination:true,
                                  onSelect: function(index,row){
                                      console.log(row.companyid);
                                      var companyid = row.companyid;
                                      $("input[name='companyid']").val(row.companyid);
                                  },
                                  loadMsg: '<?php echo GetCatalog('pleasewait')?>',
                                  columns:[[
                                          {field:'companyid',title:'<?php echo GetCatalog('companyid')?>'},
                                          {field:'companyname',title:'<?php echo GetCatalog('companyname')?>'},
                                  ]]
                                },
							},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
															return row.companyname;
					}
			},
            {
                field:'plantid',
                title:'<?php echo GetCatalog('plantcode')?>',
                editor:{
                        type:'combogrid',
                        options:{
                                panelWidth:450,
                                mode : 'remote',
                                method:'get',
                                idField:'plantid',
                                textField:'plantcode',
                                url:'<?php echo Yii::app()->createUrl('common/plant/index',array('grid'=>true))?>',
                                fitColumns:true,
                                required:true,
                                pagination:true,
                                queryParams:{
                                    trxcom:true
                                },
                                onBeforeLoad: function(param) {
                                    let companyid = $("input[name='companyid']").val();
                                    if(companyid=='')
                                    {
                                        let row = $('#dg-cheque').datagrid('getSelected');
                                        param.companyid = row.companyid;
                                    }
                                    else
                                    {
                                        param.companyid = $("input[name='companyid']").val(); 
                                    }
                                },
                                loadMsg: '<?php echo GetCatalog('pleasewait')?>',
                                columns:[[
                                    {field:'plantid',title:'<?php echo GetCatalog('plantid')?>'},
                                    {field:'plantcode',title:'<?php echo GetCatalog('plantcode')?>'},
                                    {field:'description',title:'<?php echo GetCatalog('description')?>'},
                                ]]
                        }	
                    },
                        width:'150px',
                sortable: true,
                formatter: function(value,row,index){
                      return row.plantcode;
                }
            },
			{
					field:'tglbayar',
					title:'<?php echo GetCatalog('tglbayar') ?>',
					width:'150px',
					editor:{
							type:'datebox',
							options: {
									parser: dateparser,
									formatter: dateformatter,
									required:true
							}
					},
					sortable: true,
					formatter: function(value,row,index){
															return value;
					}
			},
			{
					field:'chequeno',
					title:'<?php echo GetCatalog('chequeno') ?>',
					width:'150px',
					editor:{
							type:'textbox',
							options: {
									required:true
							}
					},
					sortable: true,
					formatter: function(value,row,index){
															return value;
					}
			},
			{
					field:'bankid',
					title:'<?php echo GetCatalog('bankname') ?>',
					editor:{
									type:'combogrid',
									options:{
													panelWidth:450,
													mode : 'remote',
													method:'get',
													idField:'bankid',
													textField:'bankname',
													url:'<?php echo Yii::app()->createUrl('accounting/cb/indexbank',array('grid'=>true)) ?>',
													fitColumns:true,
													required:true,
													pagination:true,
													queryParams:{
															combo:true
													},
													loadMsg: '<?php echo GetCatalog('pleasewait')?>',
													columns:[[
															{field:'bankid',title:'<?php echo GetCatalog('bankid')?>'},
															{field:'bankname',title:'<?php echo GetCatalog('bankname')?>'},
													]]
									}	
							},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
															return row.bankname;
					}
			},
			{
					field:'amount',
					title:'<?php echo GetCatalog('chequeamount') ?>',
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
					field:'tglcheque',
					title:'<?php echo GetCatalog('tglcheque') ?>',
					width:'150px',
					editor:{
							type:'datebox',
							options: {
									parser: dateparser,
									formatter: dateformatter,
									required:true
							}
					},
					sortable: true,
					formatter: function(value,row,index){
															return value;
					}
			},
			{
					field:'tgltempo',
					title:'<?php echo GetCatalog('tgltempo') ?>',
					width:'150px',
					editor:{
							type:'datebox',
							options: {
									parser: dateparser,
									formatter: dateformatter,
									required:true
							}
					},
					sortable: true,
					formatter: function(value,row,index){
															return value;
					}
			},
			{
					field:'addressbookid',
					title:'<?php echo GetCatalog('customervendor') ?>',
					editor:{
									type:'combogrid',
									options:{
													panelWidth:450,
													mode : 'remote',
													method:'get',
													idField:'addressbookid',
													textField:'fullname',
													url:'<?php echo Yii::app()->createUrl('common/addressbook/index',array('grid'=>true)) ?>',
													fitColumns:true,
													required:true,
													pagination:true,
													queryParams:{
															combo:true
													},
													loadMsg: '<?php echo GetCatalog('pleasewait')?>',
													columns:[[
															{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid')?>'},
															{field:'fullname',title:'<?php echo GetCatalog('fullname')?>'},
															{field:'iscustomer',title:'<?php echo GetCatalog('iscustomer')?>'},
															{field:'isvendor',title:'<?php echo GetCatalog('isvendor')?>'},
													]]
									}	
							},
					width:'200px',
					sortable: true,
					formatter: function(value,row,index){
															return row.fullname;
					}
			},
			{
	field:'iscustomer',
	title:'Is Customer?',
	width:'80px',
	editor:{type:'checkbox',options:{on:'1',off:'0'}},
	sortable: true,
	formatter: function(value,row,index){
													if (value == 1){
															return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
													} else {
															return '';
													}
					}
			},	
			{
					field:'tglcair',
					title:'<?php echo GetCatalog('tglcair') ?>',
					width:'150px',
					editor:{
							type:'datebox',
							options: {
									parser: dateparser,
									formatter: dateformatter,
									readonly:true,
									//required:true
							}
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
					width:'150px',
					editor:{
							type:'datebox',
							options: {
									parser: dateparser,
									formatter: dateformatter,
									readonly:true,
									//required:true
							}
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
					field:'recordstatus',
					title:'<?php echo GetCatalog('recordstatus') ?>',
					align:'center',
					sortable: true,
					formatter: function(value,row,index){
                    if (value == 2){
                            return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
                    } else if(value == 1){
                            return 'New Entry';
                    } else {
                        return 'Not Active';
                    }
					}
			},
	]]
});
function approvecheque() {
var ss = [];
var rows = $('#dg-cheque').edatagrid('getSelections');
for(var i=0; i<rows.length; i++){
        var row = rows[i];
        ss.push(row.chequeid);
}
jQuery.ajax({'url':'<?php echo $this->createUrl('cheque/approve') ?>',
    'data':{'id':ss},
            'type':'post',
            'dataType':'json',
    'success':function(data)
    {
        show('Message',data.msg);
        $('#dg-cheque').edatagrid('reload');				
    } ,
    'cache':false});
};
function cancelcheque() {
var ss = [];
var rows = $('#dg-cheque').edatagrid('getSelections');
for(var i=0; i<rows.length; i++){
        var row = rows[i];
        ss.push(row.chequeid);
}
jQuery.ajax({'url':'<?php echo $this->createUrl('cheque/delsete') ?>',
    'data':{'id':ss},
            'type':'post',
            'dataType':'json',
    'success':function(data)
    {
        show('Message',data.msg);
        $('#dg-cheque').edatagrid('reload');				
    } ,
    'cache':false});
};
function searchcheque(value){
	$('#dg-cheque').edatagrid('load',{
	chequeid:value,
chequeno:value,
companyid:value,
bankid:value,
plantid:value,
recordstatus:value,
	});
}
function downpdfcheque() {
	var ss = [];
	var rows = $('#dg-cheque').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.chequeid);
	}
	window.open('<?php echo $this->createUrl('cheque/downpdf') ?>?id='+ss);
}
function downxlscheque() {
	var ss = [];
	var rows = $('#dg-cheque').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.chequeid);
	}
	window.open('<?php echo $this->createUrl('cheque/downxls') 

?>?id='+ss);
}
</script>
