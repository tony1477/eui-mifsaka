<table id="dg-packages"  style="width:1200px;height:97%"></table>
<div id="tb-packages">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addpackages()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editpackages()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvepackages()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelpackages()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Purge"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="purgepackages()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfpackages()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formpackages" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="Filepackages" id="Filepackages" style="display:inline">
			<input type="submit" value="Upload" name="submitpackages" style="display:inline">
		</form>
	<?php }?>
<table>
	<tr>
		<td><?php echo GetCatalog('packageid')?></td>
		<td><input class="easyui-textbox" id="packages_search_packageid" style="width:50px"></td>
		<td><?php echo GetCatalog('companyname')?></td>
		<td><input class="easyui-textbox" id="packages_search_companyname" style="width:150px"></td>
		<td><?php echo GetCatalog('docno')?></td>
		<td><input class="easyui-textbox" id="packages_search_docno" style="width:150px"></td>
	</tr>
	<tr>
		<td><?php echo GetCatalog('customer')?></td>
		<td><input class="easyui-textbox" id="packages_search_customer" style="width:150px"></td>
		<td><?php echo GetCatalog('packagename')?></td>
		<td><input class="easyui-textbox" id="packages_search_packagename" style="width:150px"></td>
		<td><?php echo GetCatalog('headernote')?></td>
		<td><input class="easyui-textbox" id="packages_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchpackages()"></a></td>
	</tr>
</table>
</div>

<div id="dlg-packages" class="easyui-dialog" title="Sales Order" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormpackages();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-packages').dialog('close');
			}
		},
	]	
	">
	<form id="ff-packages-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="packageid" id="packageid"></input>
	<input type="hidden" name="type" id="type" value="0"></input>
		<table cellpadding="5" border="1">
            <tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options=
				"formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
            <tr>
				<td><?php echo GetCatalog('packagename')?></td>
				<td><input class="easyui-textbox" name="packagename" data-options="required:true,multiline:true" style="width:250px; height:100px"></input></td>
			</tr>
            <tr>
				<td><?php echo GetCatalog('packagetype')?></td>
                <td><select id="packagetype" class="easyui-combogrid" name="packagetype" style="width:250px;"
                    data-options="
                    panelWidth:450,
                    idField:'no',
                    textField:'type',
                    mode:'remote',
                    method:'get',
                    'pagination':true,
                    url:'<?php echo Yii::app()->createUrl('order/packages/packagetype') ?>',
                    columns:[[
                        {field:'no',title:'<?php echo getCatalog('no')?>',width:15},
                        {field:'type',title:'<?php echo getCatalog('packagetype')?>',width:100},
                    ]],
                    onSelect: function(index,row){
                        $('input[name=type]').val(row.no);
                        gettype();
				    }
                    "></select></td>
			</tr>
			<tr>
				<td style="display:none" id="tcompanyid"><?php echo GetCatalog('company')?></td>
				<td>
                    <div id="dcompanyid" style="display:none">
                    <select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px; display:none" 
                    data-options="
								panelWidth: '500px',
								required: false,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
                                multiple:true,
								method: 'get',
                                selectOnCheck: true,
                                checkOnSelect: true,
                                onCheck: function(index,row){
                                    row.final = true;
                                    console.log('test');
                                },
                                onUncheck: function(index,row){
                                    row.final = false;
                                    console.log('untest');
                                },
								url:'<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
                                        {field:'final',checkbox:true},
								]],
								fitColumns: true,
                                onSelect: function(index,row) {
                                    setvaluecompany(row.companyid,row.companyname);
                                }
						">
				</select></td>
            </tr>
            <tr>
            <td></td>
            <td>
                <input type="hidden" name="ixcompanyid" id="ixcompanyid" />
                <table width="350" border="1">
                <thead class="header-hide-company"> 
			  	<tr> 
                    <th class="text-center"><?= getCatalog('company')?></th> 
                    <th class="text-center"><?= getCatalog('Aksi')?></th> 
			  	</tr>
				</thead>
                    <tbody id="tbody-cp">
                    
                    </tbody>
                </table>
            </td>
            </tr>
            </div>
			<tr>
				<td style="display:none" id="tcustomerid"><?php echo GetCatalog('customer')?></td>
				<td>
                    <div id="dcustomerid" style="display:none">
                    <select class="easyui-combogrid" id="customerid" name="customerid" style="width:250px; display:none;" data-options="
								panelWidth: '500px',
								idField: 'addressbookid',
								textField: 'fullname',
								pagination:true,
                                required: false,
								mode:'remote',
                                multiple:false,
								method: 'get',
								url:'<?php echo Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ?>',
								columns: [[
										{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
										{field:'currentlimit',title:'<?php echo GetCatalog('currentarlimit') ?>'},
										{field:'creditlimit',title:'<?php echo GetCatalog('creditlimit') ?>'},
										{field:'overdue',title:'<?php echo GetCatalog('overdue') ?>'},
								]],
								fitColumns: true,
                                onSelect: function(index,row) {
                                    setvaluecustomer(row.customerid,row.fullname);
                                    console.log(row.fullname);
                                }
						">
				</select></td>
            </tr>
            <tr>
            <td>IX CUSTOMER</td>
            <td>
                <td><input class="easyui-textbox" id="ixcustomerid" name="ixcustomerid" data-options="multiline:true" style="width:300px;height:100px"></input></td>
            </td>
            </tr>
            </div>
            <tr>
				<td><?php echo GetCatalog('startdate')?></td>
				<td><input class="easyui-datebox" type="text" id="startdate" name="startdate" data-options=
				"formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
            <tr>
				<td><?php echo GetCatalog('enddate')?></td>
				<td><input class="easyui-datebox" type="text" id="enddate" name="enddate" data-options=
				"formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:800px">
		<div title="Detail" style="padding:5px">
			<table id="dg-packagedetail"  style="width:100%;height:400px">
			</table>
			<div id="tb-packagedetail">
				<a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-packagedetail').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-packagedetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-packagedetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="hapus" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-packagedetail').edatagrid('destroyRow')"></a>
			</div>
			<table id="dg-packagedisc"  style="width:100%;height:400px">
			</table>
			<div id="tb-packagedisc">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-packagedisc').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-packagedisc').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-packagedisc').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-packagedisc').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    
    var rowIdxcp = 0; 
    var rowIdxcs = 0; 
    
    $('#tbody-cp').on('click', '.remove', function () {
  
        // Getting all the rows next to the row
        // containing the clicked button 
        let child = $(this).closest('tr').nextAll();
  
        // Iterating across all the rows  
        // obtained to change the index 
        child.each(function () { 
  
          // Getting <tr> id. 
          let id = $(this).attr('id'); 
  
          // Getting the <p> inside the .row-index class. 
          let idx = $(this).children('.row-index').children('p'); 
  
          // Gets the row number from <tr> id. 
          let dig = parseInt(id.substring(1)); 
  
          let val = $(this).children('.row-index').text();
          // Modifying row index. 
          console.log(val);
          idx.html(`${val}`); 
		  
  
          // Modifying row id. 
          $(this).attr('id', `R${dig - 1}`); 
        }); 
  
        // Removing the current row. 
        $(this).closest('tr').remove(); 
  
        // Decreasing total number of rows by 1. 
        rowIdxcp--; 
      });
    
	$('#tbody-cs').on('click', '.remove', function () {
  
        // Getting all the rows next to the row
        // containing the clicked button 
        var child = $(this).closest('tr').nextAll();
  
        // Iterating across all the rows  
        // obtained to change the index 
        child.each(function () { 
  
          // Getting <tr> id. 
          let id = $(this).attr('id'); 
  
          // Getting the <p> inside the .row-index class. 
          let idx = $(this).children('.row-index').children('p'); 
  
          // Gets the row number from <tr> id. 
          let dig = parseInt(id.substring(1)); 
  
          let val = $(this).children('.row-index').text();
          // Modifying row index. 
            console.log(val);
          idx.html(`${val}`); 
  
          // Modifying row id. 
          $(this).attr('id', `R${dig - 1}`); 
        }); 
  
        // Removing the current row. 
        $(this).closest('tr').remove(); 
  
        // Decreasing total number of rows by 1. 
        rowIdxcs--; 
      });
	
    $('#cc').combobox({
  formatter:function(row){
    var opts = $(this).combobox('options');
    return '<input type="checkbox" class="combobox-checkbox">' + row[opts.textField]
  },
  onLoadSuccess:function(){
    var opts = $(this).combobox('options');
    var target = this;
    var values = $(target).combobox('getValues');
    $.map(values, function(value){
      var el = opts.finder.getEl(target, value);
      el.find('input.combobox-checkbox')._propAttr('checked', true);
    })
  },
  onSelect:function(row){
    console.log(row)
    var opts = $(this).combobox('options');
    var el = opts.finder.getEl(this, row[opts.valueField]);
    el.find('input.combobox-checkbox')._propAttr('checked', true);
  },
  onUnselect:function(row){
    var opts = $(this).combobox('options');
    var el = opts.finder.getEl(this, row[opts.valueField]);
    el.find('input.combobox-checkbox')._propAttr('checked', false);
  }
});
    
/*
$(document).ready(function(){
    let type = $('#type').val();
    if(type!='' && type>0 ) {
        cons
    }
        $('input[name=\"isover\"]').on('click',function () {
            if (ckbox.is(':checked')) {
                $(\"#ctover\").show();
                var oldct = $('#oldctover').val();
                $('input[name=\"ctover\"]').val(oldct);
            } else {
                //$('input[name=\"ctover\"]').attr({value:\"0\"});
                $(\"#ctover\").hide();
                $('input[name=\"ctover\"]').val('0');
            }
        });
        
        $('#ctover').change(function(value,row){
                var sk = $('input[name=\"ctover\"]').val();
                console.log('change to ' + sk );
            });
});
*/
function setvaluecompany(index,val) {
    console.log(val);
	let next = false;
    //let nilai = $('#vcompanyid').textbox('getValue');
    let ix = $('#ixcompanyid').val();
    if(ix == '') {
        //val = nilai+','+'\n'+val;
        $('#ixcompanyid').val(index);
		next=true;
		
    }
	console.log(ix);
	console.log(index);
    //$('#vcompanyid').textbox('setValue',val);
	let n = ix.indexOf(index);
	console.log(n);
	if(n==-1 && next==false) {
		index = ix+','+index;
		$('#ixcompanyid').val(index);
		next = true;
	}
    
    //$('#info').html(val);
    
     // Denotes total number of rows 
  
      // jQuery button click event to add a row 
      //$('#addBtn').on('click', function () { 
  
        // Adding a row inside the tbody. 
		console.log(next);
        $('.header-hide-company').show();
		if(next==true) {
        $('#tbody-cp').append(`<tr id="R${++rowIdxcp}"> 
             <td class="row-index text-left"> 
             <p>${val}</p> 
             </td> 
              <td > 
                <a href="#" class="remove l-btn l-btn-small l-btn-plain" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Hapus</span><span class="l-btn-icon icon-purge">&nbsp;</span></span></a>
                </td> 
              </tr>`);
		}
      //}); 
  
      // jQuery button click event to remove a row. 
}
    
function setvaluecustomer(index,val) {
    console.log(val);
    //let nilai = $('#vcompanyid').textbox('getValue');
    let ix = $('#ixcustomer').val();
    if(ix != '') {
        //val = nilai+','+'\n'+val;
        index = ix+','+index;
		$('#ixcustomer').val(index);
    }
    //$('#vcompanyid').textbox('setValue',val);
    $('#ixcustomer').val(index);
    //$('#info').html(val);
    
     // Denotes total number of rows 
  
      // jQuery button click event to add a row 
      //$('#addBtn').on('click', function () { 
  
        // Adding a row inside the tbody. 
        $('.header-hide-customer').show();
        $('#tbody-cs').append(`<tr id="R${++rowIdxcs}"> 
             <td class="row-index text-left"> 
             <p>${val}</p> 
             </td> 
              <td > 
                <a href="#" class="remove l-btn l-btn-small l-btn-plain" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Hapus</span><span class="l-btn-icon icon-purge">&nbsp;</span></span></a>
                </td> 
              </tr>`); 
      //}); 
  
      // jQuery button click event to remove a row. 
}

function gettype() {
    let type = $('#type').val();
    if(type!='' && type != 0) {
        if(type==3) {
            $('#dcompanyid').show();
            $('#tcompanyid').show();
            $('#dcustomerid').hide();
            $('#tcustomerid').hide();
            $('#companyid').combogrid({required:true});
            $('#customerid').combogrid({required:false});
            $('#customerid').combogrid('setValue','');
        }
        else if(type==4) {
            $('#dcustomerid').show();
            $('#tcustomerid').show();
            $('#dcompanyid').hide();
            $('#tcompanyid').hide();
            $('#companyid').combogrid({required:false});
            $('#customerid').combogrid({required:true});
            $('#compayid').combogrid('setValue','');
        }
        else if(type==5) {
            $('#dcustomerid').show();
            $('#tcustomerid').show();
            $('#dcompanyid').show();
            $('#tcompanyid').show();
            $('#companyid').combogrid({required:true});
            $('#customerid').combogrid({required:true});
            $('#companyid').combogrid('setValue','');
            $('#customerid').combogrid('setValue','');
        }
        else {
            $('#dcustomerid').hide();
            $('#tcustomerid').hide();
            $('#dcompanyid').hide();
            $('#tcompanyid').hide();
            $('#companyid').combogrid({required:false});
            $('#customerid').combogrid({required:false});
            $('#companyid').combogrid('setValue','');
            $('#customerid').combogrid('setValue','');
        }
    }
    else
    {
        console.log('empty');
    }
}
$("#formpackages").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('order/packages/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-packages').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
    
$('#packages_search_packageid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpackages();
			}
		}
	})
});

$('#packages_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpackages();
			}
		}
	})
});

$('#packages_search_docno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpackages();
			}
		}
	})
});
$('#packages_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpackages();
			}
		}
	})
});
$('#packages_search_packagename').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpackages();
			}
		}
	})
});
$('#packages_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchpackages();
			}
		}
	})
});
$('#dg-packages').datagrid({
		singleSelect: false,
		toolbar:'#tb-packages',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editpackages(index);
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
				url:'<?php echo $this->createUrl('packages/indexdatacomp',array('grid'=>true)) ?>?id='+row.packageid,
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
					{field:'companycode',title:'<?php echo GetCatalog('companycode') ?>',align:'right',width:'80px'},
					{field:'companyame',title:'<?php echo GetCatalog('companyname') ?>',align:'right',width:'180px'},
				]],
			});
            ddvpackagecustomer.datagrid({
				url:'<?php echo $this->createUrl('packages/indexdatacust',array('grid'=>true)) ?>?id='+row.packageid,
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
					{field:'fullname',title:'<?php echo GetCatalog('customer') ?>',align:'right',width:'250px'},
					{field:'areaname',title:'<?php echo GetCatalog('salesarea') ?>',align:'right',width:'180px'},
					{field:'groupname',title:'<?php echo GetCatalog('groupcustomer') ?>',width:'100px'},
					{field:'gradedesc',title:'<?php echo GetCatalog('grade') ?>',align:'right',width:'100px'},
				]],
			});
            ddvpackagedetail.datagrid({
				url:'<?php echo $this->createUrl('packages/indexdetail',array('grid'=>true)) ?>?id='+row.packageid,
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
					{field:'price',title:'<?php echo GetCatalog('price') ?>',align:'right',width:'150px'},
				]],
			});
			ddvpackagedisc.datagrid({
				url:'<?php echo $this->createUrl('packages/indexdisc',array('grid'=>true)) ?>?id='+row.packageid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				showFooter:true,
				columns:[[
					{field:'_expander',expander:true,width:24,fixed:true},
					{field:'discvalue',title:'<?php echo GetCatalog('discvalue') ?>',align:'right',width:'100px'},
				]],
				onResize:function(){
						$('#dg-packages').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-packages').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-packages').datagrid('fixDetailRowHeight',index);
		},
        url: '<?php echo $this->createUrl('packages/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-packages').edatagrid('reload');
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

function searchpackages(){
	$('#dg-packages').edatagrid('load',{
		packageid:$('#packages_search_packageid').val(),
		companyname:$('#packages_search_companyname').val(),
		docno:$('#packages_search_docno').val(),
		customer:$('#packages_search_customer').val(),
		packagename:$('#packages_search_packagename').val(),
		headernote:$('#packages_search_headernote').val(),
	});
};

function approvepackages() {
	var ss = [];
	var rows = $('#dg-packages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('packages/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-packages').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelpackages() {
	var ss = [];
	var rows = $('#dg-packages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('packages/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-packages').edatagrid('reload');				
		} ,
		'cache':false});
};

function purgepackages() {
	var ss = [];
	var rows = $('#dg-packages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('packages/purge') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-packages').edatagrid('reload');				
		} ,
		'cache':false});
};

function downpdfpackages() {
	var ss = [];
	var rows = $('#dg-packages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	window.open('<?php echo $this->createUrl('packages/downpdf') ?>?id='+ss);
};

function downxlspackages() {
	var ss = [];
	var rows = $('#dg-packages').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.packageid);
	}
	window.open('<?php echo $this->createUrl('packages/downxls') ?>?id='+ss);
}

function addpackages() {
		$('#dlg-packages').dialog('open');
		$('#ff-packages-modif').form('clear');
		$('#ff-packages-modif').form('load','<?php echo $this->createUrl('packages/GetData') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editpackages($i) {
	var row = $('#dg-packages').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('apppkg') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-packages').dialog('open');
			$('#ff-packages-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormpackages(){
	$('#ff-packages-modif').form('submit',{
		url:'<?php echo $this->createUrl('packages/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-packages').datagrid('reload');
        $('#dlg-packages').dialog('close');
			}
    }
	});	
};

function clearFormpackages(){
		$('#ff-packages-modif').form('clear');
};

function cancelFormpackages(){
		$('#dlg-packages').dialog('close');
};

$('#ff-packages-modif').form({
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
	url: '<?php echo $this->createUrl('packages/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('packages/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('packages/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('packages/purgedetail',array('grid'=>true))?>',
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
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
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
	url: '<?php echo $this->createUrl('packages/searchdisc',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('packages/savedisc',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('packages/savedisc',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('packages/purgedisc',array('grid'=>true))?>',
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
				precision:4,
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
