<div id="tb-reportacc">
<script type="text/javascript">
$(document).ready(function(){
    $('#listreportacc').on('change', function() {
      if ( this.value == '21')
      {
        $("#companyid").show();
      }
      else
      {
        $("#companyid").hide();
      }
    });
});
  </script>
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
 <table>
 <tr>
 <td><?php echo GetCatalog('reporttype')?></td>
 <td><select class="easyui-combobox" id="listreportacc" name="listreportacc" data-options="required:true" style="width:450px;">
    <option value="1">Rincian Jurnal Transaksi</option>
		<option value="2">Buku Besar</option>
		<option value="3">Neraca - Uji Coba</option>
		<option value="4">Laba (Rugi) - Uji Coba</option>
		<option value="5">Rincian Umur Piutang Cek/Giro</option>
		<option value="6">Rekap Umur Piutang Cek/Giro</option>
		<option value="7">Rincian Cek/Giro Cair - Ekstern</option>
		<option value="8">Rincian Cek/Giro Tolak - Ekstern</option>
		<option value="9">Rincian Cek/Giro Opname - Ekstern</option>
		<option value="10">Rincian Umur Hutang Cek/Giro</option>
		<option value="11">Rekap Umur Hutang Cek/Giro</option>
		<option value="12">Rincian Cek/Giro Cair - Intern</option>
		<option value="13">Rincian Cek/Giro Tolak - Intern</option>
		<option value="14">Rekap Jurnal Umum Per Dokumen Belum Status Max</option>
		<option value="15">Rekap Penerimaan Kas/Bank Per Dokumen Belum Status Max</option>
		<option value="16">Rekap Pengeluaran Kas/Bank Per Dokumen Belum Status Max</option>
		<option value="17">Rekap Cash Bank Per Dokumen Belum Status Max</option>
		<option value="18">Lampiran Neraca 1</option>
    </select>		<br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('company')?></td>
 <td><select class="easyui-combogrid" id="reportacc_companyid" name="reportacc_companyid" style="width:450px" data-options="
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
								fitColumns: true
						">
				</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('sloc')?></td>
 <td><select class="easyui-combogrid" id="reportacc_slocid" name="reportacc_slocid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/sloc/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'slocid',title:'<?php echo GetCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo GetCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo GetCatalog('description') ?>'},
								]],
								fitColumns: true
						">
						</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('materialgroup')?></td>
 <td><select class="easyui-combogrid" id="reportacc_materialgroupid" name="reportacc_materialgroupid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'materialgroupcode',
								textField: 'materialgroupcode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/materialgroup/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns:[[
									{field:'materialgroupid',title:'<?php echo GetCatalog('materialgroupid')?>',width:50},
									{field:'materialgroupcode',title:'<?php echo GetCatalog('materialgroupcode')?>',width:50},
									{field:'description',title:'<?php echo GetCatalog('description')?>',width:200},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('customer')?></td>
 <td><select class="easyui-combogrid" id="reportacc_addressbookid" name="reportacc_addressbookid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'fullname',
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/addressbook/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('product')?></td>
 <td><select class="easyui-combogrid" id="reportacc_productid" name="reportacc_productid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'productname',
								textField: 'productname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'productid',title:'<?php echo GetCatalog('productid') ?>'},
										{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('accountname')?></td>
 <td><select class="easyui-combogrid" id="reportacc_accountid" name="reportacc_accountid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'accountname',
								textField: 'accountname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'accountid',title:'<?php echo GetCatalog('accountid') ?>',sortable: true,},
										{field:'accountcode',title:'<?php echo GetCatalog('accountcode') ?>',sortable: true,},
										{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',sortable: true,},
										{field:'companyname',title:'<?php echo GetCatalog('company') ?>',sortable: true,},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('accountcode')?></td>
 <td><select class="easyui-combogrid" id="reportacc_startacccode" name="reportacc_startacccode" style="width:213px" data-options="
								panelWidth: 500,
								idField: 'accountcode',
								textField: 'accountcode',
								pagination:true,
								mode:'remote',
								sortable: true,
								url: '<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'accountid',title:'<?php echo GetCatalog('accountid') ?>',sortable: true,},
										{field:'accountcode',title:'<?php echo GetCatalog('accountcode') ?>',sortable: true,},
										{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',sortable: true,},
										{field:'companyname',title:'<?php echo GetCatalog('company') ?>',sortable: true,},
								]],
								fitColumns: true
						"> 
				</select> s/d <select class="easyui-combogrid" id="reportacc_endacccode" name="reportacc_endacccode" style="width:215px" data-options="
								panelWidth: 500,
								idField: 'accountcode',
								textField: 'accountcode',
								pagination:true,
								mode:'remote',
								sortable: true,
								url: '<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'accountid',title:'<?php echo GetCatalog('accountid') ?>',sortable: true,},
										{field:'accountcode',title:'<?php echo GetCatalog('accountcode') ?>',sortable: true,},
										{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',sortable: true,},
										{field:'companyname',title:'<?php echo GetCatalog('company') ?>',sortable: true,},
								]],
								fitColumns: true
						"> 
				</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('date')?></td>
 <td><input class="easyui-datebox" id="reportacc_startdate" name="reportacc_startdate" data-options="required:true" size="31"></input>
		-
		<input class="easyui-datebox" id="reportacc_enddate" name="reportacc_enddate" data-options="required:true" size="32"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreportacc()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsreportacc()"></a></td>
 </tr>

 
 

 </table>	
		
<?php }?>
</div>

<script type="text/javascript">
function downpdfreportacc   () {
	window.open('<?php echo $this->createUrl('reportacc/downpdf') ?>?lro='+
		$('#listreportacc').combobox('getValue') +
		'&company='+$('#reportacc_companyid').combogrid('getValue')+
		'&sloc='+$('#reportacc_slocid').combogrid('getValue')+
		'&materialgroup='+$('#reportacc_materialgroupid').combogrid('getValue')+
		'&customer='+$('#reportacc_addressbookid').combogrid('getValue')+
		'&product='+$('#reportacc_productid').combogrid('getValue')+
		'&account='+$('#reportacc_accountid').combogrid('getValue')+
		'&startacccode='+$('#reportacc_startacccode').combogrid('getValue')+
		'&endacccode='+$('#reportacc_endacccode').combogrid('getValue')+
		'&startdate='+$('#reportacc_startdate').datebox('getValue')+
		'&enddate='+$('#reportacc_enddate').datebox('getValue')+
		'&per=10');
};

function downxlsreportacc   () {
	window.open('<?php echo $this->createUrl('reportacc/downxls') 

?>?lro='+
		$('#listreportacc').combobox('getValue') +
		'&company='+$('#reportacc_companyid').combogrid('getValue')+
		'&sloc='+$('#reportacc_slocid').combogrid('getValue')+
		'&materialgroup='+$('#reportacc_materialgroupid').combogrid('getValue')+
		'&customer='+$('#reportacc_addressbookid').combogrid('getValue')+
		'&product='+$('#reportacc_productid').combogrid('getValue')+
		'&account='+$('#reportacc_accountid').combogrid('getValue')+
		'&startacccode='+$('#reportacc_startacccode').combogrid('getValue')+
		'&endacccode='+$('#reportacc_endacccode').combogrid('getValue')+
		'&startdate='+$('#reportacc_startdate').datebox('getValue')+
		'&enddate='+$('#reportacc_enddate').datebox('getValue')+
		'&per=10');
};
</script>
