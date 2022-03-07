<div id="tb-repaccrec">
	<?php

 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
 	<table>
 		<tr>
 			<td>
 				<?php echo GetCatalog('reporttype')?> 
 			</td>
 			<td>
 				<select class="easyui-combobox" id="listrepaccrec" name="listrepaccrec" data-options="required:true" style="width:500px;">
					<option value="1">1.1.Rincian Pelunasan Piutang Per Dokumen</option>
					<option value="2">1.2.Rekap Pelunasan Piutang Per Divisi</option>
					<option value="15">1.3.Rincian Pelunasan Piutang Per Sales</option>
					<option value="16">1.4.Rekap Pelunasan Piutang Per Sales</option>
					<option value="17">1.5.Rincian Pelunasan Piutang Per Sales Per Jenis Barang</option>
					<option value="18">1.6.Rincian Pelunasan Piutang Per Sales Per Jenis Barang (Tanpa OB)</option>
					<option value="19">1.7.Rekap Pelunasan Piutang Per Sales Per Jenis Barang</option>
					<option value="22">1.8.Rincian Pelunasan Piutang Per Customer</option>
					<option value="23">1.9.Rekap Pelunasan Piutang Per Customer</option>
					<option value="24">1.10.Rincian Pelunasan Piutang Per Customer Per Jenis Barang</option>
					<option value="25">1.11.Rekap Pelunasan Piutang Per Customer Per Jenis Barang</option>
					<option value="29">1.12.Rincian Pelunasan Piutang (Filter Tanggal Invoice)</option>
					<option value="30">1.13.Rincian Pelunasan Piutang (Filter Tanggal Pelunasan)</option>
					<option value="20">1.14.Rekap Penjualan VS Pelunasan Per Bulan Per Customer</option>
					<option value="21">1.15.Rekap Piutang VS Pelunasan Per Bulan Per Customer</option>
					<option value="3">2.1.Kartu Piutang Dagang</option>
					<option value="4">2.2.Rekap Piutang Dagang Per Customer</option>
					<option value="26">2.3.Rekap Umur Piutang Dagang</option>
					<option value="27">2.4.Rekap Umur Piutang Dagang Per Bulan Per Tahun</option>
					<option value="5">2.5.1.Rincian Faktur & Retur Jual Belum Lunas</option>
					<option value="41">2.5.2.Rincian Faktur & Retur Jual Belum Lunas Per Kategori Customer</option>
					<option value="28">2.6.Rincian Faktur & Retur Jual Belum Lunas (Filter Tanggal JTT)</option>
					<option value="6">2.7.Rincian Umur Piutang Dagang Per Customer</option>
					<option value="7">2.8.Rekap Umur Piutang Dagang Per Customer</option>
					<option value="35">2.9.Rekap Umur Piutang Dagang Per Customer VS TOP</option>
					<option value="8">2.10.Rincian Faktur & Retur Jual Belum Lunas Per Sales</option>
					<option value="9">2.11.Rekap Kontrol Piutang Customer vs Plafon</option> 
					<option value="10">2.12.Rincian Kontrol Piutang Customer vs Plafon</option>
					<option value="36">2.13.Rekap Monitoring Piutang Per Minggu Per Customer Per Sales</option>
					<option value="11">2.14.Konfirmasi Piutang Dagang</option>
					<option value="38">2.15.Rekap Umur Piutang Dagang Per Company</option>
					<option value="31">3.1.Rekap Target VS Realisasi Tagihan</option>
					<option value="32">3.2.Rincian Komisi Tagihan Per Sales</option>
					<option value="39">3.3.Rekap Komisi Tagihan Per Sales</option>
					<option value="37">3.5.Rekap Komisi Tagihan Per SPV</option>
					<option value="12">4.1.Rekap Invoice AR Per Dokumen Belum Status Max</option>
					<option value="13">4.2.Rekap Nota Retur Penjualan Per Dokumen Belum Status Max</option>
					<option value="14">4.3.Rekap Pelunasan Piutang Per Dokumen Belum Status Max</option>
					<option value="33">4.4.Rekap Target Tagihan Per Dokumen Belum Status Max</option>
					<option value="34">4.5.Rekap Skala Komisi Tagihan Per Dokumen Belum Status Max</option>
				</select>  
 			</td> 
 		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('company')?>
 			</td> 
 			<td>
 				<select class="easyui-combogrid" id="repaccrec_companyid" name="repaccrec_companyid" style="width:500px" data-options="
								panelWidth: 300,
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
		<tr id="accrec_plantid">
			<td><?php echo GetCatalog('plant')?></td>
			<td><select class="easyui-combogrid" id="repaccrec_plantid" name="repaccrec_plantid" style="width:500px" data-options="
											panelWidth: 500,
											idField: 'plantid',
											textField: 'plantcode',
											pagination:true,
											mode:'remote',
											url: '<?php echo Yii::app()->createUrl('common/plant/index',array('grid'=>true)) ?>',
											queryParams:{
												trxcom:true
											},
											onBeforeLoad: function(param) {
													param.companyid = $('#repaccrec_companyid').combogrid('getValue');
											},
											method: 'get',
											columns: [[
													{field:'plantid',title:'<?php echo GetCatalog('slocid') ?>'},
													{field:'plantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
											]],
											fitColumns: true
									">
					</select>
			</td>
		</tr>
 		<tr>
 			<td id="accrec_slocid">
 				<?php echo GetCatalog('sloc')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_slocid" name="repaccrec_slocid" style="width:500px" data-options="
								panelWidth: 300,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/sloc/indextrxcom',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								onBeforeLoad: function(param) {
									param.companyid = $('#repaccrec_companyid').combogrid('getValue');
								},
								columns: [[
										{field:'slocid',title:'<?php echo GetCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo GetCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo GetCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td id="accrec_materialgroupid">
 				<?php echo GetCatalog('materialgroup')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_materialgroupid" name="repaccrec_materialgroupid" style="width:500px" data-options="
								panelWidth: 500,
								idField: 'description',
								textField: 'description',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/materialgroup/indexfg',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns:[[
									{field:'materialgroupid',title:'<?php echo GetCatalog('unitofmeasureid')?>',width:50},
									{field:'materialgroupcode',title:'<?php echo GetCatalog('materialgroupcode')?>',width:50},
									{field:'description',title:'<?php echo GetCatalog('description')?>',width:200},
								]],
								fitColumns: true
						">
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td id="accrec_addressbookid">
 				<?php echo GetCatalog('customer')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_addressbookid" name="repaccrec_addressbookid" style="width:500px" data-options="
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
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td id="accrec_productid">
 				<?php echo GetCatalog('product')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_productid" name="repaccrec_productid" style="width:500px" data-options="
								panelWidth: 300,
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
 			<td id="accrec_employeeid">
 				<?php echo GetCatalog('sales')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_employeeid" name="repaccrec_employeeid" style="width:500px" data-options="
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
				</select>
 			</td>
 		</tr>
		<tr>
 			<td id="accrec_spvid">
 				<?php echo GetCatalog('salesspv')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_spvid" name="repaccrec_spvid" style="width:500px" data-options="
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
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td id="accrec_salesareaid">
 				<?php echo GetCatalog('salesarea')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_salesareaid" name="repaccrec_salesareaid" style="width:500px" data-options="
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
				</select>
 			</td>
 		</tr>
 		<tr>
 			<td id="accrec_groupcustomerid">
 				<?php echo GetCatalog('groupcustomer')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccrec_groupcustomerid" name="repaccrec_groupcustomerid" style="width:500px" data-options="
								panelWidth: 500,
								idField: 'groupname',
								textField: 'groupname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/groupcustomer/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'groupcustomerid',title:'<?php echo GetCatalog('groupcustomerid') ?>'},
										{field:'groupname',title:'<?php echo GetCatalog('groupname') ?>'},
								]],
								fitColumns: true
						">
				</select>
 			</td>
 		</tr>
		<tr>
 			<td id="accrec_umurpiutang">
 				<?php echo GetCatalog('umurinvoice')?> 
 			</td>
 			<td> 				
				<input class="easyui-textbox" id="repaccrec_umurpiutang" name="repaccrec_umurpiutang" style="width:100px">
 			</td>
 		</tr>
		<tr>
			<td id="accrec_isdisplay"><?php echo GetCatalog('indisplay')?></td>
			<td>
					<select class="easyui-combobox" id="repaccrec_isdisplay" name="repaccrec_isdisplay" data-options="required:'true', panelHeight:'auto'" style="width:120px">
						<option value="">All</option>
						<option value="0">No</option>
						<option value="1">Yes</option>
			</td>
		</tr>
		<tr>
			<td id="accrec_isbaddebt"><?php echo GetCatalog('Tipe Faktur')?></td>
			<td>
					<select class="easyui-combobox" id="repaccrec_isbaddebt" name="repaccrec_isbaddebt" data-options="required:'true', panelHeight:'auto'" style="width:120px">
						<option value="0">Good</option>
						<option value="1">Bad Debt</option>
			</td>
		</tr>
 		<tr>
 			<td>
 				<?php echo GetCatalog('date')?>
 			</td>
 			<td>
 				<input class="easyui-datebox" id="repaccrec_startdate" name="repaccrec_startdate" data-options="required:true"></input>
				-
				<input class="easyui-datebox" id="repaccrec_enddate" name="repaccrec_enddate" data-options="required:true"></input>
				<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepaccrec()"></a>
				<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsrepaccrec()"></a>
 			</td> 
 		</tr> 
 	</table>				 
			 	
<?php }?>
</div>

<script type="text/javascript">
$.extend($.fn.textbox.methods, {
show: function(jq){
    return jq.each(function(){
        $(this).next().show();
    })
},
hide: function(jq){
    return jq.each(function(){
        $(this).next().hide();
    })
}
})

var repaccplant = ["1","2","5","8","15","16","17","18","19","21","22","23","24","25","28","29","30","41"];
var repaccrecsloc =  ["5","35","41"];
var repaccrecmaterialgroup =  ["5","41"];
var repaccreccustomer =  ["1","2","3","4","5","6","7","8","9","10","11","15","16","17","18","19","20","21","22","23","24","25","26","27","28","35","36","38","41"];
var repaccrecproduct =  ["1","5","15","16","17","18","19","22","23","24","25","41"];
var repaccrecsales =  ["1","2","3","4","5","6","7","8","9","10","11","15","16","17","18","19","22","23","24","25","26","27","28","31","32","37","38","39","41"];
var repaccrecspv =  ["37"];
var repaccrecgroupcustomer =  ["5","8","41"];
var repaccrecsalesarea =  ["5","15","16","17","18","19","22","23","24","25","26","27","28","35","38","41"];
var repaccrecumur =  ["5","22","35","36","41"];
var repaccrecdisplay =  ["5","35","41","98","99"];
var repaccrecbaddebt =  ["1","2","3","4","5","6","7","8","9","10","11","15","16","17","18","19","21","22","23","24","25","26","27","28","29","30","35","36","38","41","98","99"];
var repaccrecstart =  ["1","2","3","4","12","13","14","15","16","17","18","19","20","22","23","24","25","27","29","30","33","34"];
var repaccrecend =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","41"];

$(document).ready(function(){
    $('#listrepaccrec').combobox({
			onSelect: function(row){
				var target = this;
				setTimeout(function(){
					var n = $('#listrepaccrec').combobox('getValue');
					if(repaccrecsloc.includes(n)) {
							$('#repaccrec_slocid').combobox('show');
							$("#accrec_slocid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_slocid').combobox('hide');
							$("#accrec_slocid").hide();
						}
					if(repaccrecmaterialgroup.includes(n)) {
							$('#repaccrec_materialgroupid').combobox('show');
							$("#accrec_materialgroupid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_materialgroupid').combobox('hide');
							$("#accrec_materialgroupid").hide();
						}
					if(repaccreccustomer.includes(n)) {
							$('#repaccrec_addressbookid').combobox('show');
							$("#accrec_addressbookid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_addressbookid').combobox('hide');
							$("#accrec_addressbookid").hide();
						}
					if(repaccrecproduct.includes(n)) {
							$('#repaccrec_productid').combobox('show');
							$("#accrec_productid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_productid').combobox('hide');
							$("#accrec_productid").hide();
						}
					if(repaccrecsales.includes(n)) {
							$('#repaccrec_employeeid').combobox('show');
							$("#accrec_employeeid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_employeeid').combobox('hide');
							$("#accrec_employeeid").hide();
						}
					if(repaccrecspv.includes(n)) {
							$('#repaccrec_spvid').combobox('show');
							$("#accrec_spvid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_spvid').combobox('hide');
							$("#accrec_spvid").hide();
						}
					if(repaccrecsalesarea.includes(n)) {
							$('#repaccrec_salesareaid').combobox('show');
							$("#accrec_salesareaid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_salesareaid').combobox('hide');
							$("#accrec_salesareaid").hide();
						}
					if(repaccrecgroupcustomer.includes(n)) {
							$('#repaccrec_groupcustomerid').combobox('show');
							$("#accrec_groupcustomerid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_groupcustomerid').combobox('hide');
							$("#accrec_groupcustomerid").hide();
						}
					if(repaccrecumur.includes(n)) {
							$('#repaccrec_umurpiutang').textbox('show');
							$("#accrec_umurpiutang").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_umurpiutang').textbox('hide');
							$("#accrec_umurpiutang").hide();
						}
					if(repaccrecdisplay.includes(n)) {
							$('#repaccrec_isdisplay').combobox('show');
							$("#accrec_isdisplay").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_isdisplay').combobox('hide');
							$("#accrec_isdisplay").hide();
						}
					if(repaccrecbaddebt.includes(n)) {
							$('#repaccrec_isbaddebt').combobox('show');
							$("#accrec_isbaddebt").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_isbaddebt').combobox('hide');
							$("#accrec_isbaddebt").hide();
						}
					if(repaccrecstart.includes(n)) {
							$('#repaccrec_startdate').combobox('show');
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_startdate').combobox('hide');
						}
					if(repaccrecend.includes(n)) {
							$('#repaccrec_enddate').combobox('show');
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_enddate').combobox('hide');
						}
					if(repaccplant.includes(n)) {
							$('#repaccrec_plantid').combobox('show');
							$("#accrec_plantid").show();
						}
						else {
							//alert('tidak ada');
							$('#repaccrec_plantid').combobox('hide');
							$("#accrec_plantid").hide();
						}
				},0);
      }
    })
});

function downpdfrepaccrec   () {
	window.open('<?php echo $this->createUrl('repaccrec/downpdf') ?>?lro='+
		$('#listrepaccrec').combobox('getValue') +
		'&company='+$('#repaccrec_companyid').combogrid('getValue')+
		'&plantid='+$('#repaccrec_plantid').combogrid('getValue')+
		'&sloc='+$('#repaccrec_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccrec_materialgroupid').combogrid('getValue')+
		'&customer='+$('#repaccrec_addressbookid').combogrid('getValue')+
		'&product='+$('#repaccrec_productid').combogrid('getValue')+
		'&sales='+$('#repaccrec_employeeid').combogrid('getValue')+
		'&spv='+$('#repaccrec_spvid').combogrid('getValue')+
		'&salesarea='+$('#repaccrec_salesareaid').combogrid('getValue')+
		'&groupcustomer='+$('#repaccrec_groupcustomerid').combogrid('getValue')+
		'&umurpiutang='+$('#repaccrec_umurpiutang').textbox('getValue')+
		'&isdisplay='+$('#repaccrec_isdisplay').combobox('getValue')+
		'&isbaddebt='+$('#repaccrec_isbaddebt').combobox('getValue')+
		'&startdate='+$('#repaccrec_startdate').datebox('getValue')+
		'&enddate='+$('#repaccrec_enddate').datebox('getValue')+
		'&per=10');
};

function downxlsrepaccrec   () {
	window.open('<?php echo $this->createUrl('repaccrec/downxls') ?>?lro='+
		$('#listrepaccrec').combobox('getValue') +
		'&company='+$('#repaccrec_companyid').combogrid('getValue')+
		'&plantid='+$('#repaccrec_plantid').combogrid('getValue')+
		'&sloc='+$('#repaccrec_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccrec_materialgroupid').combogrid('getValue')+
		'&customer='+$('#repaccrec_addressbookid').combogrid('getValue')+
		'&product='+$('#repaccrec_productid').combogrid('getValue')+
		'&sales='+$('#repaccrec_employeeid').combogrid('getValue')+
		'&spv='+$('#repaccrec_spvid').combogrid('getValue')+
		'&salesarea='+$('#repaccrec_salesareaid').combogrid('getValue')+
		'&groupcustomer='+$('#repaccrec_groupcustomerid').combogrid('getValue')+
		'&umurpiutang='+$('#repaccrec_umurpiutang').textbox('getValue')+
		'&isdisplay='+$('#repaccrec_isdisplay').combobox('getValue')+
		'&isbaddebt='+$('#repaccrec_isbaddebt').combobox('getValue')+
		'&startdate='+$('#repaccrec_startdate').datebox('getValue')+
		'&enddate='+$('#repaccrec_enddate').datebox('getValue')+
		'&per=10');
};
</script>
