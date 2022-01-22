<div id="tb-repsales">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
 <table>
 <tr>
	<td><?php echo GetCatalog('reporttype')?><td>
	<td><select class="easyui-combobox" id="listrepsales" name="listrepsales" data-options="required:true" style="width:600px;">
			<option value="1">1.1.Rincian Penjualan Per Dokumen</option>
      <option value="2">1.2.Rekap Penjualan Per Dokumen</option>
      <option value="3">1.3.Rekap Penjualan Per Customer</option>
      <option value="4">1.4.Rekap Penjualan Per Sales</option>
      <option value="5">1.5.Rekap Penjualan Per Barang</option>
      <option value="6">1.6.Rekap Penjualan Per Area</option>
      <option value="7">1.7.Rekap Penjualan Per Customer Per Barang (Total)</option>
      <option value="8">1.8.Rekap Penjualan Per Customer Per Barang (Rincian)</option>
      <option value="9">1.9.Rekap Penjualan Per Sales Per Barang (Total)</option>
      <option value="10">1.10.Rekap Penjualan Per Sales Per Barang (Rincian)</option>
      <option value="11">1.11.Rekap Penjualan Per Area Per Barang (Total)</option>
      <option value="12">1.12.Rekap Penjualan Per Area Per Barang (Rincian)</option>
      <option value="40">1.13.Rekap Penjualan Per Customer Per Bulan Per Tahun</option>
      <option value="43">1.14.Rekap Penjualan Per Jenis Customer Per Bulan Per Tahun</option>
      <option value="46">1.15.Rekap Total Penjualan Per Jenis Customer Per Bulan Per Tahun</option>
      <option value="49">1.16.Rekap Penjualan Per Barang Per Bulan (Qty)</option>
      <option value="50">1.17.Rekap Penjualan Per Barang Per Bulan (Nilai)</option>
      <option value="51">1.18.Rekap Penjualan Per Barang Per Bulan (Nilai & Qty)</option>
      <option value="13">2.1.Rincian Retur Penjualan Per Dokumen</option>
      <option value="14">2.2.Rekap Retur Penjualan Per Dokumen</option>
      <option value="15">2.3.Rekap Retur Penjualan Per Customer</option>
      <option value="16">2.4.Rekap Retur Penjualan Per Sales</option>
      <option value="17">2.5.Rekap Retur Penjualan Per Barang</option>
      <option value="18">2.6.Rekap Retur Penjualan Per Area</option>
      <option value="19">2.7.Rekap Retur Penjualan Per Customer Per Barang (Total)</option>
      <option value="20">2.8.Rekap Retur Penjualan Per Customer Per Barang (Rincian)</option>
      <option value="21">2.9.Rekap Retur Penjualan Per Sales Per Barang (Total)</option>
      <option value="22">2.10.Rekap Retur Penjualan Per Sales Per Barang (Rincian)</option>
      <option value="23">2.11.Rekap Retur Penjualan Per Area Per Barang (Total)</option>
      <option value="24">2.12.Rekap Retur Penjualan Per Area Per Barang (Rincian)</option>
      <option value="41">2.13.Rekap Retur Penjualan Per Customer Per Bulan Per Tahun</option>
      <option value="44">2.14.Rekap Retur Penjualan Per Jenis Customer Per Bulan Per Tahun</option>
      <option value="47">2.15.Rekap Total Retur Penjualan Per Jenis Customer Per Bulan Per Tahun</option>
      <option value="25">3.1.Rincian Penjualan - Retur Per Dokumen</option>
      <option value="26">3.2.Rekap Penjualan - Retur Per Dokumen</option>
      <option value="27">3.3.Rekap Penjualan - Retur Per Customer</option>
      <option value="28">3.4.Rekap Penjualan - Retur Per Sales</option>
      <option value="29">3.5.Rekap Penjualan - Retur Per Barang</option>
      <option value="30">3.6.Rekap Penjualan - Retur Per Area</option>
      <option value="31">3.7.Rekap Penjualan - Retur Per Barang Customer (Total)</option>
      <option value="32">3.8.Rekap Penjualan - Retur Per Barang Per Customer (Rincian)</option>
      <option value="33">3.9.Rekap Penjualan - Retur Per Sales Per Barang (Total)</option>
      <option value="34">3.10.Rekap Penjualan - Retur Per Sales Per Barang (Rincian)</option>
      <option value="35">3.11.Rekap Penjualan - Retur Per Area (Total)</option>
      <option value="36">3.12.Rekap Penjualan - Retur Per Area (Rincian)</option>
      <option value="42">3.13.Rekap Penjualan - Retur Penjualan Per Customer Per Bulan Per Tahun</option>
      <option value="45">3.14.Rekap Penjualan Retur Penjualan Per Jenis Customer Per Bulan Per Tahun</option>
      <option value="48">3.15.Rekap Total Penjualan - Retur Penjualan Per Jenis Customer Per Bulan Per Tahun</option>
      <option value="52">3.19.Rekap Penjualan - Retur Per Customer Per Barang Per Bulan Per Tahun Total Nilai</option>
      <option value="53">3.20.Rekap Penjualan - Retur Per Customer Per Barang Per Bulan Per Tahun Rincian Nilai</option>
      <option value="54">3.21.Rekap Penjualan - Retur Per Customer Per Barang Per Bulan Per Tahun Total Qty</option>
      <option value="55">3.22.Rekap Penjualan - Retur Per Customer Per Barang Per Bulan Per Tahun Rincian Qty</option>
      <option value="58">3.23.Rekap Penjualan - Retur Per Sales Per Bulan Per Tahun Total</option>
      <option value="59">3.24.Rekap Penjualan - Retur Per Sales Per Barang Per Bulan Per Tahun Total</option>
      <option value="60">3.25.Rekap Penjualan - Retur Per Sales Per Customer Per Bulan Per Tahun Total</option>
      <option value="61">3.26.Rekap Penjualan - Retur Area, Customer, Barang Per Bulan Per Tahun Rincian Nilai</option>
      <option value="62">3.27.Rekap Penjualan - Retur Area, Customer, Barang Per Bulan Per Tahun Rincian Qty</option>
      <option value="85">3.28.Laporan Penjualan - Retur Per Cabang Per Sales Per Group Material FG</option>
      <option value="90">3.29.Rekap Penjualan - Retur Per Customer Per Jenis Material Per Bulan Per Tahun Total Nilai</option>
      <option value="37">4.1.Rincian Sales Order Per Dokumen</option>
      <option value="38">4.2.Rincian Sales Order Outstanding</option>
      <option value="56">4.3.Rekap Sales Order Outstanding Per Barang</option>
      <option value="91">4.4.Rekap Sales Order Outstanding Per Tanggal Kirim Per Barang</option>
      <option value="39">5.1.Rekap Surat Jalan Belum Dibuatkan Faktur</option>
      <option value="63">6.1.Laporan Customer Belum Lengkap Lokasi</option>
			<option value="67">6.2.Laporan Customer Sudah Lengkap Lokasi</option>
      <option value="64">6.3.Laporan Customer Belum Lengkap Foto</option>
			<option value="68">6.4.Laporan Customer Sudah Lengkap Foto</option>
      <option value="65">6.5.Laporan Customer Belum Ada KTP</option>
			<option value="69">6.6.Laporan Customer Sudah Ada KTP</option>
      <option value="66">6.7.Laporan Customer Belum Ada NPWP</option>
			<option value="70">6.8.Laporan Customer Sudah Ada NPWP</option>
			<option value="81">6.9.Laporan Customer Belum Ada Kategori Customer</option>
			<option value="82">6.10.Laporan Customer Sudah Ada Kategori Customer</option>
			<option value="83">6.11.Laporan Customer Belum Ada Grade</option>
			<option value="84">6.12.Laporan Customer Sudah Ada Grade</option>
			<option value="89">6.13.Rincian Data Customer</option>
			<!--<option value="72">7.1.Rincian Realisasi Penjualan Per Sales Per Group Material</option>-->
			<option value="71">7.2.Rekap Realisasi Penjualan Per Sales Per Group Material</option>
			<option value="86">7.3.Rekap Realisasi Penjualan SPV Per Sales Per Group Material</option>
			<option value="88">7.4.Rekap Sales Target Per Barang</option>
			<option value="73">7.5.Rekap Penjualan VS Hasil Produksi VS Saldo Akhir</option>
      <option value="57">8.1.	Rekap SO Per Dokumen Belum Status Max</option>
      <option value="74">8.2.	Rekap TTNT Per Dokumen Belum Status Max</option>
      <option value="75">8.3.	Rekap TTF Per Dokumen Belum Status Max</option>
			<option value="76">8.4.Rekap Skala Komisi Penjualan Per Dokumen Belum Status Max</option>
			<option value="77">8.5.Rekap Target Penjualan Per Dokumen Belum Status Max</option>
			<option value="78">8.6.Rekap Perubahan Plafon Per Dokumen Belum Status Max</option>
			<option value="80">9.Daily Monitoring Report</option>
	</select></td>		
</tr>

<tr>
	<td><?php echo GetCatalog('company')?><td>

		<td><select class="easyui-combogrid" id="repsales_companyid" name="repsales_companyid" style="width:600px" data-options="
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
    <td id="sales_slocid"><?php echo GetCatalog('sloc')?><td>
		<td><select class="easyui-combogrid" id="repsales_slocid" name="repsales_slocid" style="width:600px" data-options="
								panelWidth: 500,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								required:false,
								url: '<?php echo Yii::app()->createUrl('common/sloc/indextrxcom',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								onBeforeLoad: function(param) {
									param.companyid = $('#repsales_companyid').combogrid('getValue');
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
    <td id="sales_materialgroupid"><?php echo GetCatalog('materialgroup')?><td>
		<td><select class="easyui-combogrid" id="repsales_materialgroupid" name="repsales_materialgroupid" style="width:600px" data-options="
								panelWidth: 500,
								idField: 'description',
								textField: 'description',
								pagination:true,
								mode:'remote',
								required:false,
								url: '<?php echo Yii::app()->createUrl('common/materialgroup/indexfg',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'materialgroupid',title:'<?php echo GetCatalog('materialgroupid') ?>'},
										{field:'materialgroupcode',title:'<?php echo GetCatalog('materialgroupcode') ?>'},
										{field:'description',title:'<?php echo GetCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
	</tr>
<tr>		
    <td id="sales_addressbookid"><?php echo GetCatalog('customer')?><td>
		<td><select class="easyui-combogrid" id="repsales_addressbookid" name="repsales_addressbookid" style="width:600px" data-options="
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
				</select></td>
 </tr>
 <tr>
		<td id="sales_employeeid"><?php echo GetCatalog('sales')?><td>
		<td><select class="easyui-combogrid" id="repsales_employeeid" name="repsales_employeeid" style="width:600px" data-options="
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
				</select></td>
	</tr>
 <tr>
		<td id="sales_spvid"><?php echo GetCatalog('salesspv')?><td>
		<td><select class="easyui-combogrid" id="repsales_spvid" name="repsales_spvid" style="width:600px" data-options="
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
				</select></td>
	</tr>
	<tr>
    <td id="sales_productid"><?php echo GetCatalog('product')?><td>
		<td><select class="easyui-combogrid" id="repsales_productid" name="repsales_productid" style="width:600px" data-options="
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
    <td id="sales_salesareaid"><?php echo GetCatalog('salesarea')?><td>
		<td><select class="easyui-combogrid" id="repsales_salesareaid" name="repsales_salesareaid" style="width:600px" data-options="
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
				</select></td>
	</tr>
	<tr>
		<td id="sales_isdisplay"><?php echo GetCatalog('indisplay')?><td>
		<td><select class="easyui-combobox" id="repsales_isdisplay" name="repsales_isdisplay" data-options="required:true" style="width:100px;">
			<option value="0">No</option>
      <option value="1">Yes</option>
      <option value="">All</option>
		</select></td>		
	</tr>
	<tr>
		<td><?php echo GetCatalog('date')?><td>
		<td><input class="easyui-datebox" id="repsales_startdate" name="repsales_startdate" data-options="required:true"></input>
		-
		<input class="easyui-datebox" id="repsales_enddate" name="repsales_enddate" data-options="required:true"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepsales()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsrepsales()"></a>
</tr>
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

var repsalessloc =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","42","49","50","51","52","53","54","55","56","58","59","60","61","62","80","85","90"];
var repsalesmaterialgroup =  ["5","17","29","85"];
var repsalescustomer =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","49","50","51","52","53","54","55","56","57","58","59","60","61","62","63","64","67","68","81","82","83","84","85","89","90","91"];
var repsalessales =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","49","50","51","52","53","54","55","56","57","58","59","60","61","62","71","72","73","85","90","91"];
var repsalesspv =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","61","62","79","86","90"];
var repsalesproduct =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","49","50","51","52","53","54","55","56","58","59","60","61","62","85","90","91"];
var repsalessalesarea =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","42","43","49","50","51","52","53","54","55","56","58","59","60","61","62","63","64","65","67","68","81","82","83","84","85","89","90"];
var repsalesdisplay =  ["1","2","3","4","5","6","25","26","27","28","29","30","31","32","33","34","35","36","37","38","42","45","48","52","80"];
var repsalesstart =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","57","61","62","63","64","67","68","74","75","76","77","78","80","85","87","88","90","91"];
var repsalesend =  ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","57","58","59","60","61","62","63","64","65","66","67","68","69","70","71","72","73","74","75","76","77","78","79","80","81","82","83","84","85","86","87","88","89","90","91"];

$(document).ready(function(){
    $('#listrepsales').combobox({
			onSelect: function(row){
				var target = this;
				setTimeout(function(){
					var n = $('#listrepsales').combobox('getValue');
					if(repsalessloc.includes(n)) {
							$('#repsales_slocid').combobox('show');
							$("#sales_slocid").show();
						}
						else {
							//alert('tidak ada');
							$('#repsales_slocid').combobox('hide');
							$("#sales_slocid").hide();
						}
					if(repsalesmaterialgroup.includes(n)) {
							$('#repsales_materialgroupid').combobox('show');
							$("#sales_materialgroupid").show();
						}
						else {
							//alert('tidak ada');
							$('#repsales_materialgroupid').combobox('hide');
							$("#sales_materialgroupid").hide();
						}
					if(repsalescustomer.includes(n)) {
							$('#repsales_addressbookid').combobox('show');
							$("#sales_addressbookid").show();
						}
						else {
							//alert('tidak ada');
							$('#repsales_addressbookid').combobox('hide');
							$("#sales_addressbookid").hide();
						}
					if(repsalessales.includes(n)) {
							$('#repsales_employeeid').combobox('show');
							$("#sales_employeeid").show();
						}
						else {
							//alert('tidak ada');
							$('#repsales_employeeid').combobox('hide');
							$("#sales_employeeid").hide();
						}
					if(repsalesspv.includes(n)) {
							$('#repsales_spvid').combobox('show');
							$("#sales_spvid").show();
						}
						else {
							//alert('tidak ada');
							$('#repsales_spvid').combobox('hide');
							$("#sales_spvid").hide();
						}
					if(repsalesproduct.includes(n)) {
							$('#repsales_productid').combobox('show');
							$("#sales_productid").show();
						}
						else {
							//alert('tidak ada');
							$('#repsales_productid').combobox('hide');
							$("#sales_productid").hide();
						}
					if(repsalessalesarea.includes(n)) {
							$('#repsales_salesareaid').combobox('show');
							$("#sales_salesareaid").show();
						}
						else {
							//alert('tidak ada');
							$('#repsales_salesareaid').combobox('hide');
							$("#sales_salesareaid").hide();
						}
					if(repsalesdisplay.includes(n)) {
							$('#repsales_isdisplay').combobox('show');
							$("#sales_isdisplay").show();
						}
						else {
							//alert('tidak ada');
							$('#repsales_isdisplay').combobox('hide');
							$("#sales_isdisplay").hide();
						}
					if(repsalesstart.includes(n)) {
							$('#repsales_startdate').combobox('show');
						}
						else {
							//alert('tidak ada');
							$('#repsales_startdate').combobox('hide');
						}
					if(repsalesend.includes(n)) {
							$('#repsales_enddate').combobox('show');
						}
						else {
							//alert('tidak ada');
							$('#repsales_enddate').combobox('hide');
						}
				},0);
      }
    })
});

function downpdfrepsales() {
	window.open('<?php echo $this->createUrl('reportorder/downpdf') ?>?lro='+
		$('#listrepsales').combobox('getValue') +
		'&company='+$('#repsales_companyid').combogrid('getValue')+
		'&sloc='+$('#repsales_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repsales_materialgroupid').combogrid('getValue')+
		'&customer='+$('#repsales_addressbookid').combogrid('getValue')+
		'&sales='+$('#repsales_employeeid').combogrid('getValue')+
		'&spvid='+$('#repsales_spvid').combogrid('getValue')+
		'&product='+$('#repsales_productid').combogrid('getValue')+
		'&salesarea='+$('#repsales_salesareaid').combogrid('getValue')+
		'&isdisplay='+$('#repsales_isdisplay').combobox('getValue')+
		'&startdate='+$('#repsales_startdate').datebox('getValue')+
		'&enddate='+$('#repsales_enddate').datebox('getValue')+
		'&per=10');

};

function downxlsrepsales() {
	window.open('<?php echo $this->createUrl('reportorder/downxls') ?>?lro='+
		$('#listrepsales').combobox('getValue') +
		'&company='+$('#repsales_companyid').combogrid('getValue')+
		'&sloc='+$('#repsales_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repsales_materialgroupid').combogrid('getValue')+
		'&customer='+$('#repsales_addressbookid').combogrid('getValue')+
		'&sales='+$('#repsales_employeeid').combogrid('getValue')+
		'&spvid='+$('#repsales_spvid').combogrid('getValue')+
		'&product='+$('#repsales_productid').combogrid('getValue')+
		'&salesarea='+$('#repsales_salesareaid').combogrid('getValue')+
		'&isdisplay='+$('#repsales_isdisplay').combobox('getValue')+
		'&startdate='+$('#repsales_startdate').datebox('getValue')+
		'&enddate='+$('#repsales_enddate').datebox('getValue')+
		'&per=10');

};

</script>
