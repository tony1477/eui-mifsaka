<div id="tb-repprod">
    <table>
        <tr>
            <td><?php echo GetCatalog('reporttype')?></td>
            <td><select class="easyui-combobox" id="listrepprod" name="listrepprod" data-options="required:true" style="width:450px;">
            <option value="1">Rincian Produksi Per Dokumen</option>
            <option value="2">Rekap Produksi Per Barang</option>
            <option value="3">Rincian Pemakaian Per Dokumen</option>
            <option value="4">Rekap Pemakaian Per Barang</option>
            <option value="5">Perbandingan Planning vs Output</option>
            <option value="6">Raw Material yang Gudang Asal Belum Ada di Data Gudang - SPP</option>
            <option value="7">Raw Material yang Gudang Tujuan Belum Ada di Data Gudang - SPP</option>
            <option value="8">Pendingan Produksi</option>
            <option value="9">Rincian Pendingan Produksi Per Barang</option>
            <option value="10">Rekap Pendingan Produksi Per Barang</option>
            <option value="11">Rekap Produksi Per Barang Per Hari</option>
            <option value="12">Rekap Produksi Per Dokumen Belum Status Max</option>
            <option value="13">Rekap Produksi Per Barang Per Bulan</option>
            <option value="14">Jadwal Produksi (SPP)</option>
            <option value="15">SPP yang belum status Max</option>
            <option value="16">Laporan Perbandingan</option>
            <option value="17">Laporan Material SPP</option>
            <option value="18">Laporan Hasil Produksi Menggunakan Scan</option>
            <option value="19">Laporan Hasil Operator Per Man Power</option>
            <option value="20">Laporan Hasil Produksi By Kapasitas Cycle Time</option>
            <option value="21">Laporan Rincian Hasil Produksi Per Material Group Process</option>
            <option value="22">Laporan Rekap Hasil Produksi Per Material Group Process</option>
        </select></td>
        </tr>
         <tr>
            <td> <?php echo GetCatalog('company')?></td>
            <td><select class="easyui-combogrid" id="repprod_companyid" name="repprod_companyid" style="width:250px" data-options="
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
                </select></td>
        </tr>
         <tr>
            <td><?php echo GetCatalog('sloc')?></td>
            <td><select class="easyui-combogrid" id="repprod_slocid" name="repprod_slocid" style="width:250px" data-options="
					panelWidth: 500,
					idField: 'sloccode',
					textField: 'sloccode',
					pagination:true,
					mode:'remote',
					url: '<?php echo Yii::app()->createUrl('common/sloc/indextrxcom',array('grid'=>true)) ?>',
					method: 'get',
					onBeforeLoad: function(param) {
						param.companyid = $('#repprod_companyid').combogrid('getValue');
					},
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
            <td><?php echo GetCatalog('operator')?></td>
            <td><select class="easyui-combogrid" id="repprod_employeeid" name="repprod_employeeid" style="width:250px" data-options="
					panelWidth: 500,
					idField: 'fullname',
					textField: 'fullname',
					pagination:true,
					mode:'remote',
					url: '<?php echo Yii::app()->createUrl('hr/employee/indexcompany',array('grid'=>true)) ?>',
					method: 'get',
					onBeforeLoad: function(param) {
						param.companyid = $('#repprod_companyid').combogrid('getValue');
					},
					columns: [[
							{field:'employeeid',title:'<?php echo GetCatalog('employeeid') ?>'},
							{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
							{field:'structurename',title:'<?php echo GetCatalog('structurename') ?>'},
					]],
					fitColumns: true
				">
				</select></td>
        </tr>
         <tr>
            <td><?php echo GetCatalog('product')?></td>
            <td><select class="easyui-combogrid" id="repprod_productid" name="repprod_productid" style="width:250px" data-options="
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
				</select></td>
        </tr>
         <tr>
            <td> <?php echo GetCatalog('date')?></td>
            <td><input class="easyui-datebox" id="repprod_startdate" name="repprod_startdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input>
		-
		<input class="easyui-datebox" id="repprod_enddate" name="repprod_enddate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input>
            <a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepprod()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsrepprod()"></a></td>
        </tr>
    </table>
        <?php
				if (CheckAccess($this->menuname, $this->isdownload) == 1) { ?>
        
        
       
		<br/>
                
				<br/>

                
				<br/>
               
		
		
<?php }?>
</div>

<script type="text/javascript">
function downpdfrepprod() {
	window.open('<?php echo $this->createUrl('repprod/downpdf') 

?>?lro='+
		$('#listrepprod').combobox('getValue') +
		'&company='+$('#repprod_companyid').combogrid('getValue')+
		'&sloc='+$('#repprod_slocid').combogrid('getValue')+
		'&fullname='+$('#repprod_employeeid').combogrid('getValue')+
		'&product='+$('#repprod_productid').combogrid('getValue')+
		'&startdate='+$('#repprod_startdate').datebox('getValue')+
		'&enddate='+$('#repprod_enddate').datebox('getValue'));
};

function downxlsrepprod() {
	window.open('<?php echo $this->createUrl('repprod/downxls') 

?>?lro='+
		$('#listrepprod').combobox('getValue') +
		'&company='+$('#repprod_companyid').combogrid('getValue')+
		'&sloc='+$('#repprod_slocid').combogrid('getValue')+
        '&fullname='+$('#repprod_employeeid').combogrid('getValue')+
		'&product='+$('#repprod_productid').combogrid('getValue')+
		'&startdate='+$('#repprod_startdate').datebox('getValue')+
		'&enddate='+$('#repprod_enddate').datebox('getValue'));
};

function dateformatter(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
}

function dateparser(s){
	if (!s) return new Date();
		var ss = (s.split('-'));
		var y = parseInt(ss[2],10);
		var m = parseInt(ss[1],10);
		var d = parseInt(ss[0],10);
		if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
				return new Date(y,m-1,d);
		} else {
				return new Date();
		}
}
</script>
