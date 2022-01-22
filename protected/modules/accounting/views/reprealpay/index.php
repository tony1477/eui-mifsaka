<div id="tb-reprealpay">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<?php echo GetCatalog('company')?>
		<select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('admin/company/index',array('grid'=>true)) ?>',
								method: 'get',
								queryParams: {
									auth:true
								},
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select><br/>
				<?php echo GetCatalog('supplier')?>
				<select class="easyui-combogrid" id="addressbookid" name="addressbookid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'fullname',
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/supplier/index',array('grid'=>true)) ?>',
								method: 'get',
								queryParams: {
									auth:true
								},
								columns: [[
										{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
								]],
								fitColumns: true
						">
				</select><br/>
				<?php echo GetCatalog('date')?>
		<input class="easyui-datebox" id="startdate" name="startdate" data-options="required:true"></input>
		-
		<input class="easyui-datebox" id="enddate" name="enddate" data-options="required:true"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreportacc()"></a>
<?php }?>
</div>

<script type="text/javascript">
function downpdfreportacc   () {
	window.open('<?php echo $this->createUrl('reprealpay/downpdf') 

?>?company='+$('#companyid').combogrid('getValue')+
		'&addressbook='+$('#addressbookid').combogrid('getValue')+
		'&startdate='+$('#startdate').datebox('getValue')+
		'&enddate='+$('#enddate').datebox('getValue'));
};
</script>