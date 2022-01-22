<div id="tb-repaccrec">
	<?php

 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
 	<table>
 		<tr>
 			<td>
 				<?php echo GetCatalog('reporttype')?> 
 			</td>
 			<td>
 				<select class="easyui-combobox" id="listrepaccrec" name="listrepaccrec" data-options="required:true" style="width:500px;">
			        <option value="1">Rincian Pelunasan Piutang Per Dokumen</option>
			        <option value="2">Rekap Pelunasan Piutang Per Divisi</option>
			        <option value="15">Rincian Pelunasan Piutang Per Sales</option>
			        <option value="16">Rekap Pelunasan Piutang Per Sales</option>
			        <option value="17">Rincian Pelunasan Piutang Per Sales Per Jenis Barang</option>
			        <option value="18">Rekap Pelunasan Piutang Per Sales Per Jenis Barang</option>
			        <option value="3">Kartu Piutang Dagang</option>
			        <option value="4">Rekap Piutang Dagang Per Customer</option>
			        <option value="5">Rincian Faktur & Retur Jual Belum Lunas</option>
			        <option value="6">Rincian Umur Piutang Dagang Per Customer</option>
			        <option value="7">Rekap Umur Piutang Dagang Per Customer</option>
			        <option value="8">Rincian Faktur & Retur Jual Belum Lunas Per Sales</option>
			        <option value="9">Rekap Kontrol Piutang Customer vs Plafon</option> 
			        <option value="10">Rincian Kontrol Piutang Customer vs Plafon</option>
			        <option value="11">Konfirmasi Piutang Dagang</option>
			        <option value="12">Rekap Invoice AR Per Dokumen Belum Status Max</option>
			        <option value="13">Rekap Nota Retur Penjualan Per Dokumen Belum Status Max</option>
			        <option value="14">Rekap Pelunasan Piutang Per Dokumen Belum Status Max</option>
			        <option value="19">Rekap Penjualan VS Pelunasan Per Bulan Per Customer</option>
			        <option value="20">Rekap Piutang VS Pelunasan Per Bulan Per Customer</option>
			    </select>  
 			</td> 
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('company')?>
 			</td> 
 			<td>
 				<select class="easyui-combogrid" id="repaccrec_companyid" name="repaccrec_companyid" style="width:500px" data-options="
								panelWidth: 300,
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
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('sloc')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_slocid" name="repaccrec_slocid" style="width:500px" data-options="
								panelWidth: 300,
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
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('materialgroup')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_materialgroupid" name="repaccrec_materialgroupid" style="width:500px" data-options="
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
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('customer')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_addressbookid" name="repaccrec_addressbookid" style="width:500px" data-options="
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
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('product')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_productid" name="repaccrec_productid" style="width:500px" data-options="
								panelWidth: 300,
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
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('sales')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_employeeid" name="repaccrec_employeeid" style="width:500px" data-options="
								panelWidth: 500,
								idField: 'fullname',
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'employeeid',title:'<?php echo GetCatalog('employeeid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
								]],
								fitColumns: true
						">
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('salesarea')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repsales_salesareaid" name="repsales_salesareaid" style="width:500px" data-options="
								panelWidth: 500,
								idField: 'areaname',
								textField: 'areaname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/salesarea/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'salesareaid',title:'<?php echo GetCatalog('salesareaid') ?>'},
										{field:'areaname',title:'<?php echo GetCatalog('areaname') ?>'},
								]],
								fitColumns: true
						">
				</select>
 			</td>
 		</tr>
		<tr>
 			<td>
 				<?php echo GetCatalog('umurinvoice')?> 
 			</td>
 			<td> 				
				<input class="easyui-textbox" id="repaccrec_umurpiutang" name="repaccrec_umurpiutang" style="width:100px">
 			</td>
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('date')?>
 			</td>
 			<td>
 				<input class="easyui-datebox" id="repaccrec_startdate" name="repaccrec_startdate" data-options="required:true"></input>
				-
				<input class="easyui-datebox" id="repaccrec_enddate" name="repaccrec_enddate" data-options="required:true"></input>
				<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepaccrec()"></a>
				<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsrepaccrec()"></a>
 			</td> 
 		</tr> 
 	</table>				 
			 	
<?php }?>
</div>

<script type="text/javascript">
function downpdfrepaccrec   () {
	window.open('<?php echo $this->createUrl('repaccrec/downpdf') ?>?lro='+
		$('#listrepaccrec').combobox('getValue') +
		'&company='+$('#repaccrec_companyid').combogrid('getValue')+
		'&sloc='+$('#repaccrec_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccrec_materialgroupid').combogrid('getValue')+
		'&customer='+$('#repaccrec_addressbookid').combogrid('getValue')+
		'&product='+$('#repaccrec_productid').combogrid('getValue')+
		'&sales='+$('#repaccrec_employeeid').combogrid('getValue')+
		'&salesarea='+$('#repsales_salesareaid').combogrid('getValue')+
		'&umurpiutang='+$('#repaccrec_umurpiutang').textbox('getValue')+
		'&startdate='+$('#repaccrec_startdate').datebox('getValue')+
		'&enddate='+$('#repaccrec_enddate').datebox('getValue')+
		'&per=10');
};

function downxlsrepaccrec   () {
	window.open('<?php echo $this->createUrl('repaccrec/downxls') ?>?lro='+
		$('#listrepaccrec').combobox('getValue') +
		'&company='+$('#repaccrec_companyid').combogrid('getValue')+
		'&sloc='+$('#repaccrec_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccrec_materialgroupid').combogrid('getValue')+
		'&customer='+$('#repaccrec_addressbookid').combogrid('getValue')+
		'&product='+$('#repaccrec_productid').combogrid('getValue')+
		'&sales='+$('#repaccrec_employeeid').combogrid('getValue')+
		'&salesarea='+$('#repsales_salesareaid').combogrid('getValue')+
		'&umurpiutang='+$('#repaccrec_umurpiutang').textbox('getValue')+
		'&startdate='+$('#repaccrec_startdate').datebox('getValue')+
		'&enddate='+$('#repaccrec_enddate').datebox('getValue')+
		'&per=10');
};
</script>
