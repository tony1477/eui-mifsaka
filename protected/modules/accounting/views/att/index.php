<table id="dg-att"  style="width:100%;height:100%"></table>
<div id="tb-att">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addatt()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editatt()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-att').edatagrid('destroyRow')"></a>
<?php }?>
	<select class="easyui-combogrid" id="plcompany" name="plcompany" style="width:250px" data-options="
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
								fitColumns: true,
								prompt:'Company'
						">
				</select>
		<input class="easyui-datebox" type="text" id="pldate" name="pldate" data-options="formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'" />
        <br />
        <select class="easyui-combogrid" id="plplantid" name="plplantid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'plantid',
								textField: 'plantcode',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('common/plant/index',array('grid'=>true)) ?>',
								queryParams:{
									trxcom:true
								},
                                onBeforeLoad: function(param) {
                                     param.companyid = $('#plcompany').combogrid('getValue');
                                },
								method: 'get',
								columns: [[
										{field:'plantid',title:'<?php echo GetCatalog('plantid') ?>'},
										{field:'plantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
										{field:'description',title:'<?php echo GetCatalog('description') ?>'},
								]],
								fitColumns: true,
								prompt:'Plant'
						">
				</select>
    <?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="PDF Lampiran Neraca"class="easyui-linkbutton" iconCls="icon-balance" plain="true" onclick="downpdfneraca()"></a>
		<a href="javascript:void(0)" title="Excel Lampiran Neraca"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsneraca()"></a>
        <a href="javascript:void(0)" title="PDF Lampiran Neraca 1"class="easyui-linkbutton" iconCls="icon-balance" plain="true" onclick="downpdfneraca1()"></a>
		<a href="javascript:void(0)" title="Excel Lampiran Neraca 1"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsneraca1()"></a>
		<a href="javascript:void(0)" title="PDF Lampiran Laba / Rugi"class="easyui-linkbutton" iconCls="icon-graph" plain="true" onclick="downpdfpl()"></a>
		<a href="javascript:void(0)" title="Excel Lampiran Laba / Rugi"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlspl()"></a>
		<a href="javascript:void(0)" title="PDF Lampiran Laba / Rugi + Budget"class="easyui-linkbutton" iconCls="icon-graph" plain="true" onclick="downpdfpl1()"></a>
		<a href="javascript:void(0)" title="Excel Lampiran Laba / Rugi + Budget"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlspl1()"></a>
    <a href="javascript:void(0)" title="PDF Lampiran Neraca Tahunan"class="easyui-linkbutton" iconCls="icon-balance" plain="true" onclick="downpdfneracayear()"></a>
    <a href="javascript:void(0)" title="Excel Lampiran Neraca Tahunan"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsneracayear()"></a>
		<a href="javascript:void(0)" title="PDF Lampiran Laba / Rugi Tahunan"class="easyui-linkbutton" iconCls="icon-graph" plain="true" onclick="downpdfplyear()"></a>
		<a href="javascript:void(0)" title="Excel Lampiran Laba / Rugi Tahunan"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsplyear()"></a>
<?php }?>
    <p></p>
<table>
<tr>
<td><?php echo GetCatalog('attid')?></td>
<td><input class="easyui-textbox" id="att_search_attid" style="width:150px"></td>
<td><?php echo GetCatalog('company')?></td>
<td><input class="easyui-textbox" id="att_search_company" style="width:150px"></td>
<td><?php echo GetCatalog('reportplaccount')?></td>
<td><input class="easyui-textbox" id="att_search_accountname" style="width:150px"></td>
<td><?php echo GetCatalog('isneraca')?></td>
<td><input type="checkbox" name="isneraca" id="isneraca" style="padding:8px;"/>
<a href="javascript:void(0)" title="Cari" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchatt()"></a></td>
</tr>
</table>
</div>

<div id="dlg-att" class="easyui-dialog" title="<?php echo GetCatalog('att') ?>" style="width:200;height:450" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormatt();
			}
		},
		
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-att').dialog('close');
			}
		},
	]	
	">
	<form id="ff-att-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="attid" id="attid"/>
	<input type="hidden" name="accname" id="accname"/>
	<input type="hidden" name="accdetailid" id="accdetailid"/>
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('companyname')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'companyid',
								required: true,
								textField: 'companyname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
                columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>',width:80},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
            <tr>
				<td><?php echo GetCatalog('reportplaccount')?></td>
				<td><select class="easyui-combogrid" id="accountid" name="accountid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'accountid',
								required: true,
								textField: 'accountname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('accounting/account/accountcompany',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
                                onBeforeLoad: function(param) {
							         param.companyid = $('#companyid').combogrid('getValue');
						        },
                                columns: [[
										{field:'accountid',title:'<?php echo GetCatalog('accountid') ?>',width:35},
										{field:'accountcode',title:'<?php echo GetCatalog('accountcode') ?>',width:50},
										{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',width:80},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('isneraca')?></td>
				<td><input id="isneraca" type="checkbox" name="isneraca" style="width:250px"/></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('nourut')?></td>
				<td><input class="easyui-textbox" id="nourut" name="nourut" data-options="required:true" /></td>
			</tr>
            <tr>
				<td><?php echo GetCatalog('recordstatus')?></td>
				<td><input id="recordstatus" type="checkbox" name="recordstatus" style="width:250px"/></td>
			</tr>
		</table>
	</form>
	<div class="easyui-tabs" style="width:auto;height:300px">
		<div title="Detail" style="padding:5px">
				<table id="dg-attacc"  style="width:100%">
				</table>
				<div id="tb-attacc">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-attacc').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-attacc').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-attacc').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-attacc').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>
<script type="text/javascript">
$('#att_search_attid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchatt();
			}
		}
	})
});
$('#att_search_company').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchatt();
			}
		}
	})
});

$('#att_search_accountname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchatt();
			}
		}
	})
});
$('#dg-att').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-att',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
        height:500,
		autoRowHeight:true,
        onDblClickRow: function (index,row) {
			editatt(index);
		},
        rowStyler: function(index,row){
			if (row.count >= 1){
				return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-attacc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvattacc = $(this).datagrid('getRowDetail',index).find('table.ddv-attacc');
			ddvattacc.datagrid({
				url:'<?php echo $this->createUrl('//accounting/att/indexacc',array('grid'=>true)) ?>?attid='+row.attid,
				fitColumns:true,
				singleSelect:true,
                onDblClickRow: function (index,row) {
			         editatt(index);
		        },
				rownumbers:true,
                pagination:true,
				loadMsg:'<?php echo GetCatalog('pleasewait') ?>',
				height:'auto',
                showFooter:true,
				columns:[[
						{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>'},
                        {field:'accformula',title:'<?php echo GetCatalog('accformula') ?>'},
                        {field:'nourut',title:'<?php echo GetCatalog('nourut') ?>'},
                        {field:'isbold',title:'<?php echo GetCatalog('isbold') ?>',
                            formatter: function(value,row,index){
                                if (value == 1){
                                    return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
                                } else {
                                    return '';
                        }}},
                        {field:'isview',title:'<?php echo GetCatalog('isview') ?>',
                            formatter: function(value,row,index){
                                if (value == 1){
                                    return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
                                } else {
                                    return '';
                         }}},
                        {field:'istotal',title:'<?php echo GetCatalog('istotal') ?>',
                            formatter: function(value,row,index){
                                if (value == 1){
                                    return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
                                } else {
                                    return '';
                         }}},
				]],
				onResize:function(){
						$('#dg-att').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-att').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-att').datagrid('fixDetailRowHeight',index);
		},
        url: '<?php echo $this->createUrl('//accounting/att/index',array('grid'=>true)) ?>',
		destroyUrl: '<?php echo $this->createUrl('//accounting/att/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-att').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'attid',
		editing: false,
		columns:[[
		{field:'_expander',expander:true,width:24,fixed:true},
		{
            field:'attid',
            title:'<?php echo GetCatalog('attid') ?>',
            sortable: true,
			width:'50px',
            formatter: function(value,row,index){
				return value;
		    }},
            {
            field:'companyname',
            title:'<?php echo GetCatalog('company') ?>',
            sortable: true,
            width:'250px',
            formatter: function(value,row,index){
				return value;
            }},
            {
            field:'accountname',
            title:'<?php echo GetCatalog('reportplaccount') ?>',
            sortable: true,
			width:'300px',
            formatter: function(value,row,index){
			    return value;
			}}, 
            {
            field:'isneraca',
            title:'<?php echo GetCatalog('isneraca') ?>',
            align:'center',
            editor:{type:'checkbox',options:{on:'1',off:'0'}},
            width:'80',
            sortable: true,
            formatter: function(value,row,index){
                if (value == 1){
                    return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
                } else {
                    return '';
                }
            }},
            {
            field:'nourut',
            title:'<?php echo GetCatalog('nourut') ?>',
            sortable: true,
            width:'60',
            align:'center',
            formatter: function(value,row,index){
                                    return value;
            }},
            {
            field:'recordstatus',
            title:'<?php echo GetCatalog('recordstatus') ?>',
            align:'center',
            editor:{type:'checkbox',options:{on:'1',off:'0'}},
            width:'80',
            sortable: true,
            formatter: function(value,row,index){
                if (value == 1){
                    return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
                } else {
                    return '';
                }
            }}
		]]
});

function searchatt(value){
	var isneraca = 0;
	if ($("input[name='isneraca']").prop('checked'))
	{
		isneraca = 1;
	}
	else
	{
		isneraca = 0;
	}
	$('#dg-att').edatagrid('load',{
	accountname:$('#att_search_accountname').val(),
	companyid:$('#att_search_company').val(),
	isneraca:isneraca,
	});
}

function cancelatt() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('//accounting/att/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-att').edatagrid('reload');				
		} ,
		'cache':false});
};
function addatt() {
		$('#dlg-att').dialog('open');
		$('#ff-att-modif').form('clear');
		$('#ff-att-modif').form('load','<?php echo $this->createUrl('//accounting/att/getdata') ?>');
};

function editatt($i) {
	var row = $('#dg-att').datagrid('getSelected');
	if(row) {
		$('#dlg-att').dialog('open');
		$('#ff-att-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormatt(){
	$('#ff-att-modif').form('submit',{
		url:'<?php echo $this->createUrl('//accounting/att/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-att').datagrid('reload');
        $('#dlg-att').dialog('close');
			}
    }
	});	
};

function clearFormatt(){
		$('#ff-att-modif').form('clear');
};

function cancelFormatt(){
		$('#dlg-att').dialog('close');
};

$('#ff-att-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-att').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-attacc').datagrid({
			queryParams: {
				attid: $('#attid').val()
			}
		});
		if (data.isneraca == 1)
				{
						$('#isneraca').prop('checked', true);
				} else
				{
						$('#isneraca').prop('checked', false);
				}
        
        if (data.recordstatus == 1)
				{
						$('#recordstatus').prop('checked', true);
				} else
				{
						$('#recordstatus').prop('checked', false);
				}
}});
    
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

function downpdfneraca() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
    var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downpdfneraca') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
	//window.open('<?php echo $this->createUrl('//accounting/att/downpdfneraca') ?>?company='+companyid+'&date='+bsdate+'&per='+10);
}
function downpdfneraca1() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
    var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downpdflampiranneraca1') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downpdfpl() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
	var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
	var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downpdfpl') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downpdfpl1() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
	var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downpdfpl1') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downpdfneracayear() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
    var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downpdfneracayear') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downxlsneraca() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
    var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downxlsneraca') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downxlsneraca1() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
    var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downxlslampiranneraca1') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downxlspl() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
	var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downxlspl') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downxlspl1() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
	var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downxlspl1') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downxlsneracayear() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
    var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downxlsneracayear') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downpdfplyear() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
	var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downpdfplyear') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
function downxlsplyear() {
	var ss = [];
	var rows = $('#dg-att').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.attid);
	}
	var bsdate = $('#pldate').datebox('getValue');
	var companyid = $('#plcompany').combogrid('getValue');
    var plantid = $('#plplantid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('//accounting/att/downxlsplyear') ?>?company='+companyid+'&plant='+plantid+'&date='+bsdate+'&per='+10);
}
$('#dg-attacc').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'attdetid',
	editing: true,
	toolbar:'#tb-attacc',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('//accounting/att/indexacc',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('//accounting/att/saveacc',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('//accounting/att/saveacc',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('//accounting/att/purgeacc',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-attacc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-attacc').edatagrid('getSelected');
		if (row)
		{
			row.attid = $('#attid').val();
		}
	},
    /*
	onBeforeEdit: function(index,row){
			if (row.amount != undefined) {
				var value = row.amount;
				row.amount = value.replace(".", "");
				var value = row.currencyrate;
				row.currencyrate = value.replace(".", "");
			}
  	},
    */
	columns:[[
	{
		field:'attdetid',
		title:'<?php echo GetCatalog('attdetid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'attid',
		title:'<?php echo GetCatalog('attid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
    {
		field:'accountid',
		title:'<?php echo GetCatalog('accountcode') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'accountid',
						textField:'accountcode',
						url:'<?php echo $this->createUrl('account/accountcompany',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:false,
						onBeforeLoad: function(param) {
							 param.companyid = $('#companyid').combogrid('getValue');
						},
                        onSelect: function(index,row){
                              $("#accdetailid").val(row.accountid);
                        },
                        onHidePanel: function(){
                            var tr = $(this).closest('tr.datagrid-row');
				            var index = parseInt(tr.attr('datagrid-row-index'));
							var accformula = $("#dg-attacc").datagrid("getEditor", {index: index, field:"accformula"});
                            var accname = $("#dg-attacc").datagrid("getEditor", {index: index, field:"accountname"});
				            jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('accounting/account/getaccount') ?>',
								'data':{'accheaderid':$('#accdetailid').val()},
								'type':'post','dataType':'json',
								'success':function(data)
								{
									$(accformula.target).textbox('setValue',data.accountcode);
                                    $(accname.target).textbox('setValue',data.accountname);
								},
				            'cache':false});
					   },								
                        /*
                        onChange:function(newValue,oldValue) {
							if ((newValue !== ''))
							{
                            var tr = $(this).closest('tr.datagrid-row');
				            var index = parseInt(tr.attr('datagrid-row-index'));
							var accformula = $("#dg-attacc").datagrid("getEditor", {index: index, field:"accformula"});
							var accname = $("#dg-attacc").datagrid("getEditor", {index: index, field:"accountname"});
                            var accountid = $("#dg-attacc").datagrid("getEditor", {index: index, field:"accountid"});
                            jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('accounting/account/getaccount') ?>',
									'data':{'accheaderid':$(accountid.target).combogrid("getValue")},
									'type':'post','dataType':'json',
									'success':function(data)
									{
								        $(accformula.target).textbox('setValue',data.accountcode);
                                        $(accname.target).textbox('setValue',data.accountname);
                                        
									} ,
									'cache':false});
                            }
                        },
                        */
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('accdebitname')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('company')?>'},
						]]
				}	
			},
        width:'150',
		sortable: true,
		formatter: function(value,row,index){
							return row.accountcode;
		}
	},
    {
		field:'accountname',
		title:'<?php echo GetCatalog('accountname') ?>',
		hidden:false,
        required: true,
        editor:{
			type:'textbox',
        },
		sortable: true,
        width: 250,
		formatter: function(value,row,index){
							return value;
	}
	},
        /*
	{
		field:'accformula',
		title:'<?php echo GetCatalog('accformula') ?>',
        editor:{
			type:'combogrid',
			options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'accformulaid',
						textField:'accformulaname',
						url:'<?php echo $this->createUrl('account/accountformula',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						onBeforeLoad: function(param) {
							 param.companyid = $('#companyid').combogrid('getValue');
						},
                        onSelect:function(row){
                            console.log(row)
                                var opts = $(this).combogrid('options');
                                var el = opts.finder.getEl(this, row[opts.valueField]);
                                el.find('input.combogrid-checkbox')._propAttr('checked', true);
                        },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('accdebitname')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('company')?>'},
						]]
				}	
		},
		width:'150px',		
		sortable: true,		
		formatter: function(value,row,index){
							return value;
		}
	},
    */
    {
		field:'accformula',
		title:'<?php echo GetCatalog('forumulacode') ?>',
        editor:{
			type:'textbox',
			options: {
				required:false
			}
		},
		width:'150px',		
		sortable: true,		
		formatter: function(value,row,index){
							return value;
		}
	},
    {
		field:'nourut',
		title:'<?php echo GetCatalog('nourut') ?>',
        editor:{
			type:'textbox',
			options: {
				required:true
			}
		},
		width:'150px',		
		sortable: true,		
		formatter: function(value,row,index){
							return value;
		}
	},
    {
                field:'isbold',
                title:'<?php echo getCatalog('isbold') ?>',
                width:80,
                align:'center',
                editor:{type:'checkbox',options:{on:'1',off:'0'}},
                sortable: true,
                formatter: function(value,row,index){
						if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
							return '';
						}
	}},
    {
                field:'isview',
                title:'<?php echo getCatalog('isview') ?>',
                width:80,
                align:'center',
                editor:{type:'checkbox',options:{on:'1',off:'0'}},
                sortable: true,
                formatter: function(value,row,index){
						if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
							return '';
						}
    }},
    {
                field:'istotal',
                title:'<?php echo getCatalog('istotal') ?>',
                width:80,
                align:'center',
                editor:{type:'checkbox',options:{on:'1',off:'0'}},
                sortable: true,
                formatter: function(value,row,index){
						if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
							return '';
						}
    }},
	]]
});
</script>
