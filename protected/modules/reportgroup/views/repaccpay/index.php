<div id="tb-repaccpay">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>	
    <table >
        <tr>
            <td><?php echo GetCatalog('reporttype')?></td>
            <td> <select class="easyui-combobox" id="listrepaccpay" name="listrepaccpay" data-options="required:true" style="width:320px;">
        <option value="1">Rincian Biaya Ekspedisi Per Dokumen</option>
				<option value="2">Rekap Biaya Ekspedisi Per Dokumen</option>
        <option value="3">Rekap Biaya Ekspedisi Per Barang</option>        
        <option value="4">Rincian Pembayaran Hutang per Dokumen</option>
        <option value="5">Kartu Hutang</option>
        <option value="6">Rekap Hutang per Supplier</option>
        <option value="7">Rincian Pembelian & Retur Beli Belum Lunas</option>
        <option value="8">Rincian  Umur Hutang per STTB</option>       
        <option value="9">Rekap Umur Hutang per Supplier</option>
        <option value="10">Rekap Invoice AP Per Dokumen Belum Status Max</option>
        <option value="11">Rekap Permohonan Pembayaran Per Dokumen Belum Status Max</option>
        <option value="12">Rekap Nota Retur Pembelian Per Dokumen Belum Status Max</option>      
    </select></td>
        </tr>
        <tr>
            <td><?php echo GetCatalog('company')?></td>
            <td><select class="easyui-combogrid" id="repaccpay_companyid" name="repaccpay_companyid" style="width:320px" data-options="
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
            <td><select class="easyui-combogrid" id="repaccpay_slocid" name="repaccpay_slocid" style="width:320px" data-options="
								panelWidth: 308,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
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
            <td><?php echo GetCatalog('product')?></td>
            <td><select class="easyui-combogrid" id="repaccpay_productid" name="repaccpay_productid" style="width:320px" data-options="
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
            <td><?php echo GetCatalog('supplier')?></td>
            <td><select class="easyui-combogrid" id="repaccpay_addressbookid" name="repaccpay_addressbookid" style="width:320px" data-options="
								panelWidth: 308,
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
				</select></td>
        </tr>
         <tr>
            <td><?php echo GetCatalog('no_document')?></td>
            <td><select class="easyui-combogrid" id="repaccpay_invoiceapid" name="repaccpay_invoiceapid" style="width:320px" data-options="
								panelWidth: 308,
								idField: 'invoiceno',
								textField: 'invoiceno',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('accounting/invoiceap/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'invoiceapid',title:'<?php echo GetCatalog('invoiceapid') ?>'},
										{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno') ?>'},
										
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
        <tr>
            <td><?php echo GetCatalog('date')?></td>
            <td><input class="easyui-datebox" id="repaccpay_startdate" name="repaccpay_startdate" data-options="required:true"></input>
		s/d
		<input class="easyui-datebox" id="repaccpay_enddate" name="repaccpay_enddate" data-options="required:true"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepaccpay()"></a> 
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsrepaccpay()"></a></td>
        </tr>
    </table>
   			
		
<?php }?>
</div>

<script type="text/javascript">
function downpdfrepaccpay  () {
	window.open('<?php echo $this->createUrl('repaccpay/downpdf') ?>?lro='+
		$('#listrepaccpay').combobox('getValue') +
		'&company='+$('#repaccpay_companyid').combogrid('getValue')+
		'&sloc='+$('#repaccpay_slocid').combogrid('getValue')+
		'&product='+$('#repaccpay_productid').combogrid('getValue')+
                '&supplier='+$('#repaccpay_addressbookid').combogrid('getValue')+
                '&invoice='+$('#repaccpay_invoiceapid').combogrid('getValue')+
		'&startdate='+$('#repaccpay_startdate').datebox('getValue')+
		'&enddate='+$('#repaccpay_enddate').datebox('getValue')+
		'&per=10');
};

function downxlsrepaccpay  () {
	window.open('<?php echo $this->createUrl('repaccpay/downxls') ?>?lro='+
		$('#listrepaccpay').combobox('getValue') +
		'&company='+$('#repaccpay_companyid').combogrid('getValue')+
		'&sloc='+$('#repaccpay_slocid').combogrid('getValue')+
		'&product='+$('#repaccpay_productid').combogrid('getValue')+
                '&supplier='+$('#repaccpay_addressbookid').combogrid('getValue')+
                '&invoice='+$('#repaccpay_invoiceapid').combogrid('getValue')+
		'&startdate='+$('#repaccpay_startdate').datebox('getValue')+
		'&enddate='+$('#repaccpay_enddate').datebox('getValue')+
		'&per=10');
};
</script>