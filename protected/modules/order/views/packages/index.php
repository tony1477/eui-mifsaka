<table id="dg-packages" style="width:1200px;height:97%"></table>
<div id="tb-packages">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addpackages()"></a>
		<a href="javascript:void(0)" title="Rubah" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editpackages()"></a>
	<?php } ?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvepackages()"></a>
	<?php } ?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelpackages()"></a>
	<?php } ?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Purge" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="purgepackages()"></a>
	<?php } ?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfpackages()"></a>
	<?php } ?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) { ?>
		<form id="formpackages" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="Filepackages" id="Filepackages" style="display:inline">
			<input type="submit" value="Upload" name="submitpackages" style="display:inline">
		</form>
	<?php } ?>
	<table>
		<tr>
			<td><?php echo GetCatalog('packageid') ?></td>
			<td><input class="easyui-textbox" id="packages_search_packageid" style="width:50px"></td>
			<td><?php echo GetCatalog('companyname') ?></td>
			<td><input class="easyui-textbox" id="packages_search_companyname" style="width:150px"></td>
			<td><?php echo GetCatalog('docno') ?></td>
			<td><input class="easyui-textbox" id="packages_search_docno" style="width:150px"></td>
		</tr>
		<tr>
			<td><?php echo GetCatalog('customer') ?></td>
			<td><input class="easyui-textbox" id="packages_search_customer" style="width:150px"></td>
			<td><?php echo GetCatalog('packagename') ?></td>
			<td><input class="easyui-textbox" id="packages_search_packagename" style="width:150px"></td>
			<td><?php echo GetCatalog('headernote') ?></td>
			<td><input class="easyui-textbox" id="packages_search_headernote" style="width:150px"> <a href="javascript:void(0)" title="Cari" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchpackages()"></a></td>
		</tr>
	</table>
</div>

<div id="dlg-packages" class="easyui-dialog" title="Paket SO" style="width:auto;height:600px" closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save') ?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormpackages();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel') ?>',
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
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('docdate') ?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('packagename') ?></td>
				<td><input class="easyui-textbox" name="packagename" data-options="required:true,multiline:true" style="width:250px; height:100px"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('packagetype') ?></td>
				<td><select class="easyui-combobox" id="packagetype" name="packagetype" data-options="
                    required:'true', 
                    panelHeight:'auto',
                    onClick:function(record) {
                        console.log(record);
                        //setTimeout(function(){
                            gettype();
                            if(record.value==1) {
                                
                                jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/packages/cancelmulticustomer') ?>',
                                'data':{
                                    'customerid':$('#customerid').val(),
                                    'packageid':$('#packageid').val(),
                                    'deletemulti':1,
																		'all':1,
                                    },
                                'type':'post','dataType':'json',
                                'success':function(data) {
                                    if(data.status=='success') {
                                        $('#customerid').val('');
                                        $('#dcustomerix').hide();
                                        $('#customerix').tagbox('setValue','');
                                    }
                                    else {
                                        show('Message','error');
                                    }
                                },
                                'cache':false});
                    
                                jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/packages/cancelmulticompany') ?>',
                                'data':{
                                    'companyid':$('#companyid').val(),
                                    'packageid':$('#packageid').val(),
                                    'deletemulti':1,
																		'all':1,
                                    },
                                'type':'post','dataType':'json',
                                'success':function(data) {
                                    if(data.status=='success') {
                                        $('#dcompanyix').hide();
                                        $('#companyid').val('');
                                        $('#companyix').tagbox('setValue','');
                                    }
                                    else {
                                        show('Message','error');
                                    }
                                },
                                'cache':false});
                            }
                            if(record.value==2) {                                
                                jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/packages/cancelmulticustomer') ?>',
                                'data':{
                                        'customerid':$('#customerid').val(),
                                        'packageid':$('#packageid').val(),
                                        'deletemulti':1,
                                    },
                                    'type':'post','dataType':'json',
                                    'success':function(data) {
                                    if(data.status=='success') {
                                        console.log('success di pkgtype');
                                        $('#customerid').val('');
                                        $('#customerix').tagbox('setValue','');
                                    }
                                    else {
                                        show('Message','error');
                                    }
                                },
                                'cache':false});
                            }
                            if(record.value==3) {
                                
                                jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/packages/cancelmulticompany') ?>',
                                'data':{
                                    'companyid':$('#companyid').val(),
                                    'packageid':$('#packageid').val(),
                                    'deletemulti':1,
                                    },
                                'type':'post','dataType':'json',
                                'success':function(data) {
                                    if(data.status=='success') {
                                        console.log('success di pkgtype');
                                        $('#companyid').val('');
                                        $('#companyix').tagbox('setValue','');
                                    }
                                    else {
                                        show('Message','error');
                                    }
                                },
                                'cache':false});
                            }
                        //},0);
                        }
                    " style="width:220px">
						<option value="1">All Customer</option>
						<option value="2">Pilih Perusahaan</option>
						<option value="3">Pilih Customer</option>
						<option value="4">Pilih Customer & Perusahaan</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="display:none" id="tcompanyid"><?php echo GetCatalog('company') ?></td>
				<td>
					<div id="dcompanyid" style="display:none">
						<select id="multicompanyid" name="multicompanyid" class="easyui-combogrid" style="width:250px; display:none" data-options="
								panelWidth: '500px',
								required: false,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
                                //multiple:true,
								method: 'get',
                                //selectOnCheck: true,
                                //checkOnSelect: true,
                                /*
                                onCheck: function(index,row){
                                    row.final = true;
                                    console.log('test');
                                    console.log(this.value);
                                },
                                onUncheck: function(index,row){
                                    row.final = false;
                                    console.log('untest');
                                },
                                */
                                onBeforeLoad: function(param) {
                                    param.packageid = $('#packageid').val();
                                },
								url:'<?php echo Yii::app()->createUrl('order/packages/getCompany') ?>',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true,
                                onSelect: function(index,row) {
                                    //setvaluecompany(row.companyid,row.companyname);

                                    console.log(row);
                                }
						">
						</select>
						<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="setcompany()">Set Perusahaan</a>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<div id="dcompanyix" style="display: none;">
						<input class="easyui-tagbox" style="width:350px; display:none;" id="companyix" name="companyix" data-options="
                    onRemoveTag:function(value) {
                        var ixcompany = $('#companyid').val();
                        //console.log(value);
                        jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/packages/cancelmulticompany') ?>',
                        'data':{
                            'companyid':value,
                            'packageid':$('#packageid').val(),
                            },
                        'type':'post','dataType':'json',
                        'success':function(data) {
                            if(data.status=='success') {
                                $('#companyix').tagbox('setValues',data.companyname);
                                $('#companyid').val(data.multicompany);
                            }
                            else {
                                show('Message','error');
                            }
                        },
                        'cache':false});
                    }
                " />
					</div>
					<input type="hidden" name="companyid" id="companyid" value="" />
				</td>
			</tr>
</div>
<tr>
	<td style="display:none" id="tcustomerid"><?php echo GetCatalog('customer') ?></td>
	<td>
		<div id="dcustomerid" style="display:none">
			<select class="easyui-combogrid" id="multicustomerid" name="multicustomerid" style="width:250px; display:none;" data-options="
								panelWidth: '500px',
								idField: 'addressbookid',
								textField: 'fullname',
								pagination:true,
                                required: false,
								mode:'remote',
                                //multiple:true,
								method: 'get',
                                onBeforeLoad: function(param) {
                                    param.packageid = $('#packageid').val();
                                },
								url:'<?php echo Yii::app()->createUrl('order/packages/getCustomer'); ?>',
								columns: [[
										{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
										{field:'currentlimit',title:'<?php echo GetCatalog('currentarlimit') ?>'},
										{field:'creditlimit',title:'<?php echo GetCatalog('creditlimit') ?>'},
										{field:'overdue',title:'<?php echo GetCatalog('overdue') ?>'},
								]],
								fitColumns: true,
                                /*
                                onSelect: function(index,row) {
                                    setvaluecustomer(row.customerid,row.fullname);
                                    console.log(row.fullname);
                                }
                                */             
						">
			</select>
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="setcustomer()">Set Customer</a>
	</td>
</tr>
<tr>
	<td></td>
	<td>
		<div id="dcustomerix" style="display: none;">
			<input class="easyui-tagbox" style="width:350px;" id="customerix" name="customerix" data-options="
                    onRemoveTag:function(value) {
                        var ixcustomer = $('#customerid').val();
                        //console.log(value);
                        jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('order/packages/cancelmulticustomer') ?>',
                        'data':{
                            'customerid':value,
                            'packageid':$('#packageid').val(),
                            },
                        'type':'post','dataType':'json',
                        'success':function(data) {
                            if(data.status=='success') {
                                console.log('success di remove tag');
                                $('#customerix').tagbox('setValues',data.fullname);
                                $('#customerid').val(data.multicustomer);
                            }
                            else {
                                show('Message','error');
                            }
                        },
                        'cache':false});
                    }
                " />
		</div>
		<input type="hidden" name="customerid" id="customerid" value="" />
	</td>
</tr>
</div>
<tr>
	<td><?php echo GetCatalog('paymentmethod') ?></td>
	<td><select class="easyui-combogrid" id="paymentmethodid" name="paymentmethodid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'paymentmethodid',
								required: true,
                                readonly: false,
								textField: 'paycode',
								pagination:true,
								mode:'remote',
								method: 'get',
								url:'<?php echo Yii::app()->createUrl('accounting/paymentmethod/index', array('grid' => true, 'combo' => true)) ?>',
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
	<td><?php echo GetCatalog('startdate') ?></td>
	<td><input class="easyui-datebox" type="text" id="startdate" name="startdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
</tr>
<tr>
	<td><?php echo GetCatalog('enddate') ?></td>
	<td><input class="easyui-datebox" type="text" id="enddate" name="enddate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
</tr>
<tr>
	<td><?php echo GetCatalog('headernote') ?></td>
	<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true" style="width:300px;height:100px"></input></td>
</tr>
</table>
</form>
<div class="easyui-tabs" style="width:auto;height:800px">
	<div title="Detail" style="padding:5px">
		<table id="dg-packagedetail" style="width:100%;height:400px">
		</table>
		<div id="tb-packagedetail">
			<a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-packagedetail').edatagrid('addRow')"></a>
			<a href="javascript:void(0)" title="Simpan" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-packagedetail').edatagrid('saveRow')"></a>
			<a href="javascript:void(0)" title="Kembali" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-packagedetail').edatagrid('cancelRow')"></a>
			<a href="javascript:void(0)" title="hapus" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-packagedetail').edatagrid('destroyRow')"></a>
		</div>
		<table id="dg-packagedisc" style="width:100%;height:400px">
		</table>
		<div id="tb-packagedisc">
			<a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-packagedisc').edatagrid('addRow')"></a>
			<a href="javascript:void(0)" title="Simpan" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-packagedisc').edatagrid('saveRow')"></a>
			<a href="javascript:void(0)" title="Kembali" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-packagedisc').edatagrid('cancelRow')"></a>
			<a href="javascript:void(0)" title="hapus" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-packagedisc').edatagrid('destroyRow')"></a>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#packagetype').combobox({
			onChange: function(newValue, oldValue) {
				//console.log(newValue);
				gettype();
			}
		})
	});

	var rowIdxcp = 0;
	var rowIdxcs = 0;

	function setcompany() {
		jQuery.ajax({
			'url': '<?php echo Yii::app()->createUrl('order/packages/getmulticompany') ?>',
			'data': {
				'companyid': $('#multicompanyid').combogrid('getValues'),
				'packageid': $('#packageid').val(),
				'packagetype': $('#packagetype').combobox('getValue'),
			},
			'type': 'post',
			'dataType': 'json',
			'success': function(data) {
				if (data.status == 'success') {
					$('#multicompanyid').combogrid('setValue', '');
					$('#companyix').tagbox('setValues', data.companyname);
					$('#companyid').val(data.multicompany);
				} else {
					show('Message', data.msg);
				}
			},
			'cache': false
		});
	}

	function setcustomer() {
		jQuery.ajax({
			'url': '<?php echo Yii::app()->createUrl('order/packages/getmulticustomer') ?>',
			'data': {
				'customerid': $('#multicustomerid').combogrid('getValues'),
				'packageid': $('#packageid').val(),
				'packagetype': $('#packagetype').combobox('getValue'),
			},
			'type': 'post',
			'dataType': 'json',
			'success': function(data) {
				if (data.status == 'success') {
					$('#multicustomerid').combogrid('setValue', '');
					$('#customerix').tagbox('setValues', data.fullname);
					$('#customerid').val(data.multicustomer);
				} else {
					show('Message', 'error');
				}
			},
			'cache': false
		});
	}

	function gettype() {
		let type = $('#packagetype').combobox('getValue');
		if (type != '' && type != 0) {
			if (type == 2) {
				// console.log('2');
				$('#dcompanyid').show();
				$('#tcompanyid').show();
				$('#dcompanyix').show();
				$('#dcustomerid').hide();
				$('#tcustomerid').hide();
				$('#dcustomerix').hide();
				$('#customerix').tagbox('clear');
				$('#customerid').val('');
			} else if (type == 3) {
				// console.log('3');
				$('#dcustomerid').show();
				$('#tcustomerid').show();
				$('#dcustomerix').show();
				$('#dcompanyid').hide();
				$('#tcompanyid').hide();
				$('#dcompanyix').hide();
				$('#companyix').tagbox('clear');
				$('#companyid').val('');
			} else if (type == 4) {
				// console.log('4');
				$('#dcustomerid').show();
				$('#tcustomerid').show();
				$('#dcustomerix').show();
				$('#dcompanyid').show();
				$('#tcompanyid').show();
				$('#dcompanyix').show();
			} else {
				// console.log('?');
				$('#dcustomerid').hide();
				$('#tcustomerid').hide();
				$('#dcustomerix').hide();
				$('#dcompanyid').hide();
				$('#tcompanyid').hide();
				$('#dcompanyix').hide();
				$('#customerix').tagbox('clear');
				$('#customerid').val('');
				$('#companyix').tagbox('clear');
				$('#companyid').val('');
			}
		} else {
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
			success: function(data) {
				show('Pesan', data.msg);
				$('#dg-packages').edatagrid('reload');
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

	$('#packages_search_packageid').textbox({
		inputEvents: $.extend({}, $.fn.textbox.defaults.inputEvents, {
			keyup: function(e) {
				if (e.keyCode == 13) {
					searchpackages();
				}
			}
		})
	});

	$('#packages_search_companyname').textbox({
		inputEvents: $.extend({}, $.fn.textbox.defaults.inputEvents, {
			keyup: function(e) {
				if (e.keyCode == 13) {
					searchpackages();
				}
			}
		})
	});

	$('#packages_search_docno').textbox({
		inputEvents: $.extend({}, $.fn.textbox.defaults.inputEvents, {
			keyup: function(e) {
				if (e.keyCode == 13) {
					searchpackages();
				}
			}
		})
	});
	$('#packages_search_customer').textbox({
		inputEvents: $.extend({}, $.fn.textbox.defaults.inputEvents, {
			keyup: function(e) {
				if (e.keyCode == 13) {
					searchpackages();
				}
			}
		})
	});
	$('#packages_search_packagename').textbox({
		inputEvents: $.extend({}, $.fn.textbox.defaults.inputEvents, {
			keyup: function(e) {
				if (e.keyCode == 13) {
					searchpackages();
				}
			}
		})
	});
	$('#packages_search_headernote').textbox({
		inputEvents: $.extend({}, $.fn.textbox.defaults.inputEvents, {
			keyup: function(e) {
				if (e.keyCode == 13) {
					searchpackages();
				}
			}
		})
	});
	$('#dg-packages').datagrid({
		singleSelect: false,
		toolbar: '#tb-packages',
		pagination: true,
		fitColumns: true,
		ctrlSelect: true,
		autoRowHeight: true,
		onDblClickRow: function(index, row) {
			editpackages(index);
		},
		view: detailview,
		detailFormatter: function(index, row) {
			return '<div style="padding:2px"><table class="ddv-packagecompany"></table><table class="ddv-packagecustomer"></table><table class="ddv-packagedetail"></table><table class="ddv-packagedisc"></table></div>';
		},
		onExpandRow: function(index, row) {
			var ddvpackagecompany = $(this).datagrid('getRowDetail', index).find('table.ddv-packagecompany');
			var ddvpackagecustomer = $(this).datagrid('getRowDetail', index).find('table.ddv-packagecustomer');
			var ddvpackagedetail = $(this).datagrid('getRowDetail', index).find('table.ddv-packagedetail');
			var ddvpackagedisc = $(this).datagrid('getRowDetail', index).find('table.ddv-packagedisc');
			ddvpackagecompany.datagrid({
				url: '<?php echo $this->createUrl('packages/indexdatacomp', array('grid' => true)) ?>?id=' + row.packageid,
				fitColumns: true,
				singleSelect: true,
				rownumbers: true,
				loadMsg: '',
				height: 'auto',
				width: 'auto',
				showFooter: true,
				pagination: true,
				columns: [
					[{
							field: '_expander',
							expander: true,
							width: 24,
							fixed: true
						},
						{
							field: 'companycode',
							title: '<?php echo GetCatalog('companycode') ?>',
							align: 'left',
							width: '80px'
						},
						{
							field: 'companyname',
							title: '<?php echo GetCatalog('companyname') ?>',
							align: 'left',
							width: '180px'
						},
					]
				],
			});
			ddvpackagecustomer.datagrid({
				url: '<?php echo $this->createUrl('packages/indexdatacust', array('grid' => true)) ?>?id=' + row.packageid,
				fitColumns: true,
				singleSelect: true,
				rownumbers: true,
				loadMsg: '',
				height: 'auto',
				width: 'auto',
				showFooter: true,
				pagination: true,
				columns: [
					[{
							field: '_expander',
							expander: true,
							width: 24,
							fixed: true
						},
						{
							field: 'fullname',
							title: '<?php echo GetCatalog('customer') ?>',
							align: 'left',
							width: '250px'
						},
						{
							field: 'areaname',
							title: '<?php echo GetCatalog('salesarea') ?>',
							align: 'left',
							width: '180px'
						},
						{
							field: 'groupname',
							title: '<?php echo GetCatalog('groupcustomer') ?>',
							width: '100px'
						},
						{
							field: 'gradedesc',
							title: '<?php echo GetCatalog('grade') ?>',
							align: 'left',
							width: '100px'
						},
					]
				],
			});
			ddvpackagedetail.datagrid({
				url: '<?php echo $this->createUrl('packages/indexdetail', array('grid' => true)) ?>?id=' + row.packageid,
				fitColumns: true,
				singleSelect: true,
				rownumbers: true,
				loadMsg: '',
				height: 'auto',
				width: 'auto',
				showFooter: true,
				pagination: true,
				columns: [
					[{
							field: '_expander',
							expander: true,
							width: 24,
							fixed: true
						},
						{
							field: 'productname',
							title: '<?php echo GetCatalog('productname') ?>',
							width: '350px'
						},
						{
							field: 'qty',
							title: '<?php echo GetCatalog('qty') ?>',
							align: 'right',
							width: '80px'
						},
						{
							field: 'qtystock',
							title: '<?php echo GetCatalog('qtystock') ?>',
							align: 'right',
							width: '80px'
						},
						{
							field: 'uomcode',
							title: '<?php echo GetCatalog('uomcode') ?>',
							width: '100px'
						},
						{
							field: 'price',
							title: '<?php echo GetCatalog('price') ?>',
							align: 'right',
							width: '150px',
							hidden: <?php echo getUserObjectValues('pricepkg') == 1 ? 'false' : 'true' ?>
						},
						{
							field: 'isbonus',
							title: '<?php echo GetCatalog('isbonus?') ?>',
							formatter: function(value, row, index) {
								if (value == 1) {
									return "<img src=\"<?php echo Yii::app()->request->baseUrl ?>/images/ok.png\"></img>";
								} else {
									return '';
								}
							}
						},
					]
				],
			});
			ddvpackagedisc.datagrid({
				url: '<?php echo $this->createUrl('packages/indexdisc', array('grid' => true)) ?>?id=' + row.packageid,
				fitColumns: true,
				singleSelect: true,
				rownumbers: true,
				loadMsg: '',
				height: 'auto',
				width: 'auto',
				showFooter: true,
				columns: [
					[{
							field: '_expander',
							expander: true,
							width: 24,
							fixed: true
						},
						{
							field: 'packagediscid',
							title: ''
						},
						{
							field: <?php echo getUserObjectValues('pricepkg') == 1 ? "'discvalue'" : "'discvalue1'" ?>,
							title: '<?php echo GetCatalog('discvalue') ?>',
							align: 'right',
							width: '100px'
						},
					]
				],
				onResize: function() {
					$('#dg-packages').datagrid('fixDetailRowHeight', index);
				},
				onLoadSuccess: function() {
					setTimeout(function() {
						$('#dg-packages').datagrid('fixDetailRowHeight', index);
					}, 0);
				}
			});
			$('#dg-packages').datagrid('fixDetailRowHeight', index);
		},
		url: '<?php echo $this->createUrl('packages/index', array('grid' => true)) ?>',
		onSuccess: function(index, row) {
			show('Message', row.msg);
			$('#dg-packages').edatagrid('reload');
		},
		onError: function(index, row) {
			show('Message', row.msg);
		},
		idField: 'packageid',
		editing: false,
		columns: [
			[{
					field: 'packageid',
					title: '<?php echo GetCatalog('packageid') ?>',
					sortable: true,
					width: '50px',
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'docno',
					title: '<?php echo GetCatalog('docno') ?>',
					sortable: true,
					width: '100px',
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'docdate',
					title: '<?php echo GetCatalog('docdate') ?>',
					sortable: true,
					width: '80px',
					formatter: function(value, row, index) {
						return value;
					}
				},
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
					field: 'packagename',
					title: '<?php echo GetCatalog('packagename') ?>',
					sortable: true,
					width: '125px',
					formatter: function(value, row, index) {
						return value;
					}
				},

				{
					field: 'packagetype',
					title: '<?php echo GetCatalog('packagetype') ?>',
					sortable: true,
					width: '250px',
					formatter: function(value, row, index) {
						return row.packagetypename;
					}
				},
				{
					field: 'paymentmethodid',
					title: '<?php echo GetCatalog('paymentmethod') ?>',
					sortable: true,
					width: '150px',
					formatter: function(value, row, index) {
						return row.paycode;
					}
				},
				{
					field: 'startdate',
					title: '<?php echo GetCatalog('startdate') ?>',
					sortable: true,
					width: '80px',
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'enddate',
					title: '<?php echo GetCatalog('enddate') ?>',
					sortable: true,
					width: '80px',
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'headernote',
					title: '<?php echo GetCatalog('headernote') ?>',
					sortable: true,
					width: '250px',
					formatter: function(value, row, index) {
						return row.headernote;
					}
				},
				{
					field: 'recordstatus',
					title: '<?php echo GetCatalog('recordstatus') ?>',
					sortable: true,
					formatter: function(value, row, index) {
						return row.statusname;
					}
				},
			]
		]
	});

	function searchpackages() {
		$('#dg-packages').edatagrid('load', {
			packageid: $('#packages_search_packageid').val(),
			companyname: $('#packages_search_companyname').val(),
			docno: $('#packages_search_docno').val(),
			customer: $('#packages_search_customer').val(),
			packagename: $('#packages_search_packagename').val(),
			headernote: $('#packages_search_headernote').val(),
		});
	};

	function getDataPackage($id) {
		jQuery.ajax({
			'url': '<?php echo $this->createUrl('packages/getDataPackage') ?>',
			'data': {
				'id': $id
			},
			'type': 'post',
			'dataType': 'json',
			'success': function(data) {
				//$('#ixcompany').textbox('setValue',data.companies);
				$('#customerid').val(data.customerid);
				$('#companyid').val(data.companyid);
				if (data.companies != null) {
					$('#companyix').tagbox('setValues', data.companies);
				}
				if (data.customers != null) {
					$('#customerix').tagbox('setValues', data.customers);
				}
				//console.log(data);
			}
		})
	}

	function approvepackages() {
		var ss = [];
		var rows = $('#dg-packages').edatagrid('getSelections');
		for (var i = 0; i < rows.length; i++) {
			var row = rows[i];
			ss.push(row.packageid);
		}
		jQuery.ajax({
			'url': '<?php echo $this->createUrl('packages/approve') ?>',
			'data': {
				'id': ss
			},
			'type': 'post',
			'dataType': 'json',
			'success': function(data) {
				show('Message', data.msg);
				$('#dg-packages').edatagrid('reload');
			},
			'cache': false
		});
	};

	function cancelpackages() {
		var ss = [];
		var rows = $('#dg-packages').edatagrid('getSelections');
		for (var i = 0; i < rows.length; i++) {
			var row = rows[i];
			ss.push(row.packageid);
		}
		jQuery.ajax({
			'url': '<?php echo $this->createUrl('packages/delete') ?>',
			'data': {
				'id': ss
			},
			'type': 'post',
			'dataType': 'json',
			'success': function(data) {
				show('Message', data.msg);
				$('#dg-packages').edatagrid('reload');
			},
			'cache': false
		});
	};

	function purgepackages() {
		var ss = [];
		var rows = $('#dg-packages').edatagrid('getSelections');
		for (var i = 0; i < rows.length; i++) {
			var row = rows[i];
			ss.push(row.packageid);
		}
		jQuery.ajax({
			'url': '<?php echo $this->createUrl('packages/purge') ?>',
			'data': {
				'id': ss
			},
			'type': 'post',
			'dataType': 'json',
			'success': function(data) {
				show('Message', data.msg);
				$('#dg-packages').edatagrid('reload');
			},
			'cache': false
		});
	};

	function downpdfpackages() {
		var ss = [];
		var rows = $('#dg-packages').edatagrid('getSelections');
		for (var i = 0; i < rows.length; i++) {
			var row = rows[i];
			ss.push(row.packageid);
		}
		window.open('<?php echo $this->createUrl('packages/downpdf') ?>?id=' + ss);
	};

	function downxlspackages() {
		var ss = [];
		var rows = $('#dg-packages').edatagrid('getSelections');
		for (var i = 0; i < rows.length; i++) {
			var row = rows[i];
			ss.push(row.packageid);
		}
		window.open('<?php echo $this->createUrl('packages/downxls') ?>?id=' + ss);
	}

	function addpackages() {
		$('#dlg-packages').dialog('open');
		$('#ff-packages-modif').form('clear');
		$('#ff-packages-modif').form('load', '<?php echo $this->createUrl('packages/GetData') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
	};

	function editpackages($i) {
		var row = $('#dg-packages').datagrid('getSelected');
		var docmax = <?php echo CheckDoc('apppkg') ?>;
		var docstatus = row.recordstatus;
		if (row) {
			if (docstatus == docmax) {
				show('Message', '<?php echo GetCatalog('docreachmaxstatus') ?>');
			} else {
				$('#dlg-packages').dialog('open');
				$('#ff-packages-modif').form('load', row);
				gettype();
				let pkgid = row.packageid;
				getDataPackage(pkgid);
			}
		} else {
			show('Message', 'chooseone');
		}
	};

	function submitFormpackages() {
		$('#ff-packages-modif').form('submit', {
			url: '<?php echo $this->createUrl('packages/save') ?>',
			onSubmit: function() {
				return $(this).form('enableValidation').form('validate');
			},
			success: function(data) {
				var data = eval('(' + data + ')'); // change the JSON string to javascript object
				show('Pesan', data.msg)
				if (data.isError == false) {
					$('#dg-packages').datagrid('reload');
					$('#dlg-packages').dialog('close');
				}
			}
		});
	};

	function clearFormpackages() {
		$('#ff-packages-modif').form('clear');
	};

	function cancelFormpackages() {
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
		}
	});

	function dateformatter(date) {
		var y = date.getFullYear();
		var m = date.getMonth() + 1;
		var d = date.getDate();
		return (d < 10 ? ('0' + d) : d) + '-' + (m < 10 ? ('0' + m) : m) + '-' + y;
	}

	function dateparser(s) {
		if (!s) return new Date();
		var ss = (s.split('-'));
		var y = parseInt(ss[2], 10);
		var m = parseInt(ss[1], 10);
		var d = parseInt(ss[0], 10);
		if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
			return new Date(y, m - 1, d);
		} else {
			return new Date();
		}
	}

	$('#dg-packagedetail').edatagrid({
		iconCls: 'icon-edit',
		singleSelect: true,
		idField: 'packagedetailid',
		editing: true,
		toolbar: '#tb-packagedetail',
		fitColumn: true,
		pagination: true,
		showFooter: true,
		url: '<?php echo $this->createUrl('packages/searchdetail', array('grid' => true)) ?>',
		saveUrl: '<?php echo $this->createUrl('packages/savedetail', array('grid' => true)) ?>',
		updateUrl: '<?php echo $this->createUrl('packages/savedetail', array('grid' => true)) ?>',
		destroyUrl: '<?php echo $this->createUrl('packages/purgedetail', array('grid' => true)) ?>',
		onSuccess: function(index, row) {
			show('Pesan', row.msg);
			$('#dg-packagedetail').edatagrid('reload');
			$('#dg-packagedisc').edatagrid('reload');
		},
		onError: function(index, row) {
			show('Pesan', row.msg);
		},
		onBeforeEdit: function(index, row) {
			row.packageid = $('#packageid').val();
		},
		columns: [
			[{
					field: 'packageid',
					title: '<?php echo GetCatalog('packageid') ?>',
					hidden: true,
					sortable: true,
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'packagedetailid',
					title: '<?php echo GetCatalog('packagedetailid') ?>',
					hidden: true,
					sortable: true,
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'productname',
					title: '<?php echo GetCatalog('product') ?>',
					width: '400px',
					editor: {
						type: 'combogrid',
						options: {
							panelWidth: '900px',
							mode: 'remote',
							method: 'get',
							idField: 'productid',
							textField: 'productname',
							url: '<?php echo Yii::app()->createUrl('common/product/index', array('grid' => true)) ?>',
							fitColumns: true,
							required: true,
							pagination: true,
							queryParams: {
								trxplant: true
							},
							loadMsg: '<?php echo GetCatalog('pleasewait') ?>',
							onChange: function(newValue, oldValue) {
								if ((newValue !== oldValue) && (newValue !== '')) {
									//let sotype = $("#sotype").combobox('getValue');
									var tr = $(this).closest('tr.datagrid-row');
									var index = parseInt(tr.attr('datagrid-row-index'));
									var productid = $("#dg-packagedetail").datagrid("getEditor", {
										index: index,
										field: "productname"
									});
									var uomid = $("#dg-packagedetail").datagrid("getEditor", {
										index: index,
										field: "uomcode"
									});
									var price = $("#dg-packagedetail").datagrid("getEditor", {
										index: index,
										field: "price"
									});

									jQuery.ajax({
										'url': '<?php echo Yii::app()->createUrl('common/productplant/getdatasales') ?>',
										//'data':{'productid':$(productid.target).combogrid("getValue"),'companyid':1},
										'data': {
											'productid': $(productid.target).combogrid("getValue"),
											'companyid': '<?php echo getuserobjectvalues('company') ?>'
										},
										'type': 'post',
										'dataType': 'json',
										'success': function(data) {
											$(uomid.target).combogrid('setValue', data.uomid);
										},
										'cache': false
									});

									jQuery.ajax({
										'url': '<?php echo Yii::app()->createUrl('common/productsales/generatedata') ?>',
										'data': {
											'productid': $(productid.target).combogrid("getValue"),
											'package': 1
										},
										'type': 'post',
										'dataType': 'json',
										'success': function(data) {
											$(price.target).numberbox('setValue', data.price);
										},
										'cache': false
									});

								}
							},
							columns: [
								[{
										field: 'productid',
										title: '<?php echo GetCatalog('productid') ?>'
									},
									{
										field: 'productname',
										title: '<?php echo GetCatalog('productname') ?>'
									},
									{
										field: 'barcode',
										title: '<?php echo GetCatalog('barcode') ?>'
									},
								]
							]
						}
					},
					sortable: true,
					formatter: function(value, row, index) {
						return row.productname;
					}
				},
				{
					field: 'qty',
					title: '<?php echo GetCatalog('qty') ?>',
					editor: {
						type: 'numberbox',
						options: {
							precision: 4,
							required: true,
							decimalSeparator: ',',
							groupSeparator: '.'
						}
					},
					width: '100px',
					sortable: true,
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'uomcode',
					title: '<?php echo GetCatalog('uom') ?>',
					editor: {
						type: 'combogrid',
						options: {
							panelWidth: '500px',
							mode: 'remote',
							method: 'get',
							idField: 'unitofmeasureid',
							textField: 'uomcode',
							url: '<?php echo Yii::app()->createUrl('common/unitofmeasure/index', array('grid' => true)) ?>',
							fitColumns: true,
							pagination: true,
							required: true,
							readonly: true,
							queryParams: {
								combo: true
							},
							loadMsg: '<?php echo GetCatalog('pleasewait') ?>',
							columns: [
								[{
										field: 'unitofmeasureid',
										title: '<?php echo GetCatalog('unitofmeasureid') ?>',
										width: '50px'
									},
									{
										field: 'uomcode',
										title: '<?php echo GetCatalog('uomcode') ?>',
										width: '200px'
									},
								]
							]
						}
					},
					width: '150px',
					sortable: true,
					formatter: function(value, row, index) {
						return row.uomcode;
					}
				},
				{
					field: 'price',
					title: '<?php echo GetCatalog('price') ?>',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision: 4,
							decimalSeparator: ',',
							groupSeparator: '.',
							required: true,
						}
					},
					width: '150px',
					required: true,
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'isbonus',
					title: '<?php echo getCatalog('isbonus?') ?>',
					hidden: true,
					width: '80px',
					align: 'center',
					editor: {
						type: 'checkbox',
						options: {
							on: '1',
							off: '0'
						}
					},
					sortable: true,
					formatter: function(value, row, index) {
						if (value == 1) {
							return '<img src="<?php echo Yii::app()->request->baseUrl ?>/images/ok.png"></img>';
						} else {
							return '';
						}
					}
				}
			]
		]
	});

	$('#dg-packagedisc').edatagrid({
		iconCls: 'icon-edit',
		singleSelect: true,
		idField: 'packagediscid',
		editing: true,
		toolbar: '#tb-packagedisc',
		fitColumn: true,
		pagination: true,
		showFooter: true,
		url: '<?php echo $this->createUrl('packages/searchdisc', array('grid' => true)) ?>',
		saveUrl: '<?php echo $this->createUrl('packages/savedisc', array('grid' => true)) ?>',
		updateUrl: '<?php echo $this->createUrl('packages/savedisc', array('grid' => true)) ?>',
		destroyUrl: '<?php echo $this->createUrl('packages/purgedisc', array('grid' => true)) ?>',
		onSuccess: function(index, row) {
			show('Pesan', row.msg);
			$('#dg-packagedisc').edatagrid('reload');
		},
		onError: function(index, row) {
			show('Pesan', row.msg);
		},
		onBeforeSave: function(index) {
			var row = $('#dg-packagedisc').edatagrid('getSelected');
			if (row) {
				row.packageid = $('#packageid').val()
			}
		},
		onBeforeSave: function(index) {
			var row = $('#dg-packagedisc').edatagrid('getSelected');
			if (row) {
				row.packageid = $('#packageid').val()
			}
		},
		columns: [
			[{
					field: 'packageid',
					title: '<?php echo GetCatalog('packageid') ?>',
					hidden: true,
					sortable: true,
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'packagediscid',
					title: '<?php echo GetCatalog('packagediscid') ?>',
					hidden: true,
					sortable: true,
					formatter: function(value, row, index) {
						return value;
					}
				},
				{
					field: 'discvalue',
					title: '<?php echo GetCatalog('discvalue') ?>',
					editor: {
						type: 'numberbox',
						options: {
							precision: 6,
							required: true,
							decimalSeparator: ',',
							groupSeparator: '.'
						}
					},
					sortable: true,
					formatter: function(value, row, index) {
						return value;
					}
				}
			]
		]
	});
</script>