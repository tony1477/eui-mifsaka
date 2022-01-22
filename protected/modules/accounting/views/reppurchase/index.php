<div id="tb-reppurchase">
	<?php





 

 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	<?php echo GetCatalog('reporttype')?>
<select class="easyui-combobox" id="listreppurchase" name="listreppurchase" data-options="required:true" style="width:250px;">
        <option value="1">Rincian Pembelian Per Dokumen</option>
        <option value="2">Rekap Pembelian Per Dokumen</option>
        <option value="3">Rekap Pembelian Per Customer</option>
        <option value="4">Rekap Pembelian Per Barang</option>
        <option value="5">Rekap Pembelian Per Area</option>
        <option value="6">Rincian Retur Pembelian Per Dokumen</option>        
        <option value="7">Rekap Retur Pembelian Per Dokumen</option>
        <option value="8">Rekap Retur Pembelian Per Customer</option>
        <option value="9">Rekap Retur Pembelian Per Barang</option>
        <option value="10">Rekap Retur Pembelian Per Area</option>
        <option value="11">Rincian Pembelian - Retur Per Dokumen</option>
        <option value="12">Rekap Pembelian - Retur Per Dokumen</option>
        <option value="13">Rekap Pembelian - Retur Per Customer</option>
        <option value="14">Rekap Pembelian - Retur Per Barang</option>
        <option value="15">Rekap Pembelian - Retur Per Area</option>
    </select>		<br/>
		<?php echo GetCatalog('company')?>
		<select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
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
				</select><br/>
                <?php echo GetCatalog('sloc')?>
		<select class="easyui-combogrid" id="slocid" name="slocid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'slocid',
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
				</select><br/>
                <?php echo GetCatalog('sales')?>
		<select class="easyui-combogrid" id="employeeid" name="employeeid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'employeeid',
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
				</select><br/>
                <?php echo GetCatalog('product')?>
		<select class="easyui-combogrid" id="productid" name="productid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'productid',
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
				</select><br/>
                <?php echo GetCatalog('date')?>
		<input class="easyui-datebox" id="startdate" name="startdate" data-options="required:true"></input>
		-
		<input class="easyui-datebox" id="enddate" name="enddate" data-options="required:true"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreppurchase()"></a>
<?php }?>
</div>

<script type="text/javascript">
function downpdfreppurchase() {
	window.open('<?php echo $this->createUrl('reppurchase/downpdf') 

?>?lro='+
		$('#listreppurchase').combobox('getValue') +
		'&company='+$('#companyid').combogrid('getValue')+
		'&sloc='+$('#slocid').combogrid('getValue')+
		'&startdate='+$('#startdate').datebox('getValue')+
		'&enddate='+$('#enddate').datebox('getValue'));
};
</script>