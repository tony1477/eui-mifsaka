<div id="tb-repaccpers">
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	
    <table >
        <tr>
            <td><?php echo GetCatalog('reporttype')?></td>
            <td> <select class="easyui-combobox" id="listrepaccpers" name="listrepaccpers" data-options="required:true" style="width:600px;">
				<option value="1">1.Rekap Persediaan (Detail)</option>
				<option value="2">2.Rekap Penerimaan Persediaan (Detail)</option>
				<option value="3">3.Rekap Pengeluaran Persediaan (Detail)</option>
				<option value="21">4.HPP Actual Hasil Produksi</option>
				<option value="4">5.HPP</option>
				<option value="5">6.HPP Berdasarkan BOM</option> 
				<option value="6">7.Rincian Nilai Pemakaian Stok - Data Harga</option>
				<option value="7">8.Rekap Nilai Pemakaian Stok - Data Harga</option>
				<option value="8">9.Rincian Nilai Stock Opname</option>
				<option value="9">10.Rekap Nilai Stock Opname</option>
				<option value="10">11.Rincian Harga Pokok Penjualan - Data Harga</option>
				<option value="11">12.Rekap Harga Pokok Penjualan - Data Harga</option>
				<option value="13">13.Rekap Perbandingan Nilai HPP, Nilai Penjualan dan Nilai Jurnal Per Dokumen</option>
<!--				<option value="14">14.Rekap Perbandingan Nilai HPP, Nilai Retur Penjualan dan Nilai Jurnal Per Dokumen</option>
				<option value="23">15.Rekap Perbandingan Nilai HPP, Nilai Penjualan - Retur dan Nilai Jurnal Per Dokumen</option>
-->				<option value="15">16.1.Rekap Perbandingan Nilai HPP dan Nilai Penjualan Per Barang</option>
				<option value="33">16.2.Rekap Perbandingan Nilai HPP dan Nilai Penjualan Per Kasta Per Barang</option>
				<option value="37">16.3.Rekap Perbandingan Nilai HPP dan Nilai Penjualan Per Kasta Per Group Material Per Barang</option>
				<option value="38">16.4.Rekap Perbandingan Nilai HPP dan Nilai Penjualan-Retur Per Kasta Per Group Material Per Barang</option>
				<option value="39">16.5.Rekap Perbandingan Nilai HPP dan Nilai Penjualan-Retur Per Provinsi Per Zona Per Subzona Per Customer Per Material Group</option>
				<option value="42">16.6.Rekap Perbandingan Nilai HPP dan Nilai Penjualan-Retur Per Provinsi Per Zona Per Subzona Per Customer</option>
				<option value="28">17.Rekap Perbandingan Nilai HPP dan Nilai Penjualan Per Customer Per Barang</option>
				<option value="16">18.1.Rekap Persediaan Barang Not Moving FG</option>
				<option value="36">18.2.Rekap Persediaan Barang Not Moving BB / WIP</option>
				<option value="17">19.Rekap Persediaan Barang Slow Moving</option>
				<option value="18">20.Rekap Persediaan Barang Fast Moving</option>
				<option value="19">21.Kartu Stok Barang (Nilai)</option>
				<option value="24">22.1.HPP Actual Hasil Produksi VS BOM</option>
				<option value="48">22.2.Rekap HPP Actual Hasil Produksi VS BOM</option>
				<option value="30">30.Rincian Harga Pemakaian Barang</option>
				<option value="31">31.Rincian Biaya UL Barang</option>
				<option value="32">32.Rincian Biaya FOH Barang</option>
				<option value="35">33.Rekap Persediaan Bahan Baku, Setengah Jadi & Barang Jadi</option>
				<option value="40">34.Rekap Potensi Market, Penjualan - Retur, Pembayaran, & GM Per Tipe Per Kategori Per Customer</option>
				<option value="43">35.Rekap Potensi Market, Target, Penjualan - Retur, Pembayaran, & GM Per Zona Per Subzona Per Tipe Per Kategori Per Customer</option>
				<option value="44">36.Rekap Potensi Market, Target, Penjualan - Retur, Pembayaran, & GM Per Zona Per Subzona Per Customer</option>
				<option value="41">37.Harga Bahan Baku Kimia Per Cabang</option>
    </select></td>
        </tr>
        <tr>
            <td><?php echo GetCatalog('company')?></td>
            <td><select class="easyui-combogrid" id="repaccpers_companyid" name="repaccpers_companyid" style="width:600px" data-options="
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
            <td><select class="easyui-combogrid" id="repaccpers_slocid" name="repaccpers_slocid" style="width:600px" data-options="
								panelWidth: 308,
								idField: 'sloccode',
								textField: 'sloccode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/sloc/indextrxcom',array('grid'=>true,'grid'=>true)) ?>',
								method: 'get',
								onBeforeLoad: function(param) {
									param.companyid = $('#repaccpers_companyid').combogrid('getValue');
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
 			<td>
 				<?php echo GetCatalog('materialgroup')?>
 			</td>
 			<td> 				
				<select class="easyui-combogrid" id="repaccpers_materialgroupid" name="repaccpers_materialgroupid" style="width:600px" data-options="
								panelWidth: 500,
								idField: 'description',
								textField: 'description',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/materialgroup/indextrx',array('grid'=>true)) ?>',
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
            <td><?php echo GetCatalog('storagebin')?></td>
            <td><select class="easyui-combogrid" id="repaccpers_storagebinid" name="repaccpers_storagebinid" style="width:600px" data-options="
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
            <td><select class="easyui-combogrid" id="repaccpers_productid" name="repaccpers_productid" style="width:600px" data-options="
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
      <td><?php echo GetCatalog('productcollection')?></td>
      <td>
        <select class="easyui-combogrid" id="reppurchase_productcollectid" name="reppurchase_productcollectid" style="width:320px" data-options="
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
            <td><?php echo GetCatalog('accountname')?></td>
            <td><select class="easyui-combogrid" id="repaccpers_accountid" name="repaccpers_accountid" style="width:600px" data-options="
								panelWidth: 308,
								idField: 'accountname',
								textField: 'accountname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'accountid',title:'<?php echo GetCatalog('accountid') ?>'},
										{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
        </tr>
 <tr>
 <td><?php echo GetCatalog('accountcode')?></td>
 <td><select class="easyui-combogrid" id="repaccpers_startacccode" name="repaccpers_startacccode" style="width:290px" data-options="
								panelWidth: 500,
								idField: 'accountcode',
								textField: 'accountcode',
								pagination:true,
								mode:'remote',
								sortable: true,
								url: '<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'params'=>true)) ?>',
								method: 'get',
                                onBeforeLoad: function(param) {
                                    var startacccode=$('#startacccode').val();
                                    var endacccode=$('#endacccode').val();
                                    if(startacccode!='' || startacccode!=null) {
                                        param.startcodeacc=startacccode;
                                        param.endcodeacc = endacccode;
                                    }
                                    else {
                                        console.log(startacccode);
                                    }
                                },
								columns: [[
										{field:'accountid',title:'<?php echo GetCatalog('accountid') ?>',sortable: true,},
										{field:'accountcode',title:'<?php echo GetCatalog('accountcode') ?>',sortable: true,},
										{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',sortable: true,},
										{field:'companyname',title:'<?php echo GetCatalog('company') ?>',sortable: true,},
								]],
								fitColumns: true
						"> 
				</select> s/d <select class="easyui-combogrid" id="repaccpers_endacccode" name="repaccpers_endacccode" style="width:290px" data-options="
								panelWidth: 500,
								idField: 'accountcode',
								textField: 'accountcode',
								pagination:true,
								mode:'remote',
								sortable: true,
								url: '<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'params'=>true)) ?>',
								method: 'get',
                                onBeforeLoad: function(param) {
                                    var startacccode=$('#startacccode').val();
                                    var endacccode=$('#endacccode').val();
                                    if(startacccode!='' || startacccode!=null) {
                                        param.startcodeacc=startacccode;
                                        param.endcodeacc = endacccode;
                                    }
                                    else {
                                        console.log(startacccode);
                                    }
                                },
								columns: [[
										{field:'accountid',title:'<?php echo GetCatalog('accountid') ?>',sortable: true,},
										{field:'accountcode',title:'<?php echo GetCatalog('accountcode') ?>',sortable: true,},
										{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',sortable: true,},
										{field:'companyname',title:'<?php echo GetCatalog('company') ?>',sortable: true,},
								]],
								fitColumns: true
						"> 
				</select><br/></td>
 </tr>
          <tr>
            <td> <?php echo getCatalog('Qty Keluar')?></td>
            <td><input class="easyui-box" id="keluar3" name="keluar3" style="width:600px" data-options="required:true"></input></td>
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
function makeUnique(str) {
  let uniqueNames = [];
  $.each(str, function(i, val){
    if($.inArray(val, uniqueNames) === -1) uniqueNames.push(val);
  });
  return uniqueNames.join();
}
function downpdfrepaccpers  () {
  let productcollectid = makeUnique($('#reppurchase_productcollectid').combogrid('getValues'));
	window.open('<?php echo $this->createUrl('repaccpers/downpdf') ?>?lro='+
		$('#listrepaccpers').combobox('getValue') +
		'&company='+$('#repaccpers_companyid').combogrid('getValue')+
		'&sloc='+$('#repaccpers_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccpers_materialgroupid').combogrid('getValue')+
		'&storagebin='+$('#repaccpers_storagebinid').combogrid('getValue')+
		'&product='+$('#repaccpers_productid').combogrid('getValue')+
    '&productcollect='+productcollectid+
		'&account='+$('#repaccpers_accountid').combogrid('getValue')+
		'&startacccode='+$('#repaccpers_startacccode').combogrid('getValue')+
		'&endacccode='+$('#repaccpers_endacccode').combogrid('getValue')+
        '&keluar3='+$("input[name='keluar3']").val()+
		'&startdate='+$('#repaccpers_startdate').datebox('getValue')+
		'&enddate='+$('#repaccpers_enddate').datebox('getValue')+
		'&per=10');
};

function downxlsrepaccpers   () {
  let productcollectid = makeUnique($('#reppurchase_productcollectid').combogrid('getValues'));
	window.open('<?php echo $this->createUrl('repaccpers/downxls') ?>?lro='+
		$('#listrepaccpers').combobox('getValue') +
		'&company='+$('#repaccpers_companyid').combogrid('getValue')+
		'&sloc='+$('#repaccpers_slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccpers_materialgroupid').combogrid('getValue')+
		'&storagebin='+$('#repaccpers_storagebinid').combogrid('getValue')+
		'&product='+$('#repaccpers_productid').combogrid('getValue')+
    '&productcollect='+productcollectid+
		'&account='+$('#repaccpers_accountid').combogrid('getValue')+
		'&startacccode='+$('#repaccpers_startacccode').combogrid('getValue')+
		'&endacccode='+$('#repaccpers_endacccode').combogrid('getValue')+
        '&keluar3='+$("input[name='keluar3']").val()+
		'&startdate='+$('#repaccpers_startdate').datebox('getValue')+
		'&enddate='+$('#repaccpers_enddate').datebox('getValue')+
		'&per=10');
};
</script>
