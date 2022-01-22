<div id="tb-reportacc">
<input type="hidden" name="startacccode" id="startacccode" value="" />
<input type="hidden" name="endacccode" id="endacccode" value="" />
<script type="text/javascript">
$(document).ready(function(){
  $('#row-acccode-plant').hide();
  $('#row-acccode').hide();
  //var startacccode=null;
  //var endacccode=null;
  var plant = ['1','2','5','6','7','8','9','10','11','12','13','29'];
  var acccode = ['1','2'];
  var accname = ['1','2'];
  var employee = ['20','21','22','23','24'];
  var supplier = ['25','27'];
  var customer = ['9','26','28'];
  var startdate = ['29'];
  
    $('#listreportacc').combobox({
        onSelect: function(row){
          var target = this;
          setTimeout(function(){
          var select = $('#listreportacc').combobox('getValue');
    //$("select[name='listreportacc']").on('change', function() {
    //console.log('test');
      if(plant.includes(select)) {
            //$("input[name='productname']").hide();
            $('#row-plantid').show();
            //$('#reportacc_plantid').combogrid('show');
            //console.log('show');
        }
        else {
            $('#row-plantid').hide();
            //$('#reportacc_plantid').combogrid('hide');
            //console.log('hide');
            //$("input[name='productname']").show();
        }
      if(acccode.includes(select)) {
            //$("input[name='productname']").hide();
            $('#row-acccode').show();
            //$('#reportacc_startacccode').combogrid('show');
            //$('#reportacc_endacccode').combogrid('show');
        }
        else {
            $('#row-acccode').hide();
            //$('#reportacc_startacccode').combogrid('hide');
            //$('#reportacc_endacccode').combogrid('hide');
            //$("input[name='productname']").show();
        }
      if(accname.includes(select)) {
            //$("input[name='productname']").hide();
            $('#row-accname').show();
            //$('#reportacc_accountid').combogrid('show');
        }
        else {
            $('#row-accname').hide();
            //$('#reportacc_accountid').combogrid('hide');
            //$("input[name='productname']").show();
        }
      if(employee.includes(select)) {
            //$("input[name='productname']").hide();
            $('#row-employee').show();
            //$('#reportacc_employeeid').combogrid('show');
        }
        else {
            $('#row-employee').hide();
            //$('#reportacc_employeeid').combogrid('hide');
            //$("input[name='productname']").show();
        }
      if(supplier.includes(select)) {
            //$("input[name='productname']").hide();
            $('#row-supplier').show();
            //$('#reportacc_supplierid').combogrid('show');
        }
        else {
            $('#row-supplier').hide();
            //$('#reportacc_supplierid').combogrid('hide');
            //$("input[name='productname']").show();
        }
      if(customer.includes(select)) {
            //$("input[name='productname']").hide();
            $('#row-customer').show();
            //$('#reportacc_addressbookid').combogrid('show');
        }
        else {
            $('#row-customer').hide();
            //$('#reportacc_addressbookid').combogrid('hide');
            //$("input[name='productname']").show();
        }
      if(startdate.includes(select)) {
            //$("input[name='productname']").hide();
            $('#row-startdate').hide();
            //$('#reportacc_addressbookid').combogrid('show');
        }
        else {
            $('#row-startdate').show();
        }
        },0);
    }
    })
  
    $('#reportacc_plantid').combogrid({
        onChange: function(newValue,oldValue){
          console.log(newValue);
          console.log(oldValue);
          $('#reportacc_endacccode').combogrid('setValue','');
          $('#reportacc_startacccode').combogrid('setValue','');
          var plantid = newValue;
          if(plantid!=''){
              //$('#row-acccode-plant').show();
              //$('#row-acccode').hide();
              var startacccode = '3';
              var endacccode = '999999999999999';
              $('#startacccode').val('3');
              $('#endacccode').val('9999999999999');
          }
          else {
              //$('#row-acccode').show();
              //$('#row-acccode-plant').hide();
              var startacccode = null;
              var endacccode = null;
              $('#startacccode').val('');
              $('#endacccode').val('');
          }
        }
    });
});
  </script>
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
 <table>
 <tr>
 <td><?php echo GetCatalog('reporttype')?></td>
 <td><select class="easyui-combobox" id="listreportacc" name="listreportacc" data-options="required:true" style="width:450px;">
		<option value="1">1.Rincian Jurnal Transaksi</option>
		<option value="2">2.Buku Besar</option>
		<option value="29">3.Laporan Cash & Bank Harian</option>
		<!-- <option value="3">3.Neraca - Uji Coba</option>
		<option value="4">4.Laba (Rugi) - Uji Coba</option> -->
		<option value="5">5.Rincian Umur Piutang Cek/Giro</option>
		<option value="6">6.Rekap Umur Piutang Cek/Giro</option>
		<option value="7">7.Rincian Cek/Giro Cair - Ekstern</option>
		<option value="8">8.Rincian Cek/Giro Tolak - Ekstern</option>
		<option value="9">9.Rincian Cek/Giro Opname - Ekstern</option>
		<option value="10">10.Rincian Umur Hutang Cek/Giro</option>
		<option value="11">11.Rekap Umur Hutang Cek/Giro</option>
		<option value="12">12.Rincian Cek/Giro Cair - Intern</option>
		<option value="13">13.Rincian Cek/Giro Tolak - Intern</option>
		<!--<option value="18">14.Lampiran Neraca 1</option>-->
		<option value="20">15.Rincian Piutang Karyawan</option>
		<option value="21">16.Rincian Hutang Deposito Staff</option>
		<option value="22">17.Rincian Hutang Deposito Sales</option>
		<option value="23">18.Rincian Hutang Deposito SPV</option>
		<option value="24">19.Rincian Hutang Deposito BM</option>
		<option value="30">20.Rincian Hutang Finalty Tagihan Sales/SPV</option>
		<option value="25">21.Rincian Uang Muka Pembelian</option>
		<option value="26">22.Rincian Uang Muka Penjualan</option>
		<option value="27">23.Rincian Hutang Ekspedisi</option>
		<option value="28">24.Rincian Cadangan Insentif Toko</option>
		<option value="14">25.Rekap Jurnal Umum Per Dokumen Belum Status Max</option>
		<option value="15">26.Rekap Penerimaan Kas/Bank Per Dokumen Belum Status Max</option>
		<option value="16">27.Rekap Pengeluaran Kas/Bank Per Dokumen Belum Status Max</option>
		<option value="17">28.Rekap Cash Bank Per Dokumen Belum Status Max</option>
    </select>		<br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('company')?></td>
 <td><select class="easyui-combogrid" id="reportacc_companyid" name="reportacc_companyid" style="width:450px" data-options="
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
				</select><br/></td>
 </tr>
 <div>
 <tr id="row-plantid">
 <td><?php echo GetCatalog('plant')?></td>
 <td><select class="easyui-combogrid" id="reportacc_plantid" name="reportacc_plantid" style="width:450px" data-options="
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
                                     param.companyid = $('#reportacc_companyid').combogrid('getValue');
                                },
								method: 'get',
								columns: [[
										{field:'plantid',title:'<?php echo GetCatalog('slocid') ?>'},
										{field:'plantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
								]],
								fitColumns: true
						">
						</select><br/></td>
 </tr>
 </div>
 <!--<tr>
 <td><?php echo GetCatalog('sloc')?></td>
 <td><select class="easyui-combogrid" id="reportacc_slocid" name="reportacc_slocid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'sloccode',
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
						</select><br/></td>
 </tr>
 <tr>
 <td><?php echo GetCatalog('materialgroup')?></td>
 <td><select class="easyui-combogrid" id="reportacc_materialgroupid" name="reportacc_materialgroupid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'materialgroupcode',
								textField: 'materialgroupcode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/materialgroup/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns:[[
									{field:'materialgroupid',title:'<?php echo GetCatalog('unitofmeasureid')?>',width:50},
									{field:'materialgroupcode',title:'<?php echo GetCatalog('materialgroupcode')?>',width:50},
									{field:'description',title:'<?php echo GetCatalog('description')?>',width:200},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>
-->
 <div >
 <tr id="row-customer">
 <td><?php echo GetCatalog('customer')?></td>
 <td><select class="easyui-combogrid" id="reportacc_addressbookid" name="reportacc_addressbookid" style="width:450px" data-options="
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
				</select><br/></td>
 </tr>
 </div>
 <div >
 <tr id="row-supplier">
 <td><?php echo GetCatalog('supplier')?></td>
 <td><select class="easyui-combogrid" id="reportacc_supplierid" name="reportacc_supplierid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'fullname',
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('hr/addressbook/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>
 </div>
 <div >
 <tr id="row-employee">
 <td><?php echo GetCatalog('employee')?></td>
 <td><select class="easyui-combogrid" id="reportacc_employeeid" name="reportacc_employeeid" style="width:450px" data-options="
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
				</select><br/></td>
 </tr>
 </div>
 <!--
 <tr>
 <td><?php echo GetCatalog('product')?></td>
 <td><select class="easyui-combogrid" id="reportacc_productid" name="reportacc_productid" style="width:450px" data-options="
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
				</select><br/></td>
 </tr>
  -->
 <div >
 <tr id="row-accname">
 <td><?php echo GetCatalog('accountname')?></td>
 <td><select class="easyui-combogrid" id="reportacc_accountid" name="reportacc_accountid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'accountname',
								textField: 'accountname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
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
 </div>
 <div >
 <tr id="row-acccode">
 <td><?php echo GetCatalog('accountcode')?></td>
 <td><select class="easyui-combogrid" id="reportacc_startacccode" name="reportacc_startacccode" style="width:213px" data-options="
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
				</select> s/d <select class="easyui-combogrid" id="reportacc_endacccode" name="reportacc_endacccode" style="width:215px" data-options="
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
 </div>
 <tr>
 <td><?php echo GetCatalog('date')?></td>
 <td><span id='row-startdate'><input class="easyui-datebox" id="reportacc_startdate" name="reportacc_startdate" data-options="required:true" size="31"></input> - </span>
		<input class="easyui-datebox" id="reportacc_enddate" name="reportacc_enddate" data-options="required:true" size="32"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreportacc()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsreportacc()"></a></td>
 </tr>

 
 

 </table>	
		
<?php }?>
</div>

<script type="text/javascript">
  $companyid,$plantid,$sloc,$materialgroup,$customer,$supplier,$employee,$product,$account,$startaccode,$endacccode,$startdate,$enddate,$per
function downpdfreportacc   () {
    var n = $('#reportacc_plantid').combogrid('getValue');
    
      var acccodestart = $('#reportacc_startacccode').combogrid('getValue');
      var acccodeend = $('#reportacc_endacccode').combogrid('getValue');
 
	window.open('<?php echo $this->createUrl('reportacc/downpdf') ?>?lro='+
		$('#listreportacc').combobox('getValue') +
		'&company='+$('#reportacc_companyid').combogrid('getValue')+
		'&plant='+$('#reportacc_plantid').combogrid('getValue')+
		'&sloc='+
		'&materialgroup='+
		'&customer='+$('#reportacc_addressbookid').combogrid('getValue')+
        '&supplier='+$('#reportacc_supplierid').combogrid('getValue')+
		'&employee='+$('#reportacc_employeeid').combogrid('getValue')+
		'&product='+
		'&account='+$('#reportacc_accountid').combogrid('getValue')+
		'&startacccode='+acccodestart+
		'&endacccode='+acccodeend+
		'&startdate='+$('#reportacc_startdate').datebox('getValue')+
		'&enddate='+$('#reportacc_enddate').datebox('getValue')+
		'&per=10');
};

function downxlsreportacc   () {
    var n = $('#reportacc_plantid').combogrid('getValue');
    
      var acccodestart = $('#reportacc_startacccode').combogrid('getValue');
      var acccodeend = $('#reportacc_endacccode').combogrid('getValue');
  
	window.open('<?php echo $this->createUrl('reportacc/downxls') ?>?lro='+
		$('#listreportacc').combobox('getValue') +
		'&company='+$('#reportacc_companyid').combogrid('getValue')+
		'&plant='+$('#reportacc_plantid').combogrid('getValue')+
		'&sloc='+
		'&materialgroup='+
		'&customer='+$('#reportacc_addressbookid').combogrid('getValue')+
        '&supplier='+$('#reportacc_supplierid').combogrid('getValue')+
		'&employee='+$('#reportacc_employeeid').combogrid('getValue')+
		'&product='+
		'&account='+$('#reportacc_accountid').combogrid('getValue')+
		'&startacccode='+acccodestart+
		'&endacccode='+acccodeend+
		'&startdate='+$('#reportacc_startdate').datebox('getValue')+
		'&enddate='+$('#reportacc_enddate').datebox('getValue')+
		'&per=10');
};
</script>
