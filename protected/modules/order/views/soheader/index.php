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
<table id="dg-soheader"  style="width:1200px;height:97%"></table>
<div id="tb-soheader">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addSoheader()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editSoheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvesoheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelsoheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Purge"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="purgesoheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfsoheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formSoheader" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileSoheader" id="FileSoheader" style="display:inline">
			<input type="submit" value="Upload" name="submitSoheader" style="display:inline">
		</form>
	<?php }?>
    <br />XLSX SO-PO Cabang :
    <?php //if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formSoheaderPO" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileSoheaderPO" id="FileSoheaderPO" style="display:inline">
			<input type="submit" value="Upload" name="submitSoheader" style="display:inline">
		</form>
	<?php //}?>
<table>
	<tr>
		<td><?php echo GetCatalog('soheaderid')?></td>
		<td><input class="easyui-textbox" id="soheader_search_soheaderid" style="width:150px"></td>
		<td><?php echo GetCatalog('companyname')?></td>
		<td><input class="easyui-textbox" id="soheader_search_companyname" style="width:150px"></td>
		<td><?php echo GetCatalog('sono')?></td>
		<td><input class="easyui-textbox" id="soheader_search_sono" style="width:150px"></td>
		<td><?php echo GetCatalog('sales')?></td>
		<td><input class="easyui-textbox" id="soheader_search_sales" style="width:150px"></td>
	</tr>
	<tr>
		<td><?php echo GetCatalog('customer')?></td>
		<td><input class="easyui-textbox" id="soheader_search_customer" style="width:150px"></td>
		<td><?php echo GetCatalog('pocustno')?></td>
		<td><input class="easyui-textbox" id="soheader_search_pocustno" style="width:150px"></td>
		<td><?php echo GetCatalog('poplant')?></td>
		<td><input class="easyui-textbox" id="soheader_search_poplant" style="width:150px"></td>
		<td><?php echo GetCatalog('headernote')?></td>
		<td><input class="easyui-textbox" id="soheader_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchsoheader()"></a></td>
	</tr>
</table>
</div>

<div id="dlg-soheader" class="easyui-dialog" title="Sales Order" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormSoheader();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-soheader').dialog('close');
			}
		},
	]	
	">
	<form id="ff-soheader-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="soheaderid" id="soheaderid"></input>
	<input type="hidden" name="iseditprice" id="iseditprice"></input>
	<input type="hidden" name="iseditdisc" id="iseditdisc"></input>
	<input type="hidden" name="isedittop" id="isedittop"></input>
		<table cellpadding="5">
		<tr>
				<td><?php echo GetCatalog('sodate')?></td>
				<td><input class="easyui-datebox" type="text" id="sodate" name="sodate" data-options=
				"formatter:dateformatter,required:true,readonly:true,parser:dateparser"></input></td>
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
				<td><?php echo GetCatalog('customer')?></td>
				<td><select class="easyui-combogrid" id="addressbookid" name="addressbookid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'addressbookid',
								required: true,
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								method: 'get',
								url:'<?php echo Yii::app()->createUrl('common/customer/index',array('grid'=>true,'company'=>true)) ?>',
                                onBeforeLoad: function(param) {
							       param.companyid = $('#companyid').combogrid('getValue');
						        },
								onHidePanel: function(){
										jQuery.ajax({'url':'<?php echo $this->createUrl('soheader/generateaddress') ?>',
											'data':{'id':$('#addressbookid').combogrid('getValue'),
											'hid':$('#soheaderid').val()},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#shipto').textbox('setValue',data.shipto);
												$('#billto').textbox('setValue',data.billto);
												$('#dg-sodisc').edatagrid('reload');
                                                $('#materialtypeid').combogrid('setValue','');
                                                $('#poheaderid').combogrid('setValue','');
                                                $('#packageid').combogrid('setValue','');
                                                $('#qtypackage').numberbox('setValue','');
											} ,
											'cache':false});
								},
								columns: [[
										{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
										{field:'currentlimit',title:'<?php echo GetCatalog('currentarlimit') ?>'},
										{field:'creditlimit',title:'<?php echo GetCatalog('creditlimit') ?>'},
										{field:'overdue',title:'<?php echo GetCatalog('overdue') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('sotype')?></td>
				<td>
                    <select class="easyui-combobox" id="sotype" name="sotype" data-options="required:'true', panelHeight:'auto'" style="width:120px">
                        <!--<option value=""><?= getCatalog('sotype')?></option>-->
                        <option value="1"><?= getCatalog('materialtype')?></option>
                        <option value="2"><?= getCatalog('package')?></option>
                        <option value="3"><?= getCatalog('Cabang')?></option>
                </td>
			</tr>
			<tr style="display:none;" id="poplantgrid">
				<td><?php echo GetCatalog('poplant')?></td>
				<td><select class="easyui-combogrid" id="poheaderid" name="poheaderid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'poheaderid',
								required: false,
								textField: 'pono',
								pagination:true,
                                url:'<?php echo Yii::app()->createUrl('purchasing/poheader/indexpoplant',array('grid'=>true,'combo'=>true)) ?>',
								mode:'remote',
								method: 'get',
                                onBeforeLoad: function(param) {
							     //var row = $('#dg-cb').datagrid('getSelected');
							     //param.slocid = row.slocid;
							       param.companyid = $('#companyid').combogrid('getValue');
						        },
                                onHidePanel: function()
                                {
							     jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/soheader/generatedetail') ?>',
								'data':{'id':$('#poheaderid').combogrid('getValue'),'hid':$('#soheaderid').val(),'companyid':$('#companyid').combogrid('getValue')},
								'type':'post','dataType':'json',
								'success':function(data)
								{
									$('#pocustno').textbox('setValue',data.pono);
									$('#dg-sodetail').datagrid({
										queryParams: {
											id: $('#soheaderid').val()
										}
									});
								} ,
								'cache':false});
					             },								
								columns: [[
                                {field:'poheaderid',title:'<?php echo GetCatalog('poheaderid') ?>'},
                                {field:'pono',title:'<?php echo GetCatalog('pono') ?>'},
                                {field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
                                {field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
					</select>
				</td>
			</tr>
            <tr style="display:none" id="materialtypesgrid">
				<td><?php echo GetCatalog('materialtype')?></td>
				<td><select class="easyui-combogrid" id="materialtypeid" name="materialtypeid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'materialtypeid',
								required: false,
								textField: 'description',
								pagination:true,
                                url:'<?php echo Yii::app()->createUrl('common/materialtype/index',array('grid'=>true,'soheader'=>true)) ?>',
								mode:'remote',
								method: 'get',
                                onBeforeLoad: function(param) {
							     //var row = $('#dg-cb').datagrid('getSelected');
							     //param.slocid = row.slocid;
                                   //console.log('test');
                                   //$('#materialtypeid').combogrid('clear');
							       param.addressbookid = $('#addressbookid').combogrid('getValue');
						        },
                                onHidePanel: function()
                                {
							     jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/soheader/setMaterialtype') ?>',
								'data':{
                                    'soheaderid':$('#soheaderid').val(),
                                    'addressbookid':$('#addressbookid').combogrid('getValue'),
                                    'materialtypeid':$('#materialtypeid').combogrid('getValue')
                                },
								'type':'post','dataType':'json',
								'success':function(data)
								{
                                    //console.log(data);
                                    $('#iseditprice').val(data.iseditprice);
                                    $('#iseditdisc').val(data.iseditdisc);
                                    $('#isedittop').val(data.isedittop);
                                    console.log(data);
                                    if(data.isedittop==1) {
                                        $('#isedittopgrid').show();
                                        $('#topgrid').hide();
                                        $('#paycodes').textbox('setValue','');
                                        $('#paymentmethodid').combogrid('setValue',data.paymentmethodid);
                                        $('#paymentmethodid').combogrid({required:true,readonly:false});
                                    }
                                    else {
                                        $('#isedittopgrid').hide();
                                        $('#paycodes').textbox('setValue',data.paycode);
                                        console.log(data.paycode);
                                        $('#paymentmethodid').combogrid({required:true,readonly:true});
                                        $('#topgrid').show();
                                    }
                                    $('#paymentmethodid').combogrid('setValue',data.paymentmethodid);
                                    $('#dg-sodetail').datagrid('showColumn','price');
                                    $('#dg-sodetail').datagrid('showColumn','total');
                                    $('#dg-sodisc').datagrid('showColumn','discvalue');
                                    show('Message',data.pesan);
                                    if(data.success == false) {
                                        //console.log(data);
                                        $('#materialtypeid').combogrid('setValue','');
                                    }
                                    $('#dg-sodetail').datagrid({
										queryParams: {
											id: $('#soheaderid').val()
										}
									});
									$('#dg-sodisc').datagrid({
										queryParams: {
											id: $('#soheaderid').val()
										}
									});
								} ,
								'cache':false});
					             },								
								columns: [[
                                {field:'materialtypeid',title:'<?php echo GetCatalog('materialtypeid') ?>'},
                                {field:'materialtypecode',title:'<?php echo GetCatalog('materialtypecode') ?>'},
                                {field:'description',title:'<?php echo GetCatalog('description') ?>'},
                                {field:'iseditpriceso',title:'<?php echo GetCatalog('iseditpriceso') ?>',align:'center',
                                    formatter: function(value,row,index){
                                        if (value == 1){
                                            return '<img src=<?php echo Yii::app()->request->baseUrl?>/images/ok.png></img>';
                                        } else {
                                            return '';
                                        }
                                }},
                                {field:'iseditdiscso',title:'<?php echo GetCatalog('iseditdiscso') ?>',align:'center',
                                    formatter: function(value,row,index){
                                        if (value == 1){
                                            return '<img src=<?php echo Yii::app()->request->baseUrl?>/images/ok.png></img>';
                                        } else {
                                            return '';
                                        }
                                }},
                                {field:'paymentmethodid',title:'<?php echo GetCatalog('paymentmethodid') ?>',hidden:true},
                                {field:'paycode',title:'<?php echo GetCatalog('paymentmethod') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
            </tr>
            <tr id="packagegrid" style="display:none">
				<td><?php echo GetCatalog('package')?></td>
				<td><select class="easyui-combogrid" id="packageid" name="packageid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'packageid',
								required: false,
								textField: 'packagename',
								pagination:true,
                                url:'<?php echo Yii::app()->createUrl('order/packages/indexcombo',array('grid'=>true)) ?>',
								mode:'remote',
								method: 'get',
                                onBeforeLoad: function(param) {
							     param.addressbookid = $('#addressbookid').combogrid('getValue');
                                 param.companyid = $('#companyid').combogrid('getValue');
						        },
                                /*
                                onHidePanel: function()
                                {
                                    jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/soheader/generatepackagedetail') ?>',
                                    'data':{
                                            'packageid':$('#packageid').combogrid('getValue'),
                                            'companyid':$('#companyid').combogrid('getValue'),
                                            'soheaderid':$('#soheaderid').val()
                                    },
                                    'type':'post','dataType':'json',
                                    'success':function(data)
                                    {
                                        //console.log(data);
                                        show('Message',data.pesan);
                                        $('#dg-sodetail').datagrid({
                                            queryParams: {
                                                id: $('#soheaderid').val()
                                            }
                                        });
                                        $('#dg-sodisc').datagrid({
                                            queryParams: {
                                                id: $('#soheaderid').val()
                                            }
                                        });
                                    } ,
								    'cache':false});
					            },
                                */
                                onHidePanel: function()
                                {
                                    $('#qtypackages').show();
                                },
                                    columns: [[
                                    {field:'packagename',title:'<?php echo GetCatalog('packagename') ?>'},
                                    {field:'headernote',title:'<?php echo GetCatalog('description') ?>'},
                                    {field:'startdate',title:'<?php echo GetCatalog('startdate') ?>'},
                                    {field:'enddate',title:'<?php echo GetCatalog('enddate') ?>'},
                                    {field:'paycode',title:'<?php echo GetCatalog('paymentmethod') ?>'},
                                    {field:'paymentmethodid',title:'<?php echo GetCatalog('ID') ?>',hidden:true},
								]],
								fitColumns: true,
                                onSelect:function(index,row) {
                                    $('#paymentmethodid').combogrid('setValue',row.paymentmethodid);
                                    //console.log(row);
                                }
						">
				</select></td>
            </tr>
            <tr id="qtypackages" style="display:none">
				<td><?php echo getCatalog('qty').' '.getCatalog('packages')?></td>
				<td><input class='easyui-numberbox' type='text' id='qtypackage' name='qtypackage' data-options='required:true,precision:0'>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="setqty()" >Set Qty</a>
                </td>
			</tr>
			<tr id="isdisplaygrid">
				<td><?= GetCatalog('isdisplay')?></td>
				<td><input id='isdisplay' type='checkbox' name='isdisplay' style='width:250px'></input></td>
			</tr>		
			<tr>
				<td><?php echo GetCatalog('pocustno')?></td>
				<td><input class="easyui-textbox" name="pocustno" data-options="required:true"></input></td>
			</tr>
<tr>
				<td><?php echo GetCatalog('sales')?></td>
				<td><select class="easyui-combogrid" id="employeeid" name="employeeid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'employeeid',
								required: true,
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								method: 'get',
								url:'<?php echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ?>',
								columns: [[
										{field:'employeeid',title:'<?php echo GetCatalog('employeeid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
										{field:'oldnik',title:'<?php echo GetCatalog('oldnik') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>			
			<tr id="topgrid">
				<td><?php echo GetCatalog('paymentmethod')?></td>
                <td><input class="easyui-textbox" id="paycodes" data-options="readonly:true"></input></td>
            </tr>
            <tr id="isedittopgrid" style="display:none">
                <td><?php echo GetCatalog('paymentmethod')?></td>
				<td><select class="easyui-combogrid" id="paymentmethodid" name="paymentmethodid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'paymentmethodid',
								required: true,
                                readonly: true,
								textField: 'paycode',
								pagination:true,
								mode:'remote',
								method: 'get',
								url:'<?php echo Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ?>',
								columns: [[
										{field:'paymentmethodid',title:'<?php echo GetCatalog('paymentmethodid') ?>'},
										{field:'paycode',title:'<?php echo GetCatalog('paycode') ?>'},
										{field:'paydays',title:'<?php echo GetCatalog('paydays') ?>'},
										{field:'paymentname',title:'<?php echo GetCatalog('paymentname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>	
			<tr>
				<td><?php echo GetCatalog('tax')?></td>
				<td><select class="easyui-combogrid" id="taxid" name="taxid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'taxid',
								required: true,
								textField: 'taxcode',
								pagination:true,
								mode:'remote',
								method: 'get',
								url:'<?php echo Yii::app()->createUrl('accounting/tax/index',array('grid'=>true,'combo'=>true)) ?>',
								columns: [[
										{field:'taxid',title:'<?php echo GetCatalog('taxid') ?>'},
										{field:'taxcode',title:'<?php echo GetCatalog('taxcode') ?>'},
										{field:'taxvalue',title:'<?php echo GetCatalog('taxvalue') ?>'},
										{field:'description',title:'<?php echo GetCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>	
			<tr>
				<td><?php echo GetCatalog('shipto')?></td>
				<td><input class="easyui-textbox" id="shipto" name="shipto" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('billto')?></td>
				<td><input class="easyui-textbox" id="billto" name="billto" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:800px">
		<div title="Detail" style="padding:5px">
			<table id="dg-sodetail"  style="width:100%;height:400px">
			</table>
			<div id="tb-sodetail">
				<a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-sodetail').edatagrid('addRow')" id="addsodetail" style="display:none"></a>
				<a href="javascript:void(0)" title="Simpan" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-sodetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-sodetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="hapus" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-sodetail').edatagrid('destroyRow')" id="delsodetail"></a>
			</div>
			<table id="dg-sodisc"  style="width:100%;height:400px">
			</table>
			<div id="tb-sodisc">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-sodisc').edatagrid('addRow')" id="addsodisc" style="display:none"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-sodisc').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-sodisc').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-sodisc').edatagrid('destroyRow')" id="delsodisc"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

function getsotype() {
  
  jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/soheader/getSotype') ?>',
        'data':{
                'soheaderid':$('#soheaderid').val(),
        },
        'type':'post','dataType':'json',
        'success':function(data)
        {
            console.log(data);
            //show('Message',data.pesan);
            //setTop(60);
            //return data.sotype;
            var selectedrow = $("#dg-sodetail").datagrid("getSelected");
            var rowIndex = $("#dg-sodetail").datagrid("getRowIndex", selectedrow);

            //var index = $('#dg-sodetail').datagrid('getSelected').getRowIndex');
            var productid = $("#dg-sodetail").datagrid("getEditor", {index: rowIndex, field:"productid"});
            var qty = $("#dg-sodetail").datagrid("getEditor", {index: rowIndex, field:"qty"});
            $(productid.target).combogrid('readonly',false);
            $(qty.target).combogrid('readonly',false);
            if(data.sotype == 1) { 
              $(productid.target).combogrid('readonly',true);
              $(qty.target).combogrid('readonly',true);
              //console.log($('#dg-sodetail').edatagrid('getSelected'));
              //console.log(rowIndex);
              //console.log(tbl);
            }
        },
        'cache':false
    });
    
    //console.log('Hi');
}
$(document).ready(function(){
    $("input#isdisplay").change(function() {
        let isdisplay = $(this).prop( "checked" );
        if(isdisplay) {
            let value = 120;
            setTop(value);
            //console.log('checked');
        }
        else {
            setTop(0);
            //console.log('not checked');
        }
    });
});

$('#sotype').combobox({
    onClick: function(r){
        if(r.value=='1') {
            $('#packagegrid').hide();
            $('#qtypackages').hide();
            $('#poplantgrid').hide();
						$('#isdisplaygrid').show();
            $('#materialtypesgrid').show();
            $('#qtypackage').numberbox('setValue', '');
            $('#poheaderid').combogrid('setValue', '');
            $('#qtypackage').numberbox({required: false, disabled: true});
            $('#materialtypeid').combogrid({required:true});
            $('#packageid').combogrid({required:false});
						$('#poheaderid').combogrid({required:false});
            $('#packageid').combogrid('setValue','');
            $("#addsodetail").css('display','inline');
            $("#delsodetail").css('display','inline');
            //$("#addsodisc").css('display','inline');
            //$("#delsodisc").css('display','inline');
            
        }
        else if(r.value=='2') {
            $('#packagegrid').show();
            //$('#qtypackages').show();
            $('#isdisplay').prop('checked',false);
            $('#isdisplaygrid').hide();
            $('#materialtypesgrid').hide();
						$('#poplantgrid').hide();
            $("#addsodetail").css('display','none');
            $("#delsodetail").css('display','none');
						$('#poheaderid').combogrid('setValue', '');
						$('#materialtypeid').combogrid('setValue', '');
            $('#materialtypeid').combogrid({required:false});
						$('#poheaderid').combogrid({required:false});
            $('#packageid').combogrid({required:true});
            $('#qtypackage').numberbox({required: true, disabled: false});
            //$("#addsodisc").css('display','none');
            //$("#delsodisc").css('display','none');
        }
				else if(r.value==3) {
					$('#poplantgrid').show();
					//$('#qtypackages').show();
					$('#isdisplay').prop('checked',false);
					$('#isdisplaygrid').hide();
					$('#materialtypesgrid').hide();
					$('#qtypackages').hide();
					$('#packagegrid').hide();
					$("#addsodetail").css('display','none');
					$("#delsodetail").css('display','none');
					$('#poheaderid').combogrid({required:true});
					$('#materialtypeid').combogrid({required:false});
					$('#packageid').combogrid({required:false});
					$('#qtypackage').numberbox('setValue','');
					$('#qtypackage').numberbox({required: false, disabled: false});
				}
        else{
            console.log('Pilih salah satu');
            $("#addsodetail").css('display','inline');
            $("#addsodisc").css('display','inline');
            $('#isdisplay').prop('checked',false);
            $('#isdisplaygrid').hide();
            $('#materialtypesgrid').hide();
            $('#qtypackage').numberbox('setValue','');
            $('#packagegrid').hide();
        }
      }
   });
    
function setqty()
{
    var qtypkg = $("#qtypackage").numberbox('getValue');
    //console.log(qtypkg);
    tampil_loading();
    jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/soheader/generatepackagedetail') ?>',
        'data':{
                'packageid':$('#packageid').combogrid('getValue'),
                'companyid':$('#companyid').combogrid('getValue'),
                'soheaderid':$('#soheaderid').val(),
                'addressbookid':$('#addressbookid').combogrid('getValue'),
                'qty':qtypkg
        },
        'type':'post','dataType':'json',
        'success':function(data)
        {
            //console.log(data);
            //var selectedrow = $("#dg-sodetail").datagrid("getSelected");
            //var rowIndex = $("#dg-sodetail").datagrid("getRowIndex", selectedrow);

            //var index = $('#dg-sodetail').datagrid('getSelected').getRowIndex');
            $("#dg-sodetail").datagrid("hideColumn","price");
            $("#dg-sodetail").datagrid("hideColumn","total");
            $("#dg-sodisc").datagrid("hideColumn","discvalue");
            ///var qty = $("#dg-sodetail").datagrid("getEditor", {index: rowIndex, field:"qty"});
            //$(productid.target).combogrid('readonly',true);
            //$(qty.target).combogrid('readonly',true);
            //console.log($('#dg-sodetail').edatagrid('getSelected'));
            console.log('Hii');

            show('Message',data.pesan);
            //setTop(60);
            $('#dg-sodetail').datagrid({
                queryParams: {
                    id: $('#soheaderid').val()
                }
            });
            $('#dg-sodisc').datagrid({
                queryParams: {
                    id: $('#soheaderid').val()
                }
            });
        },
        'cache':false
    });
    
}
function setTop(value) {
    if(value==0) {
        //console.log('0');
        //$("#paymentmethodid").combogrid({required:true,readonly:false});
        $("#paymentmethodid").combogrid('setValue','');
    }
    else {
         jQuery.ajax({'url':'<?php echo $this->createUrl('soheader/getTop') ?>',
		'data':{'payday':value},'type':'post','dataType':'json',
        'success':function(data)
		{
            console.log(data);
            $("#paymentmethodid").combogrid({required:true,readonly:true});
            $("#paymentmethodid").combogrid('setValue',data.paymentmethodid);
		},
		'cache':false});
    }
}
    
$('#approvesoheader').click(function(){
    tampil_loading();
});

function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css("visibility", "hidden");
}

$("#formSoheader").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('order/soheader/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-soheader').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
    
$("#formSoheaderPO").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('order/soheader/uploadSOPO') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-soheader').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#soheader_search_soheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#soheader_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#soheader_search_poplant').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#soheader_search_sono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#soheader_search_sales').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#soheader_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#soheader_search_pocustno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#soheader_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#dg-soheader').datagrid({
		singleSelect: false,
		toolbar:'#tb-soheader',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editSoheader(index);
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-sodetail"></table><table class="ddv-sodisc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvsodetail = $(this).datagrid('getRowDetail',index).find('table.ddv-sodetail');
			var ddvsodisc = $(this).datagrid('getRowDetail',index).find('table.ddv-sodisc');
      var packageid = row.packageid;
      console.log(packageid);
			ddvsodetail.datagrid({
				url:'<?php echo $this->createUrl('soheader/indexdetail',array('grid'=>true)) ?>?id='+row.soheaderid,
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
					{field:'isbonus',title:'<?php echo GetCatalog('isbonus') ?>',width:'60px',
						formatter: function(value,row,index){
						if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
							return '';
						}}
					},
					{field:'qty',title:'<?php echo GetCatalog('qty') ?>',align:'right',width:'80px'},
					{field:'giqty',title:'<?php echo GetCatalog('giqty') ?>',align:'right',width:'80px'},
					{field:'qtystock',title:'<?php echo GetCatalog('qtystock') ?>',align:'right',width:'80px'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>',width:'100px'},
					{field:'sloccode',title:'<?php echo GetCatalog('sloc') ?>',width:'120px'},
					{field:'price',title:'<?php echo GetCatalog('price') ?>',align:'right',width:'150px',
          hidden: (row.packageid === null) ? false : true},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>',width:'100px'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right',width:'80px'},
					{field:'total',title:'<?php echo GetCatalog('total') ?>',align:'right',width:'150px',
          hidden: (row.packageid === null) ? false : true},
					{field:'delvdate',title:'<?php echo GetCatalog('delvdate') ?>',width:'100px'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>',width:'150px'},
				]],
			});
			ddvsodisc.datagrid({
				url:'<?php echo $this->createUrl('soheader/indexdisc',array('grid'=>true)) ?>?id='+row.soheaderid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				showFooter:true,
				columns:[[
					{field:'_expander',expander:true,width:24,fixed:true},
					{field:'sodetailid',title:''},
					{field:(row.packageid === null) ? 'discvalue' : 'discvalue1',title:'<?php echo GetCatalog('discvalue') ?>',align:'right',width:'100px'},
				]],
				onResize:function(){
						$('#dg-soheader').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-soheader').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-soheader').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('soheader/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-soheader').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		rowStyler:function(index,row){
			switch(row.warna) {
				case '1':
					return 'background-color:red;color:white;font-weight:bold;';
					break;
				case '2':
					return 'background-color:orange;color:white;font-weight:bold;';
					break;
				case '3':
					return 'background-color:yellow;color:black;font-weight:bold;';
					break;
				default :
					return 'background-color:white;color:black;font-weight:bold;';
			}
    },
		idField:'soheaderid',
		editing: false,
		columns:[[
		{
field:'soheaderid',
title:'<?php echo GetCatalog('soheaderid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
		}},
{
field:'sono',
title:'<?php echo GetCatalog('sono') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'sodate',
title:'<?php echo GetCatalog('sodate') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'companyid',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'300px',
formatter: function(value,row,index){
						return row.companyname;
					}},
					{
field:'pono',
title:'<?php echo GetCatalog('pono') ?>',
sortable: true,
width:'125px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'pocustno',
title:'<?php echo GetCatalog('pocustno') ?>',
sortable: true,
width:'125px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'isdisplay',
title:'<?= GetCatalog('isdisplay') ?>',
align:'center',
width:'100px',
sortable: true,
formatter: function(value,row,index){
	if (value == 1){
		return '<img src=\"<?= Yii::app()->request->baseUrl?>/images/ok.png"\></img>';
	} else {
		return '';
	}
}},
{
field:'addressbookid',
title:'<?php echo GetCatalog('customer') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.customername;
					}},
{
field:'sotype',
title:'<?php echo GetCatalog('sotype') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.sotypename;
					}},
{
field:'materialtypeid',
title:'<?php echo GetCatalog('materialtype') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.description;
					}},
{
field:'packageid',
title:'<?php echo GetCatalog('package') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.packagename;
					}},
{
field:'top',
title:'<?php echo GetCatalog('top') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'currentlimit',
title:'<?php echo GetCatalog('currentarlimit') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return value;
					}},
					{
field:'creditlimit',
title:'<?php echo GetCatalog('creditlimit') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return value;
					}},
										{
field:'pendinganso',
title:'<?php echo GetCatalog('pendinganso') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'totalbefdisc',
title:'<?php echo GetCatalog('totalbefdisc') ?>',
sortable: true,
width:'125px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'totalaftdisc',
title:'<?php echo GetCatalog('totalaftdisc') ?>',
sortable: true,
width:'125px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'employeeid',
title:'<?php echo GetCatalog('sales') ?>',
sortable: true,
width:'125px',
formatter: function(value,row,index){
						return row.employeename;
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
field:'taxid',
title:'<?php echo GetCatalog('tax') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.taxcode;
					}},
										{
field:'shipto',
title:'<?php echo GetCatalog('shipto') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.shipto;
					}},
															{
field:'billto',
title:'<?php echo GetCatalog('billto') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.billto;
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
field:'recordstatussoheader',
title:'<?php echo GetCatalog('recordstatus') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'createddate',
title:'<?php echo GetCatalog('createddate') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'updatedate',
title:'<?php echo GetCatalog('updatedate') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});

function searchsoheader(){
	$('#dg-soheader').edatagrid('load',{
		soheaderid:$('#soheader_search_soheaderid').val(),
		companyname:$('#soheader_search_companyname').val(),
		sono:$('#soheader_search_sono').val(),
		sales:$('#soheader_search_sales').val(),
		customer:$('#soheader_search_customer').val(),
		pocustno:$('#soheader_search_pocustno').val(),
		pono:$('#soheader_search_poplant').val(),
		headernote:$('#soheader_search_headernote').val(),
	});
};
/*
function approvesoheader() {
	var ss = [];
	var rows = $('#dg-soheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.soheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('soheader/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-soheader').edatagrid('reload');				
		} ,
		'cache':false});
};
*/
function approvesoheader() {
         $('.ajax-loader').css('visibility', 'visible');
	var ss = [];
	var rows = $('#dg-soheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.soheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('soheader/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		statusCode: {
            200: function(data) {
                //console.log(data);
                //show('Message','Simpan Data Berhasil');
                $('#dg-soheader').edatagrid('reload');  
            }
        },
        'success':function(data)
		{
			tutup_loading();
			console.log(data);
            show('Message',data.pesan);
			$('#dg-soheader').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelsoheader() {
	var ss = [];
	var rows = $('#dg-soheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.soheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('soheader/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-soheader').edatagrid('reload');				
		} ,
		'cache':false});
};

function purgesoheader() {
	var ss = [];
	var rows = $('#dg-soheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.soheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('soheader/purge') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-soheader').edatagrid('reload');				
		} ,
		'cache':false});
};

function downpdfsoheader() {
	var ss = [];
	var rows = $('#dg-soheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.soheaderid);
	}
	window.open('<?php echo $this->createUrl('soheader/downpdf') ?>?id='+ss);
};

function downxlssoheader() {
	var ss = [];
	var rows = $('#dg-soheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.soheaderid);
	}
	window.open('<?php echo $this->createUrl('soheader/downxls') ?>?id='+ss);
}

function addSoheader() {
		$('#dlg-soheader').dialog('open');
		$('#ff-soheader-modif').form('clear');
		$('#ff-soheader-modif').form('load','<?php echo $this->createUrl('soheader/GetData') ?>');
		$('#sodate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editSoheader($i) {
	var row = $('#dg-soheader').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appso') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-soheader').dialog('open');
			$('#ff-soheader-modif').form('load',row);
            var y = $('#sotype').combobox('getValue');
            if(y==1) {
                $('#materialtypesgrid').show();
                $('#packagegrid').hide();
                $('#qtypackages').hide();
								$('#poplantgrid').hide();
                $("#addsodetail").css('display','inline');
                $("#delsodetail").css('display','inline');
            }
            else if(y==2)
            {
                $('#packagegrid').show();
                $('#qtypackages').show();
								$('#poplantgrid').hide();
                $('#materialtypesgrid').hide();
                $("#addsodetail").css('display','none');
                $("#delsodetail").css('display','none');
            }
						else if(y==3)
						{
							$('#materialtypesgrid').hide();
							$('#packagegrid').hide();
							$('#qtypackages').hide();
							$('#poplantgrid').show();
						}
            else {
                $('#packagegrid').hide();
                $('#materialtypesgrid').hide();
                $('#isdisplay').prop('checked',false);
                $('#isdisplaygrid').hide();
            }
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormSoheader(){
	$('#ff-soheader-modif').form('submit',{
		url:'<?php echo $this->createUrl('soheader/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-soheader').datagrid('reload');
        $('#dlg-soheader').dialog('close');
			}
            tutup_loading();
    }
	});	
};

function clearFormSoheader(){
		$('#ff-soheader-modif').form('clear');
};

function cancelFormSoheader(){
		$('#dlg-soheader').dialog('close');
        tutup_loading();
};

$('#ff-soheader-modif').form({
	onLoadSuccess: function(data) {
		if (data.isdisplay == 1) {
			$('#isdisplay').prop('checked', true);
		} else {
			$('#isdisplay').prop('checked', false);
		}
        $('#paycodes').textbox('setValue',data.paycode);
        jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/soheader/getAttr')?>',
        'data':{
            'soheaderid':data.soheaderid,
            'addressbookid':data.addressbookid,
            'materialtypeid':data.materialtypeid,
        },
        'type':'post',
        'dataType':'json',
        'success':function(datas) {
            //console.log(datas);
            $('#iseditprice').val(datas.iseditpriceso);
            $('#iseditdisc').val(datas.iseditdiscso);
            $('#isedittop').val(datas.isedittop);
        }
        });
		$('#dg-sodetail').datagrid({
				queryParams: {
					id: $('#soheaderid').val()
				}
		});
		$('#dg-sodisc').datagrid({
			queryParams: {
				id: $('#soheaderid').val()
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

$('#dg-sodetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'sodetailid',
	editing: true,
	toolbar:'#tb-sodetail',
	fitColumn: true,
	pagination:true,
	showFooter:true,
	url: '<?php echo $this->createUrl('soheader/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('soheader/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('soheader/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('soheader/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-sodetail').edatagrid('reload');
		$('#dg-sodisc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},	
	onBeforeEdit:function(index,row) {
		row.soheaderid = $('#soheaderid').val();
	},
  onBeginEdit:function(index,row) {
    var ed = $("#dg-sodetail").datagrid('getEditor',{index: index, field:"price"});
    let iseditprice = $('#iseditprice').val();
    let sotype = $('#sotype').combobox('getValue');
    $(ed.target).numberbox({disabled:true});
    if(iseditprice==1) {
        $(ed.target).numberbox({disabled:false});
    }
    //var selectedrow = $("#dg-sodetail").datagrid("getSelected");
    //var rowIndex = $("#dg-sodetail").datagrid("getRowIndex", selectedrow);

            //var index = $('#dg-sodetail').datagrid('getSelected').getRowIndex');
    var productid = $("#dg-sodetail").datagrid("getEditor", {index: index, field:"productid"});
    var qty = $("#dg-sodetail").datagrid("getEditor", {index: index, field:"qty"});
    $(productid.target).combogrid('readonly',false);
    $(qty.target).combogrid('readonly',false);
    
    if(sotype == 2 || sotype == 3) { 
      $(productid.target).combogrid('readonly',true);
      $(qty.target).combogrid('readonly',true);
      //console.log($('#dg-sodetail').edatagrid('getSelected'));
      //console.log(rowIndex);
      //console.log(tbl);
    }
    
        //let materialtype = $("#materialtypeid").combogrid('getValue');
        //if(materialtype)
    console.log(productid);
  },
	columns:[[
	{
		field:'soheaderid',
		title:'<?php echo GetCatalog('soheaderid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'sodetailid',
		title:'<?php echo GetCatalog('sodetailid') ?>',
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
						queryParams:{
							trxplantmattype:true
						},
						fitColumns:true,
						required:true,
            //readonly: <?php //echo $this->getSotype() == 1 ? true : false ?>,
						pagination:true,
            onBeforeLoad: function(param) {
              param.materialtypeid = $('#materialtypeid').combogrid('getValue');
              //getsotype();
            },
						onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
                                let sotype = $("#sotype").combobox('getValue');
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $("#dg-sodetail").datagrid("getEditor", {index: index, field:"productid"});
								var uomid = $("#dg-sodetail").datagrid("getEditor", {index: index, field:"unitofmeasureid"});
								var slocid = $("#dg-sodetail").datagrid("getEditor", {index: index, field:"slocid"});
								var price = $("#dg-sodetail").datagrid("getEditor", {index: index, field:"price"});
								var currencyid = $("#dg-sodetail").datagrid("getEditor", {index: index, field:"currencyid"});
								var currencyrate = $("#dg-sodetail").datagrid("getEditor", {index: index, field:"currencyrate"});
                                if(sotype == 1) {
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdatasales') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue"),
                                           'companyid':$('#companyid').combogrid("getValue")},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uomid);
										$(slocid.target).combogrid('setValue',data.slocid);
									} ,
									'cache':false});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productsales/generatedata') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue"),
										'customerid':$('#addressbookid').combogrid("getValue")},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(price.target).numberbox('setValue',data.price);
										$(currencyid.target).combogrid('setValue',data.currencyid);
										$(currencyrate.target).numberbox('setValue',data.currencyrate);
									} ,
									'cache':false});
							}
						}
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
		field:'slocid',
		title:'<?php echo GetCatalog('sloc') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'<?php echo Yii::app()->createUrl('common/sloc/indextrxso',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'slocid',title:'<?php echo GetCatalog('slocid')?>',width:'50px'},
							{field:'sloccode',title:'<?php echo GetCatalog('sloccode')?>',width:'200px'},
						]]
				}	
			},
			width:'200px',
		sortable: true,
		formatter: function(value,row,index){
							return row.sloccode;
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
		field:'currencyid',
		title:'<?php echo GetCatalog('currency') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'currencyid',
						textField:'currencyname',
						url:'<?php echo Yii::app()->createUrl('admin/currency/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'currencyid',title:'<?php echo GetCatalog('currencyid')?>',width:'50px'},
							{field:'currencyname',title:'<?php echo GetCatalog('currencyname')?>',width:'200px'},
						]]
				}	
			},
			width:'150px',			
		sortable: true,
		formatter: function(value,row,index){
							return row.currencyname;
		}
	},
	{
		field:'currencyrate',
		title:'<?php echo GetCatalog('currencyrate') ?>',
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
		field:'total',
		title:'<?php echo GetCatalog('total') ?>',
		multiline:true,
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
			{
		field:'delvdate',
		title:'<?php echo getCatalog('delvdate') ?>',
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
		field:'itemnote',
		title:'<?php echo GetCatalog('itemnote') ?>',
		editor:'text',
		width:'250px',
		multiline:true,
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'isbonus',
		title:'<?php echo getCatalog('isbonus')?>',
		width:'80px',
		align:'center',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		formatter: function(value,row,index){
			if (value == 1){
				return "<img src='<?php echo Yii::app()->request->baseUrl?>/images/ok.png'></img>";
			} else {
				return '';
			}
	}},
	]]
});

$('#dg-sodisc').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'sodiscid',
	editing: true,
	toolbar:'#tb-sodisc',
	fitColumn: true,
	pagination:true,
	showFooter:true,
	url: '<?php echo $this->createUrl('soheader/searchdisc',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('soheader/savedisc',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('soheader/savedisc',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('soheader/purgedisc',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-sodisc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-sodisc').edatagrid('getSelected');
		if (row)
		{
			row.soheaderid = $('#soheaderid').val()
		}
	},
	onBeginEdit:function(index,row) {
        var ed = $("#dg-sodisc").datagrid('getEditor',{index: index, field:"discvalue"});
				let sotype =  $('#sotype').combobox('getValue');
        let iseditdisc = $('#iseditdisc').val();
        $(ed.target).numberbox({disabled:true});
        if(iseditdisc==1) {
            $(ed.target).numberbox({disabled:false});
        }
				console.warn(sotype);
				if(sotype == 2 || sotype == 3) $(ed.target).numberbox({disabled:true});
        //let materialtype = $("#materialtypeid").combogrid('getValue');
        //if(materialtype)
    },
	columns:[[
	{
		field:'soheaderid',
		title:'<?php echo GetCatalog('soheaderid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'sodiscid',
		title:'<?php echo GetCatalog('sodiscid') ?>',
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
