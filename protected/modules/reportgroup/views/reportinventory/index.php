<div id="tb-reportinventory">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	
    <table>
        <tr>
            <td><?php echo getCatalog('reporttype')?></td>
            <td><select class="easyui-combobox" id="listreportinventory" name="listreportinventory" data-options="required:true" style="width:350px;">
        <option value="1">Rincian Histori Barang</option>
        <option value="2">Rekap Histori Barang</option>
        <option value="3">Kartu Stok Barang</option>
        <option value="4">Kartu  Stock Barang Per Rak</option>
        <option value="5">Rekap Stok Barang</option>				
        <option value="26">Rekap Stok Barang (Ada Transaksi Keluar Masuk)</option>
        <option value="6">Rekap Stok Barang per Hari</option>
        <option value="7">Rekap Stok Barang per Rak</option>
        <option value="8">Rincian Surat Jalan Per Dokumen</option>
        <option value="9">Rekap Surat Jalan Per Barang</option>
        <option value="10">Rekap Surat Jalan Per Customer</option>
        <option value="11">Rincian Retur Jual Per Dokumen</option>
        <option value="12">Rekap Retur Jual Per Barang</option>
        <option value="13">Rekap Retur Jual Per Customer</option>
        <option value="14">Rincian Terima Barang Per Dokumen</option>
        <option value="15">Rekap Terima Barang Per Barang</option>
        <option value="16">Rekap Terima Barang Per Supplier</option>
        <option value="17">Rincian Retur Beli Per Dokumen</option>
        <option value="18">Rekap Retur Beli Per Barang</option>
        <option value="19">Rekap Retur Beli Per Supplier</option>
        <option value="20">Pendingan FPB</option>
        <option value="21">Pendingan FPP</option>
        <option value="44">Rekap Monitoring Stock</option>
        <option value="45">Rincian Monitoring Stock</option>
        <option value="22">Rincian Transfer Gudang Keluar Per Dokumen</option>
        <option value="23">Rekap Transfer Gudang Keluar Per Barang</option>
        <option value="24">Rincian Transfer Gudang Masuk Per Dokumen</option>
        <option value="25">Rekap Transfer Gudang Masuk Per Barang</option>
        <option value="27">Rekap STTB Per Dokumen Belum Status Max</option>
        <option value="28">Rekap Retur Pembelian Per Dokumen Belum Status Max</option>
        <option value="29">Rekap Surat Jalan Per Dokumen Belum Status Max</option>
        <option value="30">Rekap Retur Penjualan Per Dokumen Belum Status Max</option>
        <option value="31">Rekap Transfer Per Dokumen Belum Status Max</option>
        <option value="32">Rekap Stock Opname Per Dokumen Belum Status Max</option>
        <option value="33">Rekap Konversi Per Dokumen Belum Status Max</option>
        <option value="34">Raw Material Gudang Asal Belum Ada di Data Gudang - FPB</option>
        <option value="35">Raw Material Gudang Tujuan Belum Ada di Data Gudang - FPB</option>
        <option value="36">Rekap FPB Belum Ada Transfer Per Dokumen</option>
        <option value="37">Raw Material Belum Ada Data Gudang - Stock Opname</option>
        <option value="38">Rekap FPB Per Dokumen Belum Status Max</option>
        <option value="39">Laporan Ketersediaan Barang (MRP)</option>
        <option value="40">Laporan Material Not Moving</option>
        <option value="41">Laporan Material Slow Moving</option>
        <option value="42">Laporan Material Fast Moving</option>
        <option value="43">Laporan Harian</option>
        
    </select></td>
        </tr>
        <tr>
            <td><?php echo getCatalog('company')?></td>
            <td><select class="easyui-combogrid" id="reportinventory_companyid" name="reportinventory_companyid" style="width:350px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('admin/company/indexgroup',array('grid'=>true)) ?>',
								method: 'get',
								required: true,
								columns: [[
										{field:'companyid',title:'<?php echo getCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo getCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
        <tr>
            <td><?php echo getCatalog('sloc')?></td>
            <td><select class="easyui-combogrid" id="reportinventory_slocid" name="reportinventory_slocid" style="width:350px" data-options="
								panelWidth: 500,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/sloc/indexgroup',array('grid'=>true)) ?>',
								method: 'get',
								onBeforeLoad: function(param) {
									param.companyid = $('#reportinventory_companyid').combogrid('getValue');
								},
								columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo getCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
        <tr>
            <td><?php echo getCatalog('slocto')?></td>
            <td><select class="easyui-combogrid" id="reportinventory_sloctoid" name="reportinventory_sloctoid" style="width:350px" data-options="
								panelWidth: 500,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/sloc/indextrxcom',array('grid'=>true)) ?>',
								method: 'get',
								onBeforeLoad: function(param) {
									param.companyid = $('#reportinventory_companyid').combogrid('getValue');
								},
								columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo getCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
        <tr>
            <td><?php echo getCatalog('storagebin')?></td>
            <td><select class="easyui-combogrid" id="reportinventory_storagebinid" name="reportinventory_storagebinid" style="width:350px" data-options="
								panelWidth: 500,
								idField: 'description',
								textField: 'description',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/storagebin/index',array('grid'=>true,'single'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'storagebinid',title:'<?php echo getCatalog('storagebinid') ?>'},
										{field:'description',title:'<?php echo getCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
        <tr>
            <td><?php echo getCatalog('sales')?></td>
            <td><select class="easyui-combogrid" id="reportinventory_employeeid" name="reportinventory_employeeid" style="width:350px" data-options="
								panelWidth: 500,
								idField: 'fullname',
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'employeeid',title:'<?php echo getCatalog('employeeid') ?>'},
										{field:'fullname',title:'<?php echo getCatalog('fullname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
        <tr>
            <td><?php echo getCatalog('product')?></td>
            <td><select class="easyui-combogrid" id="reportinventory_productid" name="reportinventory_productid" style="width:350px" data-options="
								panelWidth: 500,
								idField: 'productname',
								textField: 'productname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'productid',title:'<?php echo getCatalog('productid') ?>'},
										{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
        <tr>
            <td><?php echo getCatalog('salesarea')?></td>
            <td><select class="easyui-combogrid" id="reportinventory_salesareaid" name="reportinventory_salesareaid" style="width:350px" data-options="
								panelWidth: 500,
								idField: 'areaname',
								textField: 'areaname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/salesarea/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'salesareaid',title:'<?php echo getCatalog('salesareaid') ?>'},
										{field:'areaname',title:'<?php echo getCatalog('areaname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
         <tr>
            <td> <?php echo getCatalog('Qty Keluar')?></td>
            <td><input class="easyui-box" id="keluar3" name="keluar3" style="width:350px" data-options="required:true"></input></td>
        </tr>
        <tr>
            <td> <?php echo getCatalog('date')?></td>
            <td><input class="easyui-datebox" id="reportinventory_startdate" name="reportinventory_startdate" data-options="required:true"></input>
		-
		<input class="easyui-datebox" id="reportinventory_enddate" name="reportinventory_enddate" data-options="required:true"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreportinventory()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsreportinventory()"></a></td>
        </tr>
    </table>
<br/>
		
		<br/>
                
		<br/>
                
		<br/>
                
		<br/>
                
		<br/>
               
		
<?php }?>
</div>

<script type="text/javascript">
function downpdfreportinventory() {
	window.open('<?php echo $this->createUrl('reportinventory/downpdf') ?>?lro='+
		$('#listreportinventory').combobox('getValue') +
		'&companyid='+$('#reportinventory_companyid').combogrid('getValue')+
		'&sloc='+$('#reportinventory_slocid').combogrid('getValue')+
		'&slocto='+$('#reportinventory_sloctoid').combogrid('getValue')+
		'&storagebin='+$('#reportinventory_storagebinid').combogrid('getValue')+
		'&sales='+$('#reportinventory_employeeid').combogrid('getValue')+
		'&product='+$('#reportinventory_productid').combogrid('getValue')+
		'&salesarea='+$('#reportinventory_salesareaid').combogrid('getValue')+
		'&startdate='+$('#reportinventory_startdate').datebox('getValue')+
		'&enddate='+$('#reportinventory_enddate').datebox('getValue')+
    '&keluar3='+$("input[name='keluar3']").val()+
    '&per=10');
};

function downxlsreportinventory() {
	window.open('<?php echo $this->createUrl('reportinventory/downxls') 

?>?lro='+
		$('#listreportinventory').combobox('getValue') +
		'&companyid='+$('#reportinventory_companyid').combogrid('getValue')+
		'&sloc='+$('#reportinventory_slocid').combogrid('getValue')+
		'&slocto='+$('#reportinventory_sloctoid').combogrid('getValue')+
		'&storagebin='+$('#reportinventory_storagebinid').combogrid('getValue')+
		'&sales='+$('#reportinventory_employeeid').combogrid('getValue')+
		'&product='+$('#reportinventory_productid').combogrid('getValue')+
		'&salesarea='+$('#reportinventory_salesareaid').combogrid('getValue')+
		'&startdate='+$('#reportinventory_startdate').datebox('getValue')+
		'&enddate='+$('#reportinventory_enddate').datebox('getValue')+
    '&keluar3='+$("input[name='keluar3']").val()+
    '&per=10');
};
</script>