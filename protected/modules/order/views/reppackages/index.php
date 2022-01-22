<table id="dg-reppackages"  style="width:1200px;height:97%"></table>
<div id="tb-reppackages">
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreppackages()"></a>
	<?php }?>
<table>
	<tr>
		<td><?php echo GetCatalog('packageid')?></td>
		<td><input class="easyui-textbox" id="reppackages_search_packageid" style="width:50px"></td>
		<td><?php echo GetCatalog('companyname')?></td>
		<td><input class="easyui-textbox" id="reppackages_search_companyname" style="width:150px"></td>
		<td><?php echo GetCatalog('docno')?></td>
		<td><input class="easyui-textbox" id="reppackages_search_docno" style="width:150px"></td>
	</tr>
	<tr>
		<td><?php echo GetCatalog('customer')?></td>
		<td><input class="easyui-textbox" id="reppackages_search_customer" style="width:150px"></td>
		<td><?php echo GetCatalog('packagename')?></td>
		<td><input class="easyui-textbox" id="reppackages_search_packagename" style="width:150px"></td>
		<td><?php echo GetCatalog('headernote')?></td>
		<td><input class="easyui-textbox" id="reppackages_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchreppackages()"></a></td>
	</tr>
</table>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#packagetype').combobox({
    onChange:function(newValue,oldValue) {
        //console.log(newValue);
        gettype();
    }
})
});
    
    var rowIdxcp = 0; 
    var rowIdxcs = 0; 
    
    
    //function delcust() {
      //  $('#ixcustomer1').tagbox({readonly:false});
    //}
    
function setcompany() {
    var val = $('#multicompanyid').combogrid('getText');
    jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/reppackages/getmulticompany')?>',
		'data':{
			'companyid':$('#multicompanyid').combogrid('getValues'),
			'packageid':$('#packageid').val(),
			},
		'type':'post','dataType':'json',
		'success':function(data) {
			if(data.status=='success') {
				//$.fn.yiiGridView.update("TtntdetailList");
				console.log('success');
				//$("textarea[name='customers']").val(data.fullname);
				//$("#customerid").val($("input[name='multicustomerid']").val());
                $('#multicompanyid').combogrid('setValue','');
                $('#companyix').tagbox('setValues',data.companyname);
                $('#companyid').val(data.multicompany);
                //$('#ixcompany').textbox('setValue',val);
			}
			else {
				show('Message',data.msg);
			}
		},
		'cache':false});
}

function setcustomer() {
    var val = $('#multicustomerid').combogrid('getText');
    jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/reppackages/getmulticustomer')?>',
		'data':{
			'customerid':$('#multicustomerid').combogrid('getValues'),
			'packageid':$('#packageid').val(),
			},
		'type':'post','dataType':'json',
		'success':function(data) {
			if(data.status=='success') {
				//$.fn.yiiGridView.update("TtntdetailList");
				console.log('success');
				//$("textarea[name='customers']").val(data.fullname);
				//$("#customerid").val($("input[name='multicustomerid']").val());
                $('#multicustomerid').combogrid('setValue','');
                $('#customerix').tagbox('setValues',data.fullname);
                $('#customerid').val(data.multicustomer);
			}
			else {
				show('Message','error');
			}
		},
		'cache':false});
}

function gettype() {
    let type = $('#packagetype').combobox('getValue');
    if(type!='' && type != 0) {
        if(type==2) {
            console.log('2');
            $('#dcompanyid').show();
            $('#tcompanyid').show();
            $('#dcompanyix').show();
            $('#dcustomerid').hide();
            $('#tcustomerid').hide();
            $('#dcustomerix').hide();
            //$('#companyid').combogrid({required:true});
            //$('#customerid').combogrid({required:false});
            //$('#customerid').combogrid('setValue','');
            //$('#customerid').val('');
        }
        else if(type==3) {
            console.log('3');
            $('#dcustomerid').show();
            $('#tcustomerid').show();
            $('#dcustomerix').show();
            $('#dcompanyid').hide();
            $('#tcompanyid').hide();
            $('#dcompanyix').hide();
            //$('#companyid').combogrid({required:false});
            //$('#customerid').combogrid({required:true});
            //$('#compayid').combogrid('setValue','');
            //$('#companyid').val('');
        }
        else if(type==4) {
            console.log('4');
            $('#dcustomerid').show();
            $('#tcustomerid').show();
            $('#dcustomerix').show();
            $('#dcompanyid').show();
            $('#tcompanyid').show();
            $('#dcompanyix').show();
            //$('#companyid').combogrid({required:true});
            //$('#customerid').combogrid({required:true});
            //$('#companyid').combogrid('setValue','');
            //$('#customerid').combogrid('setValue','');
        }
        else {
            console.log('?');
            $('#dcustomerid').hide();
            $('#tcustomerid').hide();
            //$('#dcustomerix').hide();
            $('#dcompanyid').hide();
            $('#tcompanyid').hide();
            //$('#dcompanyix').hide();
            $('#companyid').combogrid({required:false});
            $('#customerid').combogrid({required:false});
            //$('#companyid').combogrid('setValue','');
            //$('#customerid').combogrid('setValue','');
            //$('#customerid').val('');
            //$('#companyid').val('');
        }
    }
    else
    {
        console.log('empty');
    }
}
$("#formreppackages").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('order/reppackages/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-reppackages').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
    
$('#reppackages_search_packageid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchreppackages();
			}
		}
	})
});

$('#reppackages_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchreppackages();
			}
		}
	})
});

$('#reppackages_search_docno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchreppackages();
			}
		}
	})
});
$('#reppackages_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchreppackages();
			}
		}
	})
});
$('#reppackages_search_packagename').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchreppackages();
			}
		}
	})
});
$('#reppackages_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchreppackages();
			}
		}
	})
});
$('#dg-reppackages').datagrid({
		singleSelect: false,
		toolbar:'#tb-reppackages',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editreppackages(index);
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-packagecompany"></table><table class="ddv-packagecustomer"></table><table class="ddv-packagedetail"></table><table class="ddv-packagedisc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvpackagecompany = $(this).datagrid('getRowDetail',index).find('table.ddv-packagecompany');
			var ddvpackagecustomer = $(this).datagrid('getRowDetail',index).find('table.ddv-packagecustomer');
			var ddvpackagedetail = $(this).datagrid('getRowDetail',index).find('table.ddv-packagedetail');
			var ddvpackagedisc = $(this).datagrid('getRowDetail',index).find('table.ddv-packagedisc');
			ddvpackagecompany.datagrid({
				url:'<?php echo $this->createUrl('reppackages/indexdatacomp',array('grid'=>true)) ?>?id='+row.packageid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'_expander',expander:true,width:24,fixed:true},
					{field:'companycode',title:'<?php echo GetCatalog('companycode') ?>',align:'left',width:'80px'},
					{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',align:'left',width:'180px'},
				]],
			});
            ddvpackagecustomer.datagrid({
				url:'<?php echo $this->createUrl('reppackages/indexdatacust',array('grid'=>true)) ?>?id='+row.packageid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'_expander',expander:true,width:24,fixed:true},
					{field:'fullname',title:'<?php echo GetCatalog('customer') ?>',align:'left',width:'250px'},
					{field:'areaname',title:'<?php echo GetCatalog('salesarea') ?>',align:'left',width:'180px'},
					{field:'groupname',title:'<?php echo GetCatalog('groupcustomer') ?>',width:'100px'},
					{field:'gradedesc',title:'<?php echo GetCatalog('grade') ?>',align:'left',width:'100px'},
				]],
			});
            ddvpackagedetail.datagrid({
				url:'<?php echo $this->createUrl('reppackages/indexdetail',array('grid'=>true)) ?>?id='+row.packageid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'_expander',expander:true,width:24,fixed:true},
					{field:'productname',title:'<?php echo GetCatalog('productname') ?>',width:'350px'},
					{field:'qty',title:'<?php echo GetCatalog('qty') ?>',align:'right',width:'80px'},
					{field:'qtystock',title:'<?php echo GetCatalog('qtystock') ?>',align:'right',width:'80px'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>',width:'100px'},
					{field:'price',title:'<?php echo GetCatalog('price') ?>',align:'right',width:'150px',
          hidden:<?php echo getUserObjectValues('pricepkg')==1 ? 'false' : 'true' ?>},
					{field:'isbonus',title:'<?php echo GetCatalog('isbonus?') ?>',formatter: function(value,row,index){
	                    if (value == 1){
	                    	return "<img src=\"<?php echo Yii::app()->request->baseUrl?>/images/ok.png\"></img>";
	                    } else {
	                    	return '';
	                    }}
                    },
				]],
			});
			ddvpackagedisc.datagrid({
				url:'<?php echo $this->createUrl('reppackages/indexdisc',array('grid'=>true)) ?>?id='+row.packageid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				showFooter:true,
				columns:[[
					{field:'_expander',expander:true,width:24,fixed:true},
          {field:'packagediscid',title:''},
					{field:<?php echo getUserObjectValues('pricepkg')==1 ? "'discvalue'" : "'discvalue1'" ?>,title:'<?php echo GetCatalog('discvalue') ?>',align:'right',width:'100px'},
				]],
				onResize:function(){
						$('#dg-reppackages').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-reppackages').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-reppackages').datagrid('fixDetailRowHeight',index);
		},
        url: '<?php echo $this->createUrl('reppackages/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-reppackages').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'packageid',
		editing: false,
		columns:[[
		{
field:'packageid',
title:'<?php echo GetCatalog('packageid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
		}},
{
field:'docno',
title:'<?php echo GetCatalog('docno') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
						return value;
					}},
/*
{
field:'companyid',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'300px',
formatter: function(value,row,index){
						return row.companyname;
					}},
*/
{
field:'packagename',
title:'<?php echo GetCatalog('packagename') ?>',
sortable: true,
width:'125px',
formatter: function(value,row,index){
						return value;
					}},

{
field:'packagetype',
title:'<?php echo GetCatalog('packagetype') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.packagetypename;
					}},
{
field:'paymentmethodid',
title:'<?php echo GetCatalog('paymentmethod') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.paycode;
					}},
{
field:'startdate',
title:'<?php echo GetCatalog('startdate') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'enddate',
title:'<?php echo GetCatalog('enddate') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
						return value;
					}},
					{
field:'headernote',
title:'<?php echo GetCatalog('headernote') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.headernote;
					}},
{
field:'recordstatus',
title:'<?php echo GetCatalog('recordstatus') ?>',
sortable: true,
formatter: function(value,row,index){
						return row.statusname;
					}},
		]]
});

function searchreppackages(){
	$('#dg-reppackages').edatagrid('load',{
		packageid:$('#reppackages_search_packageid').val(),
		companyname:$('#reppackages_search_companyname').val(),
		docno:$('#reppackages_search_docno').val(),
		customer:$('#reppackages_search_customer').val(),
		packagename:$('#reppackages_search_packagename').val(),
		headernote:$('#reppackages_search_headernote').val(),
	});
};
function getDataPackage($id) {
    jQuery.ajax({
        'url':'<?php echo $this->createUrl('reppackages/getDataPackage')?>',
        'data': {'id':$id},
        'type':'post',
        'dataType':'json',
        'success':function(data) {
            //$('#ixcompany').textbox('setValue',data.companies);
            $('#customerid').val(data.customerid);
            $('#companyid').val(data.companyid);
            if(data.companies != null) {
                $('#companyix').tagbox('setValues',data.companies);
            }
            if(data.customers != null) {
                $('#customerix').tagbox('setValues',data.customers);
            }
            //console.log(data);
        }
    })
}
function approvereppackages() {
	var ss = [];
	var rows = $('#dg-reppackages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('reppackages/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-reppackages').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelreppackages() {
	var ss = [];
	var rows = $('#dg-reppackages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('reppackages/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-reppackages').edatagrid('reload');				
		} ,
		'cache':false});
};

function purgereppackages() {
	var ss = [];
	var rows = $('#dg-reppackages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('reppackages/purge') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-reppackages').edatagrid('reload');				
		} ,
		'cache':false});
};

function downpdfreppackages() {
	var ss = [];
	var rows = $('#dg-reppackages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	window.open('<?php echo $this->createUrl('reppackages/downpdf') ?>?id='+ss);
};

function downxlsreppackages() {
	var ss = [];
	var rows = $('#dg-reppackages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	window.open('<?php echo $this->createUrl('reppackages/downxls') ?>?id='+ss);
}

function addreppackages() {
		$('#dlg-reppackages').dialog('open');
		$('#ff-reppackages-modif').form('clear');
		$('#ff-reppackages-modif').form('load','<?php echo $this->createUrl('reppackages/GetData') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editreppackages($i) {
	var row = $('#dg-reppackages').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('apppkg') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-reppackages').dialog('open');
			$('#ff-reppackages-modif').form('load',row);
            gettype();
            let pkgid=row.packageid;
            getDataPackage(pkgid);
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormreppackages(){
	$('#ff-reppackages-modif').form('submit',{
		url:'<?php echo $this->createUrl('reppackages/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-reppackages').datagrid('reload');
        $('#dlg-reppackages').dialog('close');
			}
    }
	});	
};

function clearFormreppackages(){
		$('#ff-reppackages-modif').form('clear');
};

function cancelFormreppackages(){
		$('#dlg-reppackages').dialog('close');
};

$('#ff-reppackages-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-packagedetail').datagrid({
				queryParams: {
					id: $('#packageid').val()
				}
		});
		$('#dg-packagedisc').datagrid({
			queryParams: {
				id: $('#packageid').val()
			}
		});
        $('.header-hide-company').hide();
        $('.header-hide-customer').hide();
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

$('#dg-packagedetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'packagedetailid',
	editing: true,
	toolbar:'#tb-packagedetail',
	fitColumn: true,
	pagination:true,
	showFooter:true,
	url: '<?php echo $this->createUrl('reppackages/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('reppackages/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('reppackages/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('reppackages/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-packagedetail').edatagrid('reload');
		$('#dg-packagedisc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},	
	onBeforeEdit:function(index,row) {
		row.packageid = $('#packageid').val();
	},
	columns:[[
	{
		field:'packageid',
		title:'<?php echo GetCatalog('packageid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'packagedetailid',
		title:'<?php echo GetCatalog('packagedetailid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productid',
		title:'<?php echo GetCatalog('product') ?>',
		width:'400px',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'900px',
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
						queryParams:{
							trxplant:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
                        onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
                                //let sotype = $("#sotype").combobox('getValue');
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $("#dg-packagedetail").datagrid("getEditor", {index: index, field:"productid"});
								var uomid = $("#dg-packagedetail").datagrid("getEditor", {index: index, field:"unitofmeasureid"});
                var price = $("#dg-packagedetail").datagrid("getEditor", {index: index, field:"price"});
                                
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdatasales') ?>',
									//'data':{'productid':$(productid.target).combogrid("getValue"),'companyid':1},
									'data':{'productid':$(productid.target).combogrid("getValue"),
                                           'companyid':'<?php echo getuserobjectvalues('company')?>'},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uomid);
									} ,
									'cache':false});

                  jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productsales/generatedata') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue"),
                          'package' : 1
                    },
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(price.target).numberbox('setValue',data.price);
									},
									'cache':false});

                }
						},
						columns:[[
							{field:'productid',title:'<?php echo GetCatalog('productid')?>'},
							{field:'productname',title:'<?php echo GetCatalog('productname')?>'},
							{field:'barcode',title:'<?php echo GetCatalog('barcode')?>'},
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
				required:true,
				decimalSeparator:',',
				groupSeparator:'.'
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
							{field:'unitofmeasureid',title:'<?php echo GetCatalog('unitofmeasureid')?>',width:'50px'},
							{field:'uomcode',title:'<?php echo GetCatalog('uomcode')?>',width:'200px'},
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
		field:'price',
		title:'<?php echo GetCatalog('price') ?>',
		sortable: true,
		editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		width:'150px',
		required:true,
		formatter: function(value,row,index){
								return value;
		}
	},
    {
	field:'isbonus',
	title:'<?php echo getCatalog('isbonus?')?>',
	width:'80px',
	align:'center',
	editor:{type:'checkbox',options:{on:'1',off:'0'}},
	sortable: true,
	formatter: function(value,row,index){
		if (value == 1){
			return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
		} else {
			return '';
		}
	}
}
	]]
});

$('#dg-packagedisc').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'packagediscid',
	editing: true,
	toolbar:'#tb-packagedisc',
	fitColumn: true,
	pagination:true,
	showFooter:true,
	url: '<?php echo $this->createUrl('reppackages/searchdisc',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('reppackages/savedisc',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('reppackages/savedisc',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('reppackages/purgedisc',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-packagedisc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-packagedisc').edatagrid('getSelected');
		if (row)
		{
			row.packageid = $('#packageid').val()
		}
	},
	onBeforeSave: function(index){
		var row = $('#dg-packagedisc').edatagrid('getSelected');
		if (row)
		{
			row.packageid = $('#packageid').val()
		}
	},
	columns:[[
	{
		field:'packageid',
		title:'<?php echo GetCatalog('packageid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'packagediscid',
		title:'<?php echo GetCatalog('packagediscid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'discvalue',
		title:'<?php echo GetCatalog('discvalue') ?>',
		editor:{
			type:'numberbox',
			options:{
				precision:6,
				required:true,
				decimalSeparator:',',
				groupSeparator:'.'
			}
		},
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	}
	]]
});
</script>