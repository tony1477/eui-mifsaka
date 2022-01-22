<div id="tb-repsales">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
 <table>
 <tr>
	<td><?php echo GetCatalog('reporttype')?><td>
	<td><select class="easyui-combobox" id="listrepsales" name="listrepsales" data-options="required:true" style="width:500px;">
		<option value="1">Rincian Penjualan Per Dokumen</option>
		<option value="2">Rekap Penjualan Per Dokumen</option>
		<option value="3">Rekap Penjualan Per Customer</option>		
		<option value="4">Rekap Penjualan Per Sales</option>
		<option value="5">Rekap Penjualan Per Barang</option>
		<option value="6">Rekap Penjualan Per Area</option>
		<option value="7">Rekap Penjualan Per Customer Per Barang (Total)</option>		
		<option value="8">Rekap Penjualan Per Customer Per Barang (Rincian)</option>		
		<option value="9">Rekap Penjualan Per Sales Per Barang (Total)</option>		
		<option value="10">Rekap Penjualan Per Sales Per Barang (Rincian)</option>		
		<option value="11">Rekap Penjualan Per Area Per Barang (Total)</option>		
		<option value="12">Rekap Penjualan Per Area Per Barang (Rincian)</option>		
		<option value="13">Rincian Retur Penjualan Per Dokumen</option>
		<option value="14">Rekap Retur Penjualan Per Dokumen</option>
		<option value="15">Rekap Retur Penjualan Per Customer</option>		
		<option value="16">Rekap Retur Penjualan Per Sales</option>
		<option value="17">Rekap Retur Penjualan Per Barang</option>
		<option value="18">Rekap Retur Penjualan Per Area</option>		
		<option value="19">Rekap Retur Penjualan Per Customer Per Barang (Total)</option>		
		<option value="20">Rekap Retur Penjualan Per Customer Per Barang (Rincian)</option>		
		<option value="21">Rekap Retur Penjualan Per Sales Per Barang (Total)</option>		
		<option value="22">Rekap Retur Penjualan Per Sales Per Barang (Rincian)</option>		
		<option value="23">Rekap Retur Penjualan Per Area Per Barang (Total)</option>		
		<option value="24">Rekap Retur Penjualan Per Area Per Barang (Rincian)</option>
		<option value="25">Rincian Penjualan - Retur Per Dokumen</option>
		<option value="26">Rekap Penjualan - Retur Per Dokumen</option>
		<option value="27">Rekap Penjualan - Retur Per Customer</option>		
		<option value="28">Rekap Penjualan - Retur Per Sales</option>
		<option value="29">Rekap Penjualan - Retur Per Barang</option>
		<option value="30">Rekap Penjualan - Retur Per Area</option>		
		<option value="31">Rekap Penjualan - Retur Per Customer Per Barang (Total)</option>		
		<option value="32">Rekap Penjualan - Retur Per Customer Per Barang (Rincian)</option>		
		<option value="33">Rekap Penjualan - Retur Per Sales Per Barang (Total)</option>		
		<option value="34">Rekap Penjualan - Retur Per Sales Per Barang (Rincian)</option>		
		<option value="35">Rekap Penjualan - Retur Per Area Per Barang (Total)</option>		
		<option value="36">Rekap Penjualan - Retur Per Area Per Barang (Rincian)</option>		
		<option value="37">Rincian Sales Order Per Dokumen</option>		
		<option value="38">Rincian Sales Order Outstanding</option>		
		<option value="39">Rekap Surat Jalan Belum Dibuatkan Faktur</option>		
		<option value="40">Rekap Penjualan Per Customer Per Bulan Per Tahun</option>		
		<option value="41">Rekap Retur Penjualan Per Customer Per Bulan Per Tahun</option>		
		<option value="42">Rekap Penjualan - Retur Per Customer Per Bulan Per Tahun</option>		
		<option value="43">Rekap Penjualan Per Jenis Customer Per Bulan Per Tahun</option>		
		<option value="44">Rekap Retur Penjualan Per Jenis Customer Per Bulan Per Tahun</option>		
		<option value="45">Rekap Penjualan - Retur Per Jenis Customer Per Bulan Per Tahun</option>		
		<option value="46">Rekap Total Penjualan Per Jenis Customer Per Bulan Per Tahun</option>		
		<option value="47">Rekap Total Retur Penjualan Per Jenis Customer Per Bulan Per Tahun</option>		
		<option value="48">Rekap Total Penjualan - Retur Per Jenis Customer Per Bulan Per Tahun</option>
		<option value="49">Rekap Penjualan Per Barang Per Bulan (Qty)</option>
		<option value="50">Rekap Penjualan Per Barang Per Bulan (Nilai)</option>
		<option value="51">Rekap Penjualan Per Barang Per Bulan (Nilai & Qty)</option>
		<option value="52">Rekap Penjualan - Retur Per Customer Per Barang Per Bulan Per Tahun Total Nilai</option>
		<option value="53">Rekap Penjualan - Retur Per Customer Per Barang Per Bulan Per Tahun Rincian Nilai</option>
		<option value="54">Rekap Penjualan - Retur Per Customer Per Barang Per Bulan Per Tahun Total Qty</option>
		<option value="55">Rekap Penjualan - Retur Per Customer Per Barang Per Bulan Per Tahun Rincian Qty</option>
		<option value="56">Rekap Sales Order Outstanding Per Barang</option>
		<option value="57">Sales Order yang belum Status Max</option>
    </select>		<br/></td>		
</tr>

<tr>
	<td><?php echo GetCatalog('company')?><td>

		<td><select class="easyui-combogrid" id="repsales_companyid" name="repsales_companyid" style="width:500px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								onHidePanel: function(){
									$('#slocid').combogrid('grid').datagrid({
															queryParams: {
															companyid: $('#companyid').combogrid('getValue')
															}
												});                               
									$('#slocid').combogrid('grid').datagrid('reload');
								},
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select><br/></td>
	</tr>
	
	<tr>
    <td><?php echo GetCatalog('sloc')?><td>
		<td><select class="easyui-combogrid" id="repsales_slocid" name="repsales_slocid" style="width:500px" data-options="
								panelWidth: 500,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								required:false,
								url: '<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true,'combo'=>true)) ?>',
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
    <td><?php echo GetCatalog('customer')?><td>
		<td><select class="easyui-combogrid" id="repsales_addressbookid" name="repsales_addressbookid" style="width:500px" data-options="
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
		<td><?php echo GetCatalog('sales')?><td>
		<td><select class="easyui-combogrid" id="repsales_employeeid" name="repsales_employeeid" style="width:500px" data-options="
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
				</select><br/></td>
	</tr>
	<tr>
    <td><?php echo GetCatalog('product')?><td>
		<td><select class="easyui-combogrid" id="repsales_productid" name="repsales_productid" style="width:500px" data-options="
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
    <td><?php echo GetCatalog('salesarea')?><td>
		<td><select class="easyui-combogrid" id="repsales_salesareaid" name="repsales_salesareaid" style="width:500px" data-options="
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
				</select><br/></td>
	</tr>
	<tr>
		<td><?php echo GetCatalog('date')?><td>
		<td><input class="easyui-datebox" id="repsales_startdate" name="repsales_startdate" data-options="required:true"></input>
		-
		<input class="easyui-datebox" id="repsales_enddate" name="repsales_enddate" data-options="required:true"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepsales()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsrepsales()"></a>
</tr>
<?php }?>
</div>
<script type="text/javascript">
function downpdfrepsales() {
	window.open('<?php echo $this->createUrl('reportorder/downpdf') 
?>?lro='+
		$('#listrepsales').combobox('getValue') +
		'&company='+$('#repsales_companyid').combogrid('getValue')+
		'&sloc='+$('#repsales_slocid').combogrid('getValue')+
		'&customer='+$('#repsales_addressbookid').combogrid('getValue')+
		'&sales='+$('#repsales_employeeid').combogrid('getValue')+
		'&product='+$('#repsales_productid').combogrid('getValue')+
		'&salesarea='+$('#repsales_salesareaid').combogrid('getValue')+
		'&startdate='+$('#repsales_startdate').datebox('getValue')+
		'&enddate='+$('#repsales_enddate').datebox('getValue')+
		'&per=10');

};

function downxlsrepsales() {
	window.open('<?php echo $this->createUrl('reportorder/downxls') ?>?lro='+
		$('#listrepsales').combobox('getValue') +
		'&company='+$('#repsales_companyid').combogrid('getValue')+
		'&sloc='+$('#repsales_slocid').combogrid('getValue')+
		'&customer='+$('#repsales_addressbookid').combogrid('getValue')+
		'&sales='+$('#repsales_employeeid').combogrid('getValue')+
		'&product='+$('#repsales_productid').combogrid('getValue')+
		'&salesarea='+$('#repsales_salesareaid').combogrid('getValue')+
		'&startdate='+$('#repsales_startdate').datebox('getValue')+
		'&enddate='+$('#repsales_enddate').datebox('getValue')+
		'&per=10');

};

</script>
