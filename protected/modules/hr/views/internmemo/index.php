<style type="text/css">
.ajax-loader {
  visibility: hidden;
  background-color: rgba(255,255,255,0.7);
  position: absolute;
  z-index: +1000 !important;
  width: 100%;
  height:100%;
}

.ajax-loader img {
  position: relative;
  top:10%;
  left:10%;
}
</style>
<div class="ajax-loader">
  <img src="<?php echo Yii::app()->baseUrl?>/images/loading.gif" class="img-responsive" />
</div>
<table id="dg-internmemo"  style="width:1300px;height:97%"></table>
<input type="hidden" name="bomid" id="bomid" />
<div id="tb-internmemo">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addInternmemo()" id="addinternmemo"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editInternmemo()" id="editinternmemo"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveInternmemo()" id="approveinternmemo"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelInternmemo()" id="rejectinternmemo"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfinternmemo()"></a>
		<a href="javascript:void(0)" title="Export Ke XLS"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsinternmemo()"></a>
	<?php }?>
  <table>
    <tr>
      <td><?php echo GetCatalog('internmemoid')?></td>
      <td><input class="easyui-textbox" id="internmemo_search_productoutputid" style="width:50px"></td>
      <td><?php echo GetCatalog('company')?></td>
      <td><input class="easyui-textbox" id="internmemo_search_company" style="width:150px"></td>
      <td><?php echo GetCatalog('docno')?></td>
      <td><input class="easyui-textbox" id="internmemo_search_docno" style="width:150px"></td>
      <td><?php echo GetCatalog('subject')?></td>
      <td><input class="easyui-textbox" id="internmemo_search_subject" style="width:150px"></td>
    
  </table>
</div>

<div id="dlg-internmemo" class="easyui-dialog" title="<?php echo GetCatalog('internmemo') ?>" style="width:80%;height:600px" 
closed="true" data-options="
  resizable:true,
  modal:true,
  toolbar: [
  {
    text:'<?php echo GetCatalog('save')?>',
    iconCls:'icon-save',
    handler:function(){
      submitFormInternmemo();
    }
  },
  {
    text:'<?php echo GetCatalog('cancel')?>',
    iconCls:'icon-cancel',
    handler:function(){
      $('.ajax-loader').css('visibility', 'hidden');
      cancelFormInternmemo();
    }
  }]">
	<form id="ff-internmemo-modif" method="post" data-options="novalidate:true">
		<input type="hidden" name="internmemoid" id="internmemoid" />
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"/></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
					panelWidth: 500,
					required: true,
					idField: 'companyid',
					textField: 'companyname',
					pagination:true,
					mode:'remote',
					url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
					method: 'get',
					required:true,
					columns: [[
            {field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
            {field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
					]],
					fitColumns: true
					">
					</select></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('subject')?></td>
				<td><input class="easyui-textbox" name="subject" data-options="required:true"></input></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('revisionno')?></td>
				<td><input class="easyui-textbox" name="revisionno" ></input></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('from')?></td>
				<td><input class="easyui-textbox" name="from" data-options="required:true"></input></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('to')?></td>
				<td><input class="easyui-textbox" name="to" data-options="required:true"></input></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('cc')?></td>
				<td><input class="easyui-textbox" name="cc" placeholder="Optional"></input></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('description')?></td>
				<td>
          <div id="toolbar-container"></div>
           <textarea name="content" id="content" class="ck5-editor" ></textarea>
        </td>
			</tr>
      
		</table>
	</form>
	<div class="easyui-tabs" style="width:auto;height:400px">
		<div title="Approval By" style="padding:5px">
			<table id="dg-internmemodetail"  style="width:1000px;height:300px">
			</table>
			<div id="tb-internmemodetail">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-internmemodetail').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-internmemodetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-internmemodetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-internmemodetail').edatagrid('destroyRow')"></a>
			</div>
		</div> 
	</div>
</div>

<script type="text/javascript">

/* $('#addinternmemo').click(function(){
    tampil_loading();
});
    
$('#editinternmemo').click(function(){
    tampil_loading();
}); */
/*
  $(".add-text").on('click',function(){
    console.log('add');
     $("table.appendrow").append(`<tr>
        <td><input class="textapproveby" name="textapproveby[]" placeholder="Optional"></input></td>
        <td><select id="dg-emp" name="dg-emp" style="width:250px"></select> <a href="#" class="btn_set" onclick="setqty()" >Set Qty</a>
          <input class="appbyperson" name="appbyperson[]" placeholder="Optional"></input>
        </td>
      </tr>
      `);

    
    $('.textapproveby').textbox({
      width: 300,
      label: 'Text:',
      iconCls:'icon-edit',
      prompt:'Mengetahui / Menyetujui, dll',
      iconAlign:'left'
    });

    $('.btn_set').linkbutton({
      iconCls: 'icon-ok',
      plain:'true'
    });

    $('.appbyperson').textbox({
      width: 300,
      readonly:true,
    });
   
   $('#dg-emp').combogrid({
    panelWidth: '500px',
    idField: 'employeeid',
    textField: 'fullname',
    pagination:true,
    mode:'remote',
    method: 'get',
   	url:'<?php //echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ?>',
    columns: [[
        {field:'employeeid',title:'<?php echo GetCatalog('employeeid') ?>'},
        {field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
        {field:'oldnik',title:'<?php echo GetCatalog('oldnik') ?>'},
        {field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
    ]],
  });
    //$('#dg-appbyperson').datagrid('reload');   
  });  
  */
  $('#approveinternmemo').click(function(){
      tampil_loading();
  });

  $('#rejectinternmemo').click(function(){
      tampil_loading();
  });

  function tampil_loading(){
      $('.ajax-loader').css('visibility', 'visible');
  }

  function tutup_loading(){
      $('.ajax-loader').css("visibility", "hidden");
  }
      
  $('#internmemo_search_productoutputid').textbox({
    inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
      keyup:function(e){
        if (e.keyCode == 13) searchinternmemo();
      }
    })
  });
  $('#internmemo_search_company').textbox({
    inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
      keyup:function(e){
        if (e.keyCode == 13) searchinternmemo();
      }
    })
  });
  $('#internmemo_search_docno').textbox({
    inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
      keyup:function(e){
        if (e.keyCode == 13) searchinternmemo();
      }
    })
  });
  $('#internmemo_search_docdate').textbox({
    inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
      keyup:function(e){
        if (e.keyCode == 13) searchinternmemo();
      }
    })
  });
  
	$('#dg-internmemo').edatagrid({
		singleSelect: false,
		toolbar:'#tb-internmemo',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		view: detailview,
		detailFormatter:function(index,row){
			return '<div style="padding:2px"><table class="ddv-internmemodet"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvinternmemodet = $(this).datagrid('getRowDetail',index).find('table.ddv-internmemodet');
			//var ddvinternmemosign = $(this).datagrid('getRowDetail',index).find('table.ddv-internmemosign');
      //const arr = Array.from(row.textapproveby);
			ddvinternmemodet.datagrid({
				url:'<?php echo $this->createUrl('internmemo/indexcontent',array('grid'=>true)) ?>?id='+row.internmemoid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				title:'Isi Internal Memo',
				height:'auto',
				width:'100%',
				pagination:true,
        showFooter:true,
				columns:[[
          {field:'_expander',expander:true,width:24,fixed:true},
				  {field:'internmemoid',title:'<?php echo GetCatalog('internmemoid') ?>'},
				  {field:'content',title:'<?php echo GetCatalog('content') ?>'},
				]],
				onResize:function(){
					$('#dg-internmemo').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
					setTimeout(function(){
						$('#dg-internmemo').datagrid('fixDetailRowHeight',index);
					},0);
				}
			});
			$('#dg-internmemo').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('internmemo/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-internmemo').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'internmemoid',
		editing: false,
		columns:[[
		{field:'_expander',expander:true,width:24,fixed:true},
		{
			field:'internmemoid',
			title:'<?php echo GetCatalog('internmemoid') ?>',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
    }},
    {
      field:'companyid',
      title:'<?php echo GetCatalog('company') ?>',
      sortable: true,
      width:'250px',
      formatter: function(value,row,index) {
        return row.companyname;
      }
    },
    {
      field:'docno',
        title:'<?php echo GetCatalog('docno') ?>',
        sortable: true,
        width:'150px',
        formatter: function(value,row,index){
          return value; 
    }},
    {
      field:'docdate',
      title:'<?php echo GetCatalog('docdate') ?>',
      sortable: true,
      width:'80px',
      formatter: function(value,row,index){
        return value;
    }},
    {
      field:'subject',
      title:'<?php echo GetCatalog('subject') ?>',
      sortable: true,
      width:'250px',
      formatter: function(value,row,index){
        return value
    }},
    {
      field:'revisionno',
      title:'<?php echo GetCatalog('revisionno') ?>',
      sortable: true,
      width:'150px',
      formatter: function(value,row,index){
        return value
    }},
    {
      field:'content',
      title:'<?php echo GetCatalog('content') ?>',
      sortable: true,
      width:'100px',
      hidden:true,
      formatter: function(value,row,index){
        return value;
    }},
    {
      field:'from',
      title:'<?php echo GetCatalog('from') ?>',
      sortable: true,
      width:'200px',
      formatter: function(value,row,index){
        return value;
    }},
    {
      field:'to',
      title:'<?php echo GetCatalog('to') ?>',
      sortable: true,
      width:'200px',
      formatter: function(value,row,index){
        return value;
    }},
    {
      field:'cc',
      title:'<?php echo GetCatalog('cc') ?>',
      sortable: true,
      width:'200px',
      formatter: function(value,row,index){
        return value;
    }},
    {
      field:'recordstatusinternmemo',
      title:'<?php echo GetCatalog('recordstatus') ?>',
      sortable: true,
      width:'150px',
      formatter: function(value,row,index){
        return value;
    }},
  ]]
	});
	
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
	
	function searchinternmemo(){
		$('#dg-internmemo').edatagrid('load',{
			internmemoid:$('#internmemo_search_productoutputid').val(),
			company:$('#internmemo_search_company').val(),
			docno:$('#internmemo_search_docno').val(),
			sono:$('#internmemo_search_sono').val(),
			customer:$('#internmemo_search_customer').val(),
			foreman:$('#internmemo_search_foreman').val(),
			docdate:$('#internmemo_search_docdate').val(),
			description:$('#internmemo_search_description').val(),
			productdetail:$('#internmemo_search_productdetail').val(),
			productfg:$('#internmemo_search_productfg').val(),
		});
	};
	
	function approveInternmemo() {
		var ss = [];
		var rows = $('#dg-internmemo').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.internmemoid);
		}
		jQuery.ajax({'url':'<?php echo $this->createUrl('internmemo/approve') ?>',
			'data':{'id':ss},'type':'post','dataType':'json',
			'success':function(data)
			{
				tutup_loading();
                show('Message',data.message);
				$('#dg-internmemo').edatagrid('reload');				
			} ,
		'cache':false});
	};
	
	function cancelInternmemo() {
		var ss = [];
		var rows = $('#dg-internmemo').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.internmemoid);
		}
		jQuery.ajax({'url':'<?php echo $this->createUrl('internmemo/delete') ?>',
			'data':{'id':ss},'type':'post','dataType':'json',
			'success':function(data)
			{
                tutup_loading();
				show('Message',data.message);
				$('#dg-internmemo').edatagrid('reload');				
			} ,
		'cache':false});
	};
	
	function downpdfinternmemo() {
		var ss = [];
		var rows = $('#dg-internmemo').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.internmemoid);
		}
		window.open('<?php echo $this->createUrl('internmemo/downpdf') ?>?id='+ss);
	};
	function downxlsinternmemo() {
		var ss = [];
		var rows = $('#dg-internmemo').edatagrid('getSelections');
		for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.internmemoid);
		}
		window.open('<?php echo $this->createUrl('internmemo/downxls') ?>?id='+ss);
	};
	
	function addInternmemo() {
		$('#dlg-internmemo').dialog('open');
		$('#ff-internmemo-modif').form('clear');
		$('#ff-internmemo-modif').form('load','<?php echo $this->createUrl('internmemo/GetData') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
    let myEditor;
    ClassicEditor
        .create(document.querySelector('.ck5-editor'))
        .then(editor => {
            window.editor = editor;
            myEditor = editor;
        })
        .catch(err => {
            console.error(err.stack);
        });
	};
	
	function editInternmemo() {
		var row = $('#dg-internmemo').datagrid('getSelected');
		var docmax = <?php echo CheckDoc('appprodplan') ?>;
	  var docstatus = row.recordstatus;
		if(row) {
			if (docstatus == docmax)  show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
      else
      {
        $('#dlg-internmemo').dialog('open');
        //console.log(row);
        let myEditor;

        $('#ff-internmemo-modif').form('load',row);
         ClassicEditor
        .create(document.querySelector('.ck5-editor'))
        .then(editor => {
            window.editor = editor;
            myEditor = editor;
        })
        .catch(err => {
            console.error(err.stack);
        });

        //myEditor.setData(row.content);
      }
		}
		else {
			show('Message','chooseone');
		}
	};
	
	function submitFormInternmemo(){
		$('#ff-internmemo-modif').form('submit',{
			url:'<?php echo $this->createUrl('internmemo/save') ?>',
			onSubmit:function(){
                tutup_loading();
				return $(this).form('enableValidation').form('validate');
			},
			success:function(data){
				var data = eval('(' + data + ')');  // change the JSON string to javascript object
				show('Pesan',data.message)
				if (data.success == true){
					$('#dg-internmemo').datagrid('reload');
					$('#dlg-internmemo').dialog('close');
          document.querySelector('.ck-editor__editable').ckeditorInstance.destroy()
				}
			}
		});	
	};
	
	function clearFormInternmemo(){
		$('#ff-internmemo-modif').form('clear');
	};
	
	function cancelFormInternmemo(){
    document.querySelector('.ck-editor__editable').ckeditorInstance.destroy()
		$('#dlg-internmemo').dialog('close');
        //tutup_loading();
      
	}
	
	$('#ff-internmemo-modif').form({
		onLoadSuccess: function(data) {
			//console.log('OK!');
      $('#dg-internmemodetail').datagrid({
				queryParams: {
					id: $('#internmemoid').val()
				}
		});
		}
	});

  $('#dg-internmemodetail').edatagrid({
    iconCls: 'icon-edit',	
    singleSelect: true,
    idField:'internmemoid',
    editing: true,
    toolbar:'#tb-internmemodetail',
    fitColumn: true,
    pagination:true,
    showFooter:true,
    url: '<?php echo $this->createUrl('internmemo/searchttd',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('internmemo/savettd',array('grid'=>true))?>',
    updateUrl: '<?php echo $this->createUrl('internmemo/savettd',array('grid'=>true))?>',
    destroyUrl: '<?php echo $this->createUrl('internmemo/purgettd',array('grid'=>true))?>',
    onSuccess: function(index,row){
      show('Pesan',row.msg);
      $('#dg-internmemodetail').edatagrid('reload');
    },
    onError: function(index,row){
      show('Pesan',row.msg);
    },	
    onBeforeEdit:function(index,row) {
      row.internmemoid = $('#internmemoid').val();
    },
    columns:[[
    {
      field:'internmemoid',
      title:'<?php echo GetCatalog('internmemoid') ?>',
      hidden:true,
      sortable: true,
      formatter: function(value,row,index){
        return value;
      }
    },
    {
      field:'textapproveby',
      title:'<?php echo GetCatalog('textapproveby') ?>',
      editor:'text',
      width:'250px',
      multiline:true,
      sortable: true,
      formatter: function(value,row,index){
        return value;
      }
    },
    {
      field:'appbyperson',
      title:'<?php echo GetCatalog('appbyperson') ?>',
      editor:{
        type:'combogrid',
        options:{
          panelWidth:'500px',
          mode : 'remote',
          method:'get',
          idField:'useraccessid',
          textField:'realname',
          url:'<?php echo Yii::app()->createUrl('admin/useraccess/index',array('grid'=>true)) ?>',
          fitColumns:true,
          pagination:true,
          required:true,
          queryParams:{
            combo:true
          },
          loadMsg: '<?php echo GetCatalog('pleasewait')?>',
          columns:[[
            {field:'realname',title:'<?php echo GetCatalog('realname')?>',width:'250px'},
          ]]
        }	
        },
      width:'150px',
      sortable: true,
      formatter: function(value,row,index){
        return row.realname;
      }
	  },
     {
      field:'number',
      title:'<?php echo GetCatalog('number') ?>',
      editor:'text',
      width:'250px',
      hidden:true,
      sortable: true,
      formatter: function(value,row,index){
        return row.number;
      }
    },
  ]]
  });
</script>
<script>
/*     ClassicEditor
        .create( document.querySelector( 'textarea#content' ) )
        .catch( error => {
            console.error( error );
        } );  */
</script>
