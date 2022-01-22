<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'broadcastwaid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('order/broadcastwa/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('order/broadcastwa/getData'),
  'ispost'=>1,
  'isreject'=>1,
  'approveurl'=>Yii::app()->createUrl('order/broadcastwa/approve'),
  'rejecturl'=>Yii::app()->createUrl('order/broadcastwa/delete'),
	'saveurl'=>Yii::app()->createUrl('order/broadcastwa/save'),
	'destroyurl'=>Yii::app()->createUrl('order/broadcastwa/purge'),
	'uploadurl'=>Yii::app()->createUrl('order/broadcastwa/upload'),
	'downpdf'=>Yii::app()->createUrl('order/broadcastwa/downpdf'),
	'downxls'=>Yii::app()->createUrl('order/broadcastwa/downxls'),
	'columns'=>"
		{
			field:'broadcastwaid',
			title:'".getCatalog('broadcastwaid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'".getCatalog('company') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
    {
			field:'wanumber',
			title:'".getCatalog('wanumber') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'senddate',
			title:'".getCatalog('senddate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
    {
			field:'sendtime',
			title:'".getCatalog('sendtime') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
    {
			field:'broadcasttype',
			title:'".getCatalog('broadcasttype') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.type;
		}},
		{
			field:'message',
			title:'".getCatalog('message') ."',
			sortable: true,
			width:'450px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'file',
			title:'".getCatalog('file') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
    {
			field:'filename',
			title:'".getCatalog('filename') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:'".getCatalog('description') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			align:'left',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.statusname;
		}},",
	'searchfield'=> array ('broadcastwaid','companyname','senddate','sendtime','description','status'),
	'headerform'=> "
		<input type='hidden' id='broadcastwaid' name='broadcastwaid'></input>
		<table cellpadding='5'>
    <tr>
      <td>".getCatalog('company')."</td>
      <td><select class='easyui-combogrid' id='companyid' name='companyid' style='width:250px' data-options=\"
              panelWidth: '500px',
              required: true,
              idField: 'companyid',
              textField: 'companyname',
              pagination:true,
              mode:'remote',
              method: 'get',
              url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true))."',
              columns: [[
                  {field:'companyid',title:'".GetCatalog('companyid')."'},
                  {field:'companyname',title:'". GetCatalog('companyname') ."'},
              ]],
              fitColumns: true
          \">
      </select></td>
    </tr>
    <tr>
      <td>".getCatalog('wanumber')."</td>
      <td><input class='easyui-textbox' type='text' id='wanumber' name='wanumber' data-options='required:true' style='width:250px'></input></td>
    </tr>
		<tr>
				<td>".getCatalog('senddate')."</td>
				<td><input class=\"easyui-datebox\" type=\"text\" id=\"senddate\" name=\"senddate\" data-options=\"formatter:dateformatter,required:true,parser:dateparser\" ></input></td>
    </tr>
    <tr>
				<td>".getCatalog('senddate')."</td>
				<td><input class=\"easyui-timespinner\" type=\"text\" id=\"sendtime\" name=\"sendtime\"  data-options=\"
        formatter:function(date){
          if (!date) return '';
          var opts = $(this).timespinner('options');
          var tt = [formatN(date.getHours()), formatN(date.getMinutes())];
          if (opts.showSeconds){
            tt.push(formatN(date.getSeconds()));
          }
          return tt.join(opts.separator);
          
          function formatN(value){
            return (value < 10 ? '0' : '') + value;
          }
        },
        parser:function(s){
          var opts = $(this).timespinner('options');
          if (!s) return null;
          var tt = s.split(opts.separator);
          return new Date(1900, 0, 0, 
            parseInt(tt[0],10)||0, parseInt(tt[1],10)||0, parseInt(tt[2],10)||0);
        }\"></input></td>
    </tr>
    <tr>
      <td>".getCatalog('broadcasttype')."</td>
      <td>
        <select class='easyui-combobox' name='broadcasttype' id='broadcasttype' data-options=\"required:'true', panelHeight:'auto'\" style='width:120px'>
          <option value='1'>Message</option>
          <option value='2'>Image</option>
          <option value='3'>Document/File</option>
          <option value='4'>Video</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>".GetCatalog('message')."</td>
      <td><input class='easyui-textbox' id='message' name='message' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
    </tr>
    <tr>
      <td>".getCatalog('file')."</td>
      <td><input class='easyui-filebox' type='text' id='file' name='file' style='text-indent:50px' data-options=\" 
        prompt:'Choose a file...',
        labelPosition:'after',
        accept:'image/*',
        width:'250px',
        onChange: function(){
          //e.preventDefault(); 
          var files = $(this).filebox('files')
          var formData = new FormData();
          for(var i=0; i<files.length; i++){
              var file = files[i];
              formData.append('file-broadcastwa',file,file.name);
          }
          $.ajax({
              url:'".Yii::app()->createUrl('order/broadcastwa/uploadfile')."',
              type:'post',
              data:formData,
              contentType:false,
              processData:false,
              success:function(data){
                  show('Message',data.pesan);
                  $('#filename').textbox('setValue',data.filename);
                  //console.log(data);
              }
          })
          return false;
        },
        formatter: function(value,row,index){
          if (row.file != ''){
            return '<a href=".Yii::app()->request->baseUrl."/images/broadcastwa/' + value + ' target=_blank><img src=".Yii::app()->request->baseUrl."/images/broadcastwa/' + value + ' width=100 height=60 ></img></a>';
          } else {
            return '';
          }
        }
      \"></input></td>
    </tr>
    <tr>
      <td>File Name</td>
      <td><input class='easyui-textbox' type='text' name='filename' id='filename' style='width:250px'></input>
    </tr>
    <tr>
      <td>".getCatalog('description')."</td>
      <td><input class='easyui-textbox' type='textarea' name='description' data-options='required:true' ></input></td>
    </tr>
    <tr>
      <td>".getCatalog('recordstatus')."</td>
      <td><input id='recordstatus' name='recordstatus' type='checkbox'></input></td>
    </tr>
		</table>
	",
	'loadsuccess' => "
		if (data.recordstatus == 1) {
			$('#recordstatus').prop('checked', true);
		} else {
			$('#recordstatus').prop('checked', false);
		}
	",
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='Copy'class='easyui-linkbutton' iconCls='icon-broadcastwa' plain='true' onclick='copybroadcastwa()'></a>
	",
	'addonscripts'=>"
		function copybroadcastwa() {
			var ss = [];
			var rows = $('#dg-broadcastwa').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.broadcastwaid);
			}
			jQuery.ajax({'url':'".$this->createUrl('broadcastwa/copybroadcastwa') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-broadcastwa').edatagrid('reload');				
				} ,
				'cache':false});
		};
        
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'broadcastwadetid',
			'urlsub'=>Yii::app()->createUrl('order/broadcastwa/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('order/broadcastwa/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('order/broadcastwa/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('order/broadcastwa/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('order/broadcastwa/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'broadcastwadetid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'customername',title:'".getCatalog('customername') ."',width:'250px'},
				{field:'ownername',title:'".getCatalog('ownername') ."',width:'250px'},
				{field:'destnumber',title:'".getCatalog('destnumber') ."',align:'right',width:'150px'},
				{field:'status',title:'".getCatalog('status') ."',width:'150px'},
			",
			'onsuccess'=>"
				$('#dg-broadcastwa-detail').edatagrid('reload');
			",
			'onerror'=>"
				$('#dg-broadcastwa-detail').edatagrid('reload');
			",
			'onbeforesave'=>"
				var row = $('#dg-broadcastwa-detail').edatagrid('getSelected');
				if (row)
				{
					row.broadcastwaid = $('#broadcastwaid').val();
				}
			",
			'columns'=>"
				{
					field:'broadcastwaid',
					title:'".getCatalog('broadcastwaid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'broadcastwadetid',
					title:'".getCatalog('broadcastwadetid') ."',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
        {
          field:'addressbookid',
          title:'".GetCatalog('addressbook')."',
          width:'200px',
          editor:{
            type:'combogrid',
            options:{
              panelWidth:'500px',
              mode : 'remote',
              method:'get',
              idField:'addressbookid',
              textField:'fullname',
              url:'".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true))."',
              fitColumns:true,
              required:true,
              pagination:true,
              queryParams:{
                combo:true
              },
              loadMsg: '".GetCatalog('pleasewait')."',
              columns:[[
                {field:'addressbookid',title:'".GetCatalog('addressbookid')."',width:'50px'},
                {field:'fullname',title:'".GetCatalog('fullname')."',width:'250px'},
              ]]
            }	
          },
          sortable: true,
          formatter: function(value,row,index){
            return row.fullname;
          }
        },
				{
					field:'customername',
					title:'".getCatalog('customername') ."',
					editor:'text',
					sortable: true,
					required:true,
					formatter: function(value,row,index){
						return value;
					}
				},
        {
					field:'ownername',
					title:'".getCatalog('ownername') ."',
					editor:'text',
					sortable: true,
					required:true,
					formatter: function(value,row,index){
						return value;
					}
				},
        {
					field:'destnumber',
					title:'".getCatalog('destnumber') ."',
					editor:'text',
					sortable: true,
					required:true,
					formatter: function(value,row,index){
						return value;
					}
				},
			"
		),
	),	
));