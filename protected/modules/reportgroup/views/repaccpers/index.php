<div id="tb-repaccpers">
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	
    <table >
        <tr>
            <td><?php echo GetCatalog('reporttype')?></td>
            <td> <select class="easyui-combobox" id="listrepaccpers" name="listrepaccpers" data-options="required:true" style="width:320px;">
              <option value="1">Rekap Persediaan (Detail)</option>
							<option value="2">Rekap Penerimaan Persediaan (Detail)</option>
							<option value="3">Rekap Pengeluaran Persediaan (Detail)</option>
							<option value="4">HPP</option>
							<option value="5">HPP Berdasarkan BOM</option> 
							<option value="6">Rincian Nilai Pemakaian Stok - Data Harga</option>
							<option value="7">Rekap Nilai Pemakaian Stok - Data Harga</option>
							<option value="8">Rincian Nilai Stock Opname</option>
							<option value="9">Rekap Nilai Stock Opname</option>
							<option value="10">Rincian Harga Pokok Penjualan - Data Harga</option>
							<option value="11">Rekap Harga Pokok Penjualan - Data Harga</option>
							<option value="13">Rekap Perbandingan Nilai HPP, Nilai Penjualan dan Nilai Jurnal Per Dokumen</option>
							<option value="14">Rekap Perbandingan Nilai HPP, Nilai Retur Penjualan dan Nilai Jurnal Per Dokumen</option>
							<option value="15">Rekap Perbandingan Nilai HPP dan Nilai Penjualan Per Barang</option>
    </select></td>
        </tr>
        <tr>
            <td><?php echo GetCatalog('company')?></td>
            <td><select class="easyui-combogrid" id="repaccpers_companyid" name="repaccpers_companyid" style="width:320px" data-options="
								panelWidth: 308,
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
				</select></td>
        </tr>
        <tr>
            <td><?php echo GetCatalog('sloc')?></td>
            <td><select class="easyui-combogrid" id="repaccpers_slocid" name="repaccpers_slocid" style="width:320px" data-options="
								panelWidth: 308,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true,'grid'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'slocid',title:'<?php echo GetCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo GetCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo GetCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
        <tr>
 			<td>
 				<?php echo GetCatalog('materialgroup')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccpers_materialgroupid" name="repaccpers_materialgroupid" style="width:320px" data-options="
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
            <td><?php echo GetCatalog('storagebin')?></td>
            <td><select class="easyui-combogrid" id="repaccpers_storagebinid" name="repaccpers_storagebinid" style="width:320px" data-options="
								panelWidth: 500,
								idField: 'description',
								textField: 'description',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/storagebin/index',array('grid'=>true,'single'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'storagebinid',title:'<?php echo GetCatalog('storagebinid') ?>'},
										{field:'description',title:'<?php echo GetCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
 		<tr>
            <td><?php echo GetCatalog('product')?></td>
            <td><select class="easyui-combogrid" id="repaccpers_productid" name="repaccpers_productid" style="width:320px" data-options="
								panelWidth: 308,
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
				</select></td>
        </tr>
        <tr>
            <td><?php echo GetCatalog('date')?></td>
            <td><input class="easyui-datebox" id="repaccpers_startdate" name="repaccpers_startdate" data-options="required:true"></input>
		s/d
		<input class="easyui-datebox" id="repaccpers_enddate" name="repaccpers_enddate" data-options="required:true"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepaccpers()"></a> 
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsrepaccpers()"></a></td>
        </tr>
    </table>
   			
		
<?php }?>
</div>

<script type="text/javascript">
function downpdfrepaccpers  () {
	window.open('<?php echo $this->createUrl('repaccpers/downpdf') ?>?lro='+
		$('#listrepaccpers').combobox('getValue') +
		'&company='+$('#repaccpers_companyid').combogrid('getValue')+
		'&sloc='+$('#repaccpers_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccpers_materialgroupid').combogrid('getValue')+
		'&storagebin='+$('#repaccpers_storagebinid').combogrid('getValue')+
		'&product='+$('#repaccpers_productid').combogrid('getValue')+
		'&startdate='+$('#repaccpers_startdate').datebox('getValue')+
		'&enddate='+$('#repaccpers_enddate').datebox('getValue')+
		'&per=10');
};

function downxlsrepaccpers   () {
	window.open('<?php echo $this->createUrl('repaccpers/downxls') ?>?lro='+
		$('#listrepaccpers').combobox('getValue') +
		'&company='+$('#repaccpers_companyid').combogrid('getValue')+
		'&sloc='+$('#repaccpers_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccpers_materialgroupid').combogrid('getValue')+
		'&storagebin='+$('#repaccpers_storagebinid').combogrid('getValue')+
		'&product='+$('#repaccpers_productid').combogrid('getValue')+
		'&startdate='+$('#repaccpers_startdate').datebox('getValue')+
		'&enddate='+$('#repaccpers_enddate').datebox('getValue')+
		'&per=10');
};
</script>
