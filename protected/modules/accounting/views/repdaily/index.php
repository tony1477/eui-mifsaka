<div id="tb-repdaily">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<?php echo GetCatalog('company')?>
		<select class="easyui-combogrid" id="repdaily_companyid" name="repdaily_companyid" style="width:250px" data-options="
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
				<?php echo GetCatalog('date')?>
		<input class="easyui-datebox" id="repdaily_startdate" name="repdaily_startdate" data-options="required:true"></input>
		-
		<input class="easyui-datebox" id="repdaily_enddate" name="repdaily_enddate" data-options="required:true"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepdaily()"></a>
        <a href="javascript:void(0)" title="Export Ke XLS"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsrepdaily()"></a>
<?php }?>
</div>

<script type="text/javascript">
function downpdfrepdaily   () {
	window.open('<?php echo $this->createUrl('repdaily/downpdf')?>?company='+$('#repdaily_companyid').combogrid('getValue')+
		'&startdate='+$('#repdaily_startdate').datebox('getValue')+
		'&enddate='+$('#repdaily_enddate').datebox('getValue'));
};
    
function downxlsrepdaily   () {
	window.open('<?php echo $this->createUrl('repdaily/downxls')?>?company='+$('#repdaily_companyid').combogrid('getValue')+
		'&startdate='+$('#repdaily_startdate').datebox('getValue')+
		'&enddate='+$('#repdaily_enddate').datebox('getValue'));
};
</script>