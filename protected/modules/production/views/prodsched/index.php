<table id="dg-prodsched" style="width:100%;height:97%"></table>
<div id="tb-prodsched">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-prodsched').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-prodsched').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-prodsched').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-prodsched').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfprodsched()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsprodsched()"></a>
<?php }?>
     <form enctype="multipart/form-data" method="post" id="ff">
<table>
<tr>
<td><?php echo getCatalog('product')?></td>
<td><input class="easyui-textbox" id="prodsched_search_product" style="width:150px"></td>
<td><?php echo getCatalog('company')?></td>
<td><input class="easyui-textbox" id="prodsched_search_company" style="width:150px"></td>
<td><?php echo getCatalog('sloc')?></td>
<td><input class="easyui-textbox" id="prodsched_search_sloc" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchprodsched()"></a></td>
</tr>
     <tr>
<td>Upload File</td>
<td>
    <input class="easyui-filebox" name="upload" id="chooseFile" data-options="prompt:'Choose a file...'" />
</td>
<td>
    <a id="btn_add" href="#" class="easyui-linkbutton" iconCls="icon-upload" plain="true">Upload!</a>
</td>
<td>
    <div id="progressFile" class="easyui-progressbar" style="width:100%;"></div>
</td>
</tr>
</table>	
         </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
    $('#ff').form({
        url:'production/prodsched/uploaddata',
        ajax:'true',
        dataType: 'json',
        data : $("#ff").serialize(),
        iframe:false, // pour activer le onProgress
        onProgress: function(percent){
            // pendant l'envoi du fichier
            $('#progressFile').progressbar('setValue', percent);    
        },
        success: function(data){
            data = JSON.parse(data); 
            if (data.status == "success"){
                jQuery.ajax({'url':'production/prodsched/running',
                  'data':{'id':data.filename},
		          'type':'post',
                  'dataType':'json',
		          'success':function(result) {
                      console.log(result);
                     if (result.status == "success"){
				        $('#dg-prodsched').datagrid('reload');
			         }else{
				        alert('error');
			         }
		          },
		          'cache':false});
            }else{
                alert('error');
            }
                
        },
        onLoadError: function(){
                // apres l'envoi du fichier en cas d'erreur
        }
    });    
     $('#btn_add').bind('click', function(){
        // vérification si le champ du nom de fichier est vide ou non
        if($('#chooseFile').filebox('getValue')!="") { // transfer le fichier en assynchrone
            $('#ff').submit(); // on envoie le fichier
        }
    });
});

$('#dg-prodsched').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-prodsched',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
    autoSave:false,
    url: '<?php echo $this->createUrl('prodsched/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('prodsched/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('prodsched/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('prodsched/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-prodsched').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'prodschedid',
		editing: '<?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>',
		columns:[[
		{
field:'prodschedid',
title:'<?php echo getCatalog('id') ?>',
width:30,
sortable: true,
formatter: function(value,row,index){
					return value;
					}},

{
field:'companyid',
title:'<?php echo getCatalog('company') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'companyid',
						textField:'companyname',
						url:'<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'companyid',title:'<?php echo getCatalog('companyid')?>',width:50},
							{field:'companyname',title:'<?php echo getCatalog('companyname')?>',width:200},
						]]
				}	
			},
			width:'180px',
sortable: true,
formatter: function(value,row,index){
						return row.companyname;
					}},

{
field:'bulan',
title:'<?php echo getCatalog('bulan') ?>',
editor:'numberbox',
width:'50px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
            {
field:'tahun',
title:'<?php echo getCatalog('tahun') ?>',
editor:'numberbox',
width:'80px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
            {
field:'productid',
title:'<?php echo getCatalog('product') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo getCatalog('productid')?>',width:50},
							{field:'productname',title:'<?php echo getCatalog('productname')?>',width:200},
						]]
				}	
			},
			width:'450px',
sortable: true,
formatter: function(value,row,index){
						return row.productname;
					}},

					{
field:'unitofmeasureid',
title:'<?php echo getCatalog('unitofmeasure') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'unitofmeasureid',title:'<?php echo getCatalog('unitofmeasureid')?>',width:50},
							{field:'uomcode',title:'<?php echo getCatalog('uomcode')?>',width:200},
						]]
				}	
			},
			width:'80px',
sortable: true,
formatter: function(value,row,index){
						return row.uomcode;
					}},
            {
field:'slocid',
title:'<?php echo getCatalog('sloc') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'<?php echo Yii::app()->createUrl('common/sloc/indextrx',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'slocid',title:'<?php echo getCatalog('slocid')?>',width:50},
							{field:'sloccode',title:'<?php echo getCatalog('sloccode')?>',width:200},
						]]
				}	
			},
sortable: true,
formatter: function(value,row,index){
						return row.sloccode;
					}},
               
            {
field:'d1',
title:'<?php echo getCatalog('d1') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
            {
field:'d2',
title:'<?php echo getCatalog('d2') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d3',
title:'<?php echo getCatalog('d3') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d4',
title:'<?php echo getCatalog('d4') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d5',
title:'<?php echo getCatalog('d5') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d6',
title:'<?php echo getCatalog('d6') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d7',
title:'<?php echo getCatalog('d7') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d8',
title:'<?php echo getCatalog('d8') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d9',
title:'<?php echo getCatalog('d9') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d10',
title:'<?php echo getCatalog('d10') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
            {
field:'d11',
title:'<?php echo getCatalog('d11') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
            {
field:'d12',
title:'<?php echo getCatalog('d12') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d13',
title:'<?php echo getCatalog('d13') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d14',
title:'<?php echo getCatalog('d14') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d15',
title:'<?php echo getCatalog('d15') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d16',
title:'<?php echo getCatalog('d16') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d17',
title:'<?php echo getCatalog('d17') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d18',
title:'<?php echo getCatalog('d18') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d19',
title:'<?php echo getCatalog('d19') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d20',
title:'<?php echo getCatalog('d20') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d21',
title:'<?php echo getCatalog('d21') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
            {
field:'d22',
title:'<?php echo getCatalog('d22') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d23',
title:'<?php echo getCatalog('d23') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d24',
title:'<?php echo getCatalog('d24') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d25',
title:'<?php echo getCatalog('d25') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d26',
title:'<?php echo getCatalog('d26') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d27',
title:'<?php echo getCatalog('d27') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d28',
title:'<?php echo getCatalog('d28') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d29',
title:'<?php echo getCatalog('d29') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
             {
field:'d30',
title:'<?php echo getCatalog('d30') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
            
            {
field:'d31',
title:'<?php echo getCatalog('d31') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},            {
field:'total',
title:'<?php echo getCatalog('total') ?>',
editor:'numberbox',
width:'35px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}}, 
          
		]]
});
    
function searchprodsched(value){
	$('#dg-prodsched').edatagrid('load',{
			prodschedid:value,
			product:value,
			sloc:value,
			company:value,
			unitofmeasure:value,
	});
}
function downpdfprodsched() {
	var ss = [];
	var rows = $('#dg-prodsched').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.prodschedid);
	}
	window.open('<?php echo $this->createUrl('prodsched/downpdf') ?>?id='+ss);
}
function downxlsprodsched() {
	var ss = [];
	var rows = $('#dg-prodsched').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.prodschedid);
	}
	window.open('<?php echo $this->createUrl('prodsched/downxls') 

?>?id='+ss);
}
</script>
