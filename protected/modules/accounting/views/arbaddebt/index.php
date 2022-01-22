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
<table id="dg-arbaddebt"  style="width:1200px;height:97%"></table>
<div id="tb-arbaddebt">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addarbaddebt()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editarbaddebt()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvearbaddebt()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelarbaddebt()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfarbaddebt()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formarbaddebt" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="Filearbaddebt" id="Filearbaddebt" style="display:inline">
			<input type="submit" value="Upload" name="submitarbaddebt" style="display:inline">
		</form>
	<?php }?>
<table>
	<tr>
		<td><?php echo GetCatalog('arbaddebtid')?></td>
		<td><input class="easyui-textbox" id="arbaddebt_search_arbaddebtid" style="width:150px"></td>
		<td><?php echo GetCatalog('companyname')?></td>
		<td><input class="easyui-textbox" id="arbaddebt_search_companyname" style="width:150px"></td>
		<td><?php echo GetCatalog('docno')?></td>
		<td><input class="easyui-textbox" id="arbaddebt_search_docno" style="width:150px"></td>
	</tr>
	<!-- <tr>
		<td><?php echo GetCatalog('customer')?></td>
		<td><input class="easyui-textbox" id="arbaddebt_search_customer" style="width:150px"></td>
		<td><?php echo GetCatalog('pocustno')?></td>
		<td><input class="easyui-textbox" id="arbaddebt_search_pocustno" style="width:150px"></td>
		<td><?php echo GetCatalog('poplant')?></td>
		<td><input class="easyui-textbox" id="arbaddebt_search_poplant" style="width:150px"></td>
		<td><?php echo GetCatalog('headernote')?></td>
		<td><input class="easyui-textbox" id="arbaddebt_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searcharbaddebt()"></a></td>
	</tr> -->
</table>
</div>

<div id="dlg-arbaddebt" class="easyui-dialog" title="Piutang Bad Debt" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormarbaddebt();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-arbaddebt').dialog('close');
			}
		},
	]	
	">
	<form id="ff-arbaddebt-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="arbaddebtid" id="arbaddebtid"></input>
  <input type="hidden" name="detailcustomerid" id="detailcustomerid"></input>
		<table cellpadding="5">
		<tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options=
				"formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: '500px',
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								method: 'get',
								url:'<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
      <tr>
      <td><?php echo GetCatalog('plant')?></td>
      <td><select class="easyui-combogrid" id="plantid" name="plantid" style="width:250px" data-options="
              panelWidth: '500px',
              required: true,
              idField: 'plantid',
              textField: 'plantcode',
              pagination:true,
              mode:'remote',
              method: 'get',
              url:'<?php echo Yii::app()->createUrl('common/plant/index',array('grid'=>true)) ?>',
              queryParams:{
							  trxcom:true
						  },
              onBeforeLoad: function(param) {
                  param.companyid = $('#companyid').combogrid('getValue');
						  },
              columns: [[
                  {field:'plantid',title:'<?php echo GetCatalog('plantid') ?>'},
                  {field:'plantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
              ]],
              fitColumns: true
          ">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:800px">
		<div title="Detail" style="padding:5px">
			<table id="dg-arbaddebtdetail"  style="width:100%;height:400px">
			</table>
			<div id="tb-arbaddebtdetail">
				<a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-arbaddebtdetail').edatagrid('addRow')" id="addarbaddebtdetail"></a>
				<a href="javascript:void(0)" title="Simpan" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-arbaddebtdetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-arbaddebtdetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="hapus" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-arbaddebtdetail').edatagrid('destroyRow')" id="delarbaddebtdetail"></a>
			</div>
			<table id="dg-arbaddebtacc"  style="width:100%;height:400px">
			</table>
			<div id="tb-arbaddebtacc">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-arbaddebtacc').edatagrid('addRow')" id="addarbaddebtacc"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-arbaddebtacc').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-arbaddebtacc').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-arbaddebtacc').edatagrid('destroyRow')" id="delarbaddebtacc"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
   
$('#approvearbaddebt').click(function(){
    tampil_loading();
});

function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css("visibility", "hidden");
}

$("#formarbaddebt").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('order/arbaddebt/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-arbaddebt').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
    
$('#arbaddebt_search_arbaddebtid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searcharbaddebt();
			}
		}
	})
});
$('#arbaddebt_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searcharbaddebt();
			}
		}
	})
});
$('#arbaddebt_search_docno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searcharbaddebt();
			}
		}
	})
});
$('#arbaddebt_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searcharbaddebt();
			}
		}
	})
});
$('#dg-arbaddebt').datagrid({
		singleSelect: false,
		toolbar:'#tb-arbaddebt',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editarbaddebt(index);
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-arbaddebtdetail"></table><table class="ddv-arbaddebtacc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvarbaddebtdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-arbaddebtdetail');
			var ddvarbaddebtacc = $(this).datagrid('getRowDetail',index).find('table.ddv-arbaddebtacc');
      var packageid = row.packageid;
      console.log(packageid);
			ddvarbaddebtdetail.datagrid({
				url:'<?php echo $this->createUrl('arbaddebt/indexdetail',array('grid'=>true)) ?>?id='+row.arbaddebtid,
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
					{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>',width:'250px'},
					{field:'invoicedate',title:'<?php echo GetCatalog('invoicedate') ?>',align:'right',width:'80px'},
					{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno') ?>',width:'150px'},
					{field:'paycode',title:'<?php echo GetCatalog('paycode') ?>',width:'120px'},
					{field:'saldo',title:'<?php echo GetCatalog('saldo') ?>',align:'right',width:'120px'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>',align:'right',width:'120px'},
					{field:'payamount',title:'<?php echo GetCatalog('payamount') ?>',align:'right',width:'120px'},
				]],
			});
			ddvarbaddebtacc.datagrid({
				url:'<?php echo $this->createUrl('arbaddebt/indexacc',array('grid'=>true)) ?>?id='+row.arbaddebtid,
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
          {field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',width:'350px'},
          {field:'employeename',title:'<?php echo GetCatalog('employeename') ?>',width:'350px'},
					{field:'debit',title:'<?php echo GetCatalog('debit') ?>',width:'100px'},
					{field:'credit',title:'<?php echo GetCatalog('credit') ?>',width:'120px'},
					{field:'currencyname',title:'<?php echo GetCatalog('currencyname') ?>',width:'120px'},
					{field:'ratevalue',title:'<?php echo GetCatalog('ratevalue') ?>',width:'120px'},
					,
				]],
				onResize:function(){
						$('#dg-arbaddebt').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-arbaddebt').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-arbaddebt').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('arbaddebt/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-arbaddebt').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		rowStyler:function(index,row){
      if (row.debit != row.credit){
			  return 'background-color:blue;color:#fff;';
		  }
      else {
        return 'background-color:white;color:black;font-weight:bold;';
			}
    },
		idField:'arbaddebtid',
		editing: false,
		columns:[[
      {
        field:'arbaddebtid',
        title:'<?php echo GetCatalog('arbaddebtid') ?>',
        sortable: true,
        width:'50px',
        formatter: function(value,row,index){
            return value;
        }
      },
      {
        field:'docno',
        title:'<?php echo GetCatalog('docno') ?>',
        sortable: true,
        width:'100px',
        formatter: function(value,row,index){
          return value;
        }
      },
      {
        field:'docdate',
        title:'<?php echo GetCatalog('docdate') ?>',
        sortable: true,
        width:'80px',
        formatter: function(value,row,index){
          return value;
        }
      },
      {
        field:'companyid',
        title:'<?php echo GetCatalog('company') ?>',
        sortable: true,
        width:'300px',
        formatter: function(value,row,index){
          return row.companyname;
        }
      },
      {
        field:'plantid',
        title:'<?php echo GetCatalog('plant') ?>',
        sortable: true,
        width:'125px',
        formatter: function(value,row,index){
          return row.plantcode;
        }
      },
      {
        field:'headernote',
        title:'<?php echo GetCatalog('headernote') ?>',
        sortable: true,
        width:'250px',
        formatter: function(value,row,index){
          return row.headernote;
        }
      },
      {
        field:'recordstatusarbaddebt',
        title:'<?php echo GetCatalog('recordstatus') ?>',
        sortable: true,
        formatter: function(value,row,index){
          return value;
        }
      },
    ]]
});

function searcharbaddebt(){
	$('#dg-arbaddebt').edatagrid('load',{
		arbaddebtid:$('#arbaddebt_search_arbaddebtid').val(),
		companyname:$('#arbaddebt_search_companyname').val(),
		docno:$('#arbaddebt_search_docno').val(),
		headernote:$('#arbaddebt_search_headernote').val(),
	});
};
/*
function approvearbaddebt() {
	var ss = [];
	var rows = $('#dg-arbaddebt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.arbaddebtid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('arbaddebt/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-arbaddebt').edatagrid('reload');				
		} ,
		'cache':false});
};
*/
function approvearbaddebt() {
         $('.ajax-loader').css('visibility', 'visible');
	var ss = [];
	var rows = $('#dg-arbaddebt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.arbaddebtid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('arbaddebt/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		statusCode: {
      200: function(data) {
        $('#dg-arbaddebt').edatagrid('reload');  
      }
    },
    'success':function(data)
		{
			tutup_loading();
      show('Message',data.pesan);
			$('#dg-arbaddebt').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelarbaddebt() {
	var ss = [];
	var rows = $('#dg-arbaddebt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.arbaddebtid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('arbaddebt/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-arbaddebt').edatagrid('reload');				
		} ,
		'cache':false});
};

// function purgearbaddebt() {
// 	var ss = [];
// 	var rows = $('#dg-arbaddebt').edatagrid('getSelections');
// 	for(var i=0; i<rows.length; i++){
// 			var row = rows[i];
// 			ss.push(row.arbaddebtid);
// 	}
// 	jQuery.ajax({'url':'<?php echo $this->createUrl('arbaddebt/purge') ?>',
// 		'data':{'id':ss},'type':'post','dataType':'json',
// 		'success':function(data)
// 		{
// 			show('Message',data.msg);
// 			$('#dg-arbaddebt').edatagrid('reload');				
// 		} ,
// 		'cache':false});
// };

function downpdfarbaddebt() {
	var ss = [];
	var rows = $('#dg-arbaddebt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.arbaddebtid);
	}
	window.open('<?php echo $this->createUrl('arbaddebt/downpdf') ?>?id='+ss);
};

function downxlsarbaddebt() {
	var ss = [];
	var rows = $('#dg-arbaddebt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.arbaddebtid);
	}
	window.open('<?php echo $this->createUrl('arbaddebt/downxls') ?>?id='+ss);
}

function addarbaddebt() {
		$('#dlg-arbaddebt').dialog('open');
		$('#ff-arbaddebt-modif').form('clear');
		$('#ff-arbaddebt-modif').form('load','<?php echo $this->createUrl('arbaddebt/GetData') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editarbaddebt($i) {
	var row = $('#dg-arbaddebt').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('apparbaddebt') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-arbaddebt').dialog('open');
			$('#ff-arbaddebt-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormarbaddebt(){
	$('#ff-arbaddebt-modif').form('submit',{
		url:'<?php echo $this->createUrl('arbaddebt/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-arbaddebt').datagrid('reload');
        $('#dlg-arbaddebt').dialog('close');
			}
      tutup_loading();
    }
	});	
};

function clearFormarbaddebt(){
  $('#ff-arbaddebt-modif').form('clear');
};

function cancelFormarbaddebt(){
  $('#dlg-arbaddebt').dialog('close');
  tutup_loading();
};

$('#ff-arbaddebt-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-arbaddebtdetail').datagrid({
				queryParams: {
					id: $('#arbaddebtid').val()
				}
		});
		$('#dg-arbaddebtacc').datagrid({
			queryParams: {
				id: $('#arbaddebtid').val()
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

$('#dg-arbaddebtdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'arbaddebtdetid',
	editing: true,
	toolbar:'#tb-arbaddebtdetail',
	fitColumn: true,
	pagination:true,
	showFooter:true,
	url: '<?php echo $this->createUrl('arbaddebt/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('arbaddebt/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('arbaddebt/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('arbaddebt/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-arbaddebtdetail').edatagrid('reload');
		$('#dg-arbaddebtacc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},	
	onBeforeEdit:function(index,row) {
		row.arbaddebtid = $('#arbaddebtid').val();
    //console.log(row);
    $('#detailcustomerid').val(row.addressbookid);
	},
	columns:[[
	{
		field:'arbaddebtid',
		title:'<?php echo GetCatalog('arbaddebtid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'arbaddebtdetid',
		title:'<?php echo GetCatalog('arbaddebtdetid') ?>',
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'addressbookid',
		title:'<?php echo GetCatalog('fullname') ?>',
		width:'400px',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'addressbookid',
						textField:'fullname',
						url:'<?php echo Yii::app()->createUrl('common/customer/index',array('grid'=>true,'company'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
            onBeforeLoad: function(param) {
              param.companyid = $('#companyid').combogrid('getValue');
            },
						onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								let tr = $(this).closest('tr.datagrid-row');
								let index = parseInt(tr.attr('datagrid-row-index'));
								let addressbookid = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"addressbookid"});
								let invoiceid = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"invoiceid"});
								let amount = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"amount"});
								let payamount = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"payamount"});

                $(invoiceid.target).combogrid('setValue','');
                $(amount.target).numberbox('setValue','');
                $(payamount.target).numberbox('setValue','');
						  }
            },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid')?>'},
							{field:'fullname',title:'<?php echo GetCatalog('fullname')?>'},
              {field:'currentlimit',title:'<?php echo GetCatalog('currentarlimit') ?>'},
              {field:'creditlimit',title:'<?php echo GetCatalog('creditlimit') ?>'},
						]],
            onSelect: function(index,row) {
              console.log(row);
              $('#detailcustomerid').val(row.addressbookid);
            }
				},
			},
		sortable: true,
		formatter: function(value,row,index){
      return row.fullname;
		}
	},
	{
		field:'invoiceid',
		title:'<?php echo GetCatalog('invoice') ?>',
		width:'150px',
		editor:{
      type:'combogrid',
      options:{
        panelWidth:450,
        mode : 'remote',
        method:'get',
        idField:'invoiceid',
        textField:'invoiceno',
        url:'<?php echo Yii::app()->createUrl('accounting/invoicear/index',array('grid'=>true)) ?>',
        fitColumns:true,
        pagination:true,
        queryParams:{
          baddebt:true
        },
        onBeforeLoad: function(param) {
          param.companyid = $('#companyid').combogrid('getValue');
          param.addressbookid = $('#detailcustomerid').val();
        },
        onChange:function(newValue,oldValue) {
          if ((newValue !== oldValue) && (newValue !== ''))
          {
            let tr = $(this).closest('tr.datagrid-row');
            let index = parseInt(tr.attr('datagrid-row-index'));
            let addressbookid = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"addressbookid"});
            var invoiceid = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"invoiceid"});
            var saldo = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"saldo"});
            var amount = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"amount"});
            var payamount = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"payamount"});
            var invoicedate = $("#dg-arbaddebtdetail").datagrid("getEditor", {index: index, field:"invoicedate"});
                            
            jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('accounting/arbaddebt/getinvoice') ?>',
              'data':{'invoiceid':$(invoiceid.target).combogrid("getValue")},
              'type':'post','dataType':'json',
              'success':function(data)
              {
                $(amount.target).numberbox('setValue',data.amount);
                $(payamount.target).numberbox('setValue',data.payamount);
                $(invoicedate.target).datebox('setValue',data.invoicedate);
              },
              'cache':false});
          }
        },
        loadMsg: '<?php echo GetCatalog('pleasewait')?>',
        columns:[[
          {field:'invoiceid',title:'<?php echo GetCatalog('invoiceid')?>'},
          {field:'invoiceno',title:'<?php echo GetCatalog('invoiceno')?>'},
          {field:'fullname',title:'<?php echo GetCatalog('customer')?>'},
          {field:'saldo',title:'<?php echo GetCatalog('saldo')?>'},
          {field:'amount',title:'<?php echo GetCatalog('amount')?>'},
          {field:'payamount',title:'<?php echo GetCatalog('payamount')?>'},
        ]]
      }	
    },
    required:true,
		sortable: true,
		formatter: function(value,row,index){
        return row.invoiceno;
		}
	},
  {
		field:'invoicedate',
		title:'<?php echo getCatalog('invoicedate') ?>',
		editor: {
			type: 'datebox',
			options:{
			formatter:dateformatter,
			required:true,
			parser:dateparser
			}
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
      return value;
		}
	},
  {
    field:'saldo',
    title:'<?php echo GetCatalog('saldo') ?>',
    sortable: true,
    editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
        readonly:true,
			}
		},
    width:'120px',
    formatter: function(value,row,index){
      return '<div style="text-align:right">'+value+'</div>';
  }},
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
        readonly:true,
			}
		},
    width:'120px',
    formatter: function(value,row,index){
      return '<div style="text-align:right">'+value+'</div>';
  }},
  {
    field:'payamount',
    title:'<?php echo GetCatalog('payamount') ?>',
    sortable: true,
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
    width:'120px',
    formatter: function(value,row,index){
      return '<div style="text-align:right">'+value+'</div>';
  }},
	]]
});

$('#dg-arbaddebtacc').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'arbaddebtaccid',
	editing: true,
	toolbar:'#tb-arbaddebtacc',
	fitColumn: true,
	pagination:true,
	showFooter:true,
	url: '<?php echo $this->createUrl('arbaddebt/searchacc',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('arbaddebt/saveacc',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('arbaddebt/saveacc',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('arbaddebt/purgeacc',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-arbaddebtacc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-arbaddebtacc').edatagrid('getSelected');
		if (row)
		{
			row.arbaddebtid = $('#arbaddebtid').val()
		}
	},
	columns:[[
	{
		field:'arbaddebtid',
		title:'<?php echo GetCatalog('arbaddebtid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'arbaddebtaccid',
		title:'<?php echo GetCatalog('arbaddebtaccid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
    field:'accountid',
    title:'<?=GetCatalog('accountname')?>',
    editor:{
      type:'combogrid',
      options:{
        panelWidth:'800px',
        mode : 'remote',
        method:'get',
        idField:'accountid',
        textField:'accountname',
        url:'<?=$this->createUrl('account/index',array('grid'=>true)) ?>',
        fitColumns:true,
        pagination:true,
        required:true,
        queryParams:{
          trxcom:true
        },
        onBeforeLoad: function(param) {
            param.companyid = $('#companyid').combogrid('getValue');
        },
        loadMsg: '<?=GetCatalog('pleasewait')?>',
        columns:[[
          {field:'accountid',title:'<?=GetCatalog('accountid')?>',width:'80px'},
          {field:'companyname',title:'<?=GetCatalog('company')?>',width:'350px'},
          {field:'accountcode',title:'<?=GetCatalog('accountcode')?>',width:'120px'},
          {field:'accountname',title:'<?=GetCatalog('accountname')?>',width:'250px'},
        ]]
      }	
    },
    width:'300px',
    sortable: true,
    formatter: function(value,row,index){
      return row.accountname;
    }
  },
  {
		field:'employeeid',
		title:'<?= GetCatalog('employee') ?>',
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
          {field:'structurename',title:'<?php echo GetCatalog('structurename')?>'},
        ]]
      }	
    },
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
        return row.employeename;
		}
	},
  {
    field:'debit',
    title:'<?=GetCatalog('debit') ?>',
    width:'100px',
    editor: {
      type: 'numberbox',
      options:{
        precision:4,
        decimalSeparator:',',
        groupSeparator:'.',
        value:0,
        required:true,
      }
    },
    sortable: true,
    formatter: function(value,row,index){
      return value;
    }
  },
        {
    field:'credit',
    title:'<?=GetCatalog('credit') ?>',
    width:'100px',
    editor: {
      type: 'numberbox',
      options:{
        precision:4,
        decimalSeparator:',',
        groupSeparator:'.',
        value:0,
        readonly:true,
      }
    },
    sortable: true,
    formatter: function(value,row,index){
      return value;
    }
  },
  {
    field:'currencyid',
    title:'<?=GetCatalog('currency') ?>',
    editor:{
        type:'combogrid',
        options:{
          panelWidth:'500px',
          mode : 'remote',
          method:'get',
          idField:'currencyid',
          textField:'currencyname',
          url:'<?=Yii::app()->createUrl('admin/currency/index',array('grid'=>true))?>',
          fitColumns:true,
          pagination:true,
          required:true,
          queryParams:{
            combo:true
          },
          loadMsg: '<?=GetCatalog('pleasewait')?>',
          columns:[[
            {field:'currencyid',title:'<?=GetCatalog('currencyid')?>',width:'50px'},
            {field:'currencyname',title:'<?=GetCatalog('currencyname')?>',width:'150px'},
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
    field:'ratevalue',
    title:'<?=GetCatalog('ratevalue') ?>',
    editor:{
      type:'numberbox',
      options:{
        precision:4,
        decimalSeparator:',',
        groupSeparator:'.',
        required:true,
        value:1,
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
