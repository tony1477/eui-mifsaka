<div id="tb-repsales">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	<?php echo GetCatalog('reporttype')?>
<select class="easyui-combobox" id="listrepsales" name="listrepsales" data-options="required:true" style="width:250px;">
        <option value="1">Rincian Penjualan Per Dokumen</option>
        <option value="2">Rekap Penjualan Per Dokumen</option>
        <option value="3">Rekap Penjualan Per Customer</option>
        <option value="4">Rekap Penjualan Per Barang</option>
        <option value="5">Rekap Penjualan Per Area</option>
        <option value="6">Rincian Retur Penjualan Per Dokumen</option>        
        <option value="7">Rekap Retur Penjualan Per Dokumen</option>
        <option value="8">Rekap Retur Penjualan Per Customer</option>
        <option value="9">Rekap Retur Penjualan Per Barang</option>
        <option value="10">Rekap Retur Penjualan Per Area</option>
        <option value="11">Rincian Penjualan - Retur Per Dokumen</option>
        <option value="12">Rekap Penjualan - Retur Per Dokumen</option>
        <option value="13">Rekap Penjualan - Retur Per Customer</option>
        <option value="14">Rekap Penjualan - Retur Per Barang</option>
        <option value="15">Rekap Penjualan - Retur Per Area</option>
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
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepsales()"></a>
<?php }?>
</div>

<script type="text/javascript">
function downpdfrepsales() {
	window.open('<?php echo $this->createUrl('repsales/downpdf') 

?>?lro='+
		$('#listrepsales').combobox('getValue') +
		'&company='+$('#companyid').combogrid('getValue')+
		'&sloc='+$('#slocid').combogrid('getValue')+
		'&sales='+$('#employeeid').combogrid('getValue')+
		'&product='+$('#productid').combogrid('getValue')+
		'&startdate='+$('#startdate').datebox('getValue')+
		'&enddate='+$('#enddate').datebox('getValue'));
};
</script>