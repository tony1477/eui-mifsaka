<table id="dg-repttnt" style="width:1300px;height:97%">
</table>
<div id="tb-repttnt">
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfttnt()"></a>
<?php }?>
<?php if (getUserObjectValues('rejectttnt') === 'true') {  ?>
		<a href="javascript:void(0)" title="Batal " class="easyui-linkbutton" iconCls="icon-clear" plain="true" onclick="rejectttnt()" >Batal</a>
<?php }?>
<?php if (getUserObjectValues('backttnt') === 'true') {  ?>
		<a href="javascript:void(0)" title="Balik " class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="backttnt()" >Balik</a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('id')?></td>
<td><input class="easyui-textbox" id="repttnt_search_ttntid" style="width:50px">
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="repttnt_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('docno')?></td>
<td><input class="easyui-textbox" id="repttnt_search_docno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="repttnt_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('sales')?></td>
<td><input class="easyui-textbox" id="repttnt_search_sales" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="repttnt_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchreptnt()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repttnt_search_ttntid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepttnt();
			}
		}
	})
});
$('#repttnt_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepttnt();
			}
		}
	})
});
$('#repttnt_search_docno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepttnt();
			}
		}
	})
});
$('#repttnt_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepttnt();
			}
		}
	})
});
$('#repttnt_search_sales').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepttnt();
			}
		}
	})
});
$('#repttnt_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepttnt();
			}
		}
	})
});
$('#dg-repttnt').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-repttnt',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		queryParams:{
			list:true
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-ttntdetail"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvttntdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-ttntdetail');
			ddvttntdetail.datagrid({
				url:'<?php echo $this->createUrl('repttnt/indexdetail',array('grid'=>true)) ?>?id='+row.ttntid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo GetCatalog('pleasewait') ?>',
				height:'auto',
				width:'auto',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'fullname',title:'<?php echo GetCatalog('customer') ?>'},
					{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno') ?>',
						formatter: function(value,row,index){
							if (row.wjth == 1){
								return '<div style="background-color:red;color:white">'+value+'</div>';
							} else {
								return value;
							}
							}},
					{field:'invoicedate',title:'<?php echo GetCatalog('invoicedate') ?>'},
					{field:'jatuhtempo',title:'<?php echo GetCatalog('jatuhtempo') ?>'},
					{field:'gino',title:'<?php echo GetCatalog('giheader') ?>'},
					{field:'sono',title:'<?php echo GetCatalog('soheader') ?>'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>',align:'right'},
					{field:'payamount',title:'<?php echo GetCatalog('payamount') ?>',align:'right'},
					{field:'sisa',title:'<?php echo GetCatalog('sisa') ?>',align:'right'},
				]],
				onResize:function(){
						$('#dg-repttnt').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repttnt').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repttnt').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('repttnt/index',array('grid'=>true)) ?>',
                onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repttnt').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'ttntid',
		editing: false,
		columns:[[
			{
field:'ttntid',
title:'<?php echo GetCatalog('ttntid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					if (row.recordstatus == 1) {
				return '<div style="background-color:green;color:white">'+value+'</div>';
			} else 
				if (row.recordstatus == 2) {
					return '<div style="background-color:yellow;color:black">'+value+'</div>';
				} else 
					if (row.recordstatus == 3) {
						return '<div style="background-color:red;color:white">'+value+'</div>';
					} else 
						if (row.recordstatus == 0) {
						return '<div style="background-color:black;color:white">'+value+'</div>';
					}
					}},
							{
field:'companyname',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'280px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'docno',
title:'<?php echo GetCatalog('docno') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'employeeid',
title:'<?php echo GetCatalog('sales') ?>',
sortable: true,
width:'180px',
formatter: function(value,row,index){
						return row.employeename;
					}},
{
field:'iscbin',
title:'<?php echo GetCatalog('iscbin') ?>',
align:'center',
width:'100px',
sortable: true,
formatter: function(value,row,index){
				if (value == 1){
					return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
				} else {
					return '';
				}
			}},
{
field:'iscutar',
title:'<?php echo GetCatalog('iscutar') ?>',
align:'center',
width:'110px',
sortable: true,
formatter: function(value,row,index){
				if (value == 1){
					return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
				} else {
					return '';
				}
			}},
			{
field:'isreturn',
title:'<?php echo GetCatalog('isreturn?') ?>',
align:'center',
width:'110px',
sortable: true,
formatter: function(value,row,index){
				if (value == 1){
					return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
				} else {
					return '';
				}
			}},
			{
field:'isreject',
title:'<?php echo GetCatalog('isreject?') ?>',
align:'center',
width:'110px',
sortable: true,
formatter: function(value,row,index){
				if (value == 1){
					return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
				} else {
					return '';
				}
			}},
{
field:'description',
title:'<?php echo GetCatalog('description') ?>',
width:'200px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
					{
field:'recordstatusttnt',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'160px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchrepttnt(value){
	$('#dg-repttnt').edatagrid('load',{
	ttntid:$('#repttnt_search_ttntid').val(),
        docdate:$('#repttnt_search_docdate').val(),
        docno:$('#repttnt_search_docno').val(),
        employeeid:$('#repttnt_search_sales').val(),
        description:$('#repttnt_search_description').val(),
        companyid:$('#repttnt_search_companyname').val()
	});
}

function rejectttnt() {
	var x = confirm('Apa Anda Yakin Mau Batalin TTNT ini ?');
	if(x==true) {
		openloader();
		let ss = [];
		const rows = $('#dg-repttnt').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
				let row = rows[i];
				ss.push(row.ttntid);
		}
		jQuery.ajax({'url':'<?=$this->createUrl('ttnt/cancelttnt')?>',
			'data':{'id':ss},
			'type':'post',
			'dataType':'json',
			'success':function(data)
			{
				closeloader();
				show('Pesan',data.msg);
				$('#dg-repttnt').edatagrid('reload');				
			} ,
			'cache':false
		});
	}
	else {
		console.log('no');
	}
}

function rejectttnt() {
	$.messager.confirm({
		title: 'Konfirmasi',
		msg: 'Apakah Anda Yakin Untuk Batal TTNT Ini?',
		ok: 'Ya',
		cancel: 'Tidak',
		fn: function(r){
			if (r){
				openloader();
				let ss = [];
				const rows = $('#dg-repttnt').edatagrid('getSelections');
				for(var i=0; i<rows.length; i++){
						let row = rows[i];
						ss.push(row.ttntid);
				}
				jQuery.ajax({'url':'<?=$this->createUrl('ttnt/cancelttnt')?>',
					'data':{'id':ss},
					'type':'post',
					'dataType':'json',
					'success':function(data)
					{
						closeloader();
						show('Pesan',data.msg);
						$('#dg-repttnt').edatagrid('reload');				
					} ,
					'cache':false
				});
			}
		}
	});
		//alert('confirmed: '+r);			
}

function backttnt() {
	$.messager.confirm({
		title: 'Konfirmasi',
		msg: 'TTNT Kembali / Balik?',
		ok: 'Ya',
		cancel: 'Tidak',
		fn: function(r){
			if (r){
				openloader();
				let ss = [];
				const rows = $('#dg-repttnt').edatagrid('getSelections');
				for(var i=0; i<rows.length; i++){
						let row = rows[i];
						ss.push(row.ttntid);
				}
				jQuery.ajax({'url':'<?=$this->createUrl('ttnt/backttnt')?>',
					'data':{'id':ss},
					'type':'post',
					'dataType':'json',
					'success':function(data)
					{
						closeloader();
						show('Pesan',data.msg);
						$('#dg-repttnt').edatagrid('reload');				
					} ,
					'cache':false
				});
			}
		}
	});
		//alert('confirmed: '+r);			
}

function downpdfttnt() {
	var ss = [];
	var rows = $('#dg-repttnt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttntid);
	}
	window.open('<?php echo $this->createUrl('repttnt/downpdf') ?>?id='+ss);
}
function downxlsttnt() {
	var ss = [];
	var rows = $('#dg-repttnt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttntid);
	}
	window.open('<?php echo $this->createUrl('repttnt/downxls') ?>?id='+ss);
}

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
