<div id="tb-reppurchase">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
  <table>
    <tr>
      <td><?php echo GetCatalog('reporttype')?></td>
      <td>
        <select class="easyui-combobox" id="listreppurchase" name="listreppurchase" data-options="required:true" style="width:250px;">
        <option value="1">Rincian PO Per Dokumen</option>
        <option value="2">Rekap PO Per Dokumen</option>
        <option value="3">Rekap PO Per Supplier</option>
        <option value="4">Rekap PO Per Barang</option>
        <option value="5">Rincian Pembelian Per Dokumen</option>        
        <option value="6">Rekap Pembelian Per Dokumen</option>
        <option value="7">Rekap Pembelian Per Supplier</option>
        <option value="8">Rekap Pembelian Per Barang</option>
        <option value="9">Rincian Retur Pembelian Per Dokumen</option>        
        <option value="10">Rekap Retur Pembelian Per Dokumen</option>
        <option value="11">Rekap Retur Pembelian Per Supplier</option>
        <option value="12">Rekap Retur Pembelian Per Barang</option>
        <option value="13">Rincian Pembelian - Retur Per Dokumen</option>
        <option value="14">Rekap Pembelian - Retur Per Dokumen</option>
        <option value="15">Rekap Pembelian - Retur Per Supplier</option>
        <option value="16">Rekap Pembelian - Retur Per Barang</option>
        <option value="17">Pendingan PO Per Dokumen</option>
        <option value="23">Rincian Pendingan PO Per Dokumen ke Supplier</option>
        <option value="18">Rincian Pendingan PO Per Barang</option>
        <option value="19">Rekap Pendingan PO Per Barang</option>
        <option value="26">Rincian Pendingan PO Per Barang Per Dokumen</option>
        <option value="24">Rincian Pendingan PO Per Barang ke Supplier</option>
        <option value="25">Rekap Pendingan PO Per Barang ke Supplier</option>
        <option value="27">Rincian Pendingan PO Per Gudang</option>
        <option value="20">Dokumen PO yang belum Max</option>
        <option value="21">Rekap Pembelian Per Barang Per Tanggal</option>
        <option value="22">Laporan Pembelian Per Supplier Per Bulan Per Tahun</option>
        <option value="28">Rekap Forecast FPP</option>
        </select>
      </td>
    </tr>
    <tr>
      <td><?php echo GetCatalog('company')?></td>
      <td>
        <select class="easyui-combogrid" id="reppurchase_companyid" name="reppurchase_companyid" style="width:250px" data-options="
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
        </select>
      </td>
    </tr>
    <tr>
      <td> <?php echo GetCatalog('supplier')?></td>
      <td>
        <select class="easyui-combogrid" id="reppurchase_addressbookid" name="reppurchase_addressbookid" style="width:250px" data-options="
            panelWidth: 500,
            idField: 'fullname',
            textField: 'fullname',
            pagination:true,
            mode:'remote',
            url: '<?php echo Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ?>',
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
      <td><?php echo GetCatalog('productcollection')?></td>
      <td>
        <select class="easyui-combogrid" id="reppurchase_productcollectid" name="reppurchase_productcollectid" style="width:250px" data-options="
            panelWidth: 500,
            idField: 'productcollectid',
            textField: 'collectionname',
            pagination:true,
            multiple:true,
            mode:'remote',
            url: '<?php echo Yii::app()->createUrl('common/productcollection/index',array('grid'=>true,'combo'=>true)) ?>',
            method: 'get',
            columns: [[
                {field:'productcollectid',title:'<?php echo GetCatalog('productcollectid') ?>'},
                {field:'collectionname',title:'<?php echo GetCatalog('collectionname') ?>'},
            ]],
            fitColumns: true
        ">
        </select>
      </td>
    </tr>
    <tr>
      <td><?php echo GetCatalog('product')?></td>
      <td>
        <select class="easyui-combogrid" id="reppurchase_productid" name="reppurchase_productid" style="width:250px" data-options="
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
        </select>
      </td>
    </tr>
    <tr>
      <td><?php echo GetCatalog('date')?></td>
      <td>
        <input class="easyui-datebox" id="reppurchase_startdate" name="reppurchase_startdate" data-options="required:true" width="1066px"></input> - <input class="easyui-datebox" id="reppurchase_enddate" name="reppurchase_enddate" data-options="required:true"></input>
        <a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreportpurchasing()"></a>
        <a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsreportpurchasing()"></a>
      </td>
    </tr>
  </table>
<?php }?>
</div>
<script type="text/javascript">
function makeUnique(str) {
  let uniqueNames = [];
  $.each(str, function(i, val){
    if($.inArray(val, uniqueNames) === -1) uniqueNames.push(val);
  });
  return uniqueNames.join();
}

function downpdfreportpurchasing() {
  let productcollectid = makeUnique($('#reppurchase_productcollectid').combogrid('getValues'));
	window.open('<?php echo $this->createUrl('reportpurchasing/downpdf') ?>?lro='+
		$('#listreppurchase').combobox('getValue') +
		'&company='+$('#reppurchase_companyid').combogrid('getValue')+
		'&supplier='+$('#reppurchase_addressbookid').combogrid('getValue')+
		'&productcollectid='+productcollectid+
		'&product='+$('#reppurchase_productid').combogrid('getValue')+
		'&startdate='+$('#reppurchase_startdate').datebox('getValue')+
		'&enddate='+$('#reppurchase_enddate').datebox('getValue')+
		'&per=10');
    
};

function downxlsreportpurchasing() {
  let productcollectid = makeUnique($('#reppurchase_productcollectid').combogrid('getValues'));
	window.open('<?php echo $this->createUrl('reportpurchasing/downxls') 
?>?lro='+
		$('#listreppurchase').combobox('getValue') +
		'&company='+$('#reppurchase_companyid').combogrid('getValue')+
		'&supplier='+$('#reppurchase_addressbookid').combogrid('getValue')+
		'&productcollectid='+productcollectid+
		'&product#='+$('#reppurchase_productid').combogrid('getValue')+
		'&startdate='+$('#reppurchase_startdate').datebox('getValue')+
		'&enddate='+$('#reppurchase_enddate').datebox('getValue')+
		'&per=10');
};
</script>
