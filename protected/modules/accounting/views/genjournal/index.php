<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'genjournalid',
	'formtype'=>'masterdetail',
	'isxls'=>0,
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appjournal',
	'url'=>Yii::app()->createUrl('accounting/genjournal/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/genjournal/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/genjournal/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/genjournal/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/genjournal/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/genjournal/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/genjournal/delete'),
	'downpdf'=>Yii::app()->createUrl('accounting/genjournal/downpdf'),
	'columns'=>"
		{
			field:'genjournalid',
			title:'".GetCatalog('genjournalid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				if (row.recordstatus == 1) {
					return '<div style=\"background-color:green;color:white\">'+value+'</div>';
				} else 
				if (row.recordstatus == 2) {
					return '<div style=\"background-color:yellow;color:black\">'+value+'</div>';
				} else 
				if (row.recordstatus == 3) {
					return '<div style=\"background-color:red;color:white\">'+value+'</div>';
				} else 
					if (row.recordstatus == 0) {
					return '<div style=\"background-color:black;color:white\">'+value+'</div>';
				}
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'journalno',
			title:'".GetCatalog('journalno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'referenceno',
			title:'".GetCatalog('referenceno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
						return value;
					}},
		{
			field:'journaldate',
			title:'".GetCatalog('journaldate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'journalnote',
			title:'".GetCatalog('journalnote') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusname',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'rowstyler'=>"
		if (row.debit != row.credit){
			return 'background-color:blue;color:#fff;';
		}
	",
	'searchfield'=> array ('genjournalid','companyname','journalno','referenceno','journaldate','journalnote'),
	'headerform'=> "
		<input type='hidden' name='genjournalid' id='genjournalid'></input>
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('journaldate')."</td>
				<td><input class='easyui-datebox' type='text' id='journaldate' name='journaldate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('company')."</td>
				<td>
					<select class='easyui-combogrid' id='companyid' name='companyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'companyid',
						textField: 'companyname',
						pagination:true,
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'companyid',title:'".GetCatalog('companyid') ."',width:'50px'},
								{field:'companyname',title:'".GetCatalog('companyname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('journalnote')."</td>
				<td><input class='easyui-textbox' id='journalnote' name='journalnote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('referenceno')."</td>
				<td><input class='easyui-textbox' id='referenceno' name='referenceno'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'journaldetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/genjournal/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/genjournal/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/genjournal/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/genjournal/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/genjournal/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'plantcode',title:'".GetCatalog('plantcode') ."',width:'100px'},
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.debit;
					},align:'right',width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.credit;
					},align:'right',width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
			'columns'=>"
				{
					field:'genjournalid',
					title:'".GetCatalog('genjournalid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'journaldetailid',
					title:'".GetCatalog('journaldetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'accountid',
					title:'".GetCatalog('accountname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'accountid',
							textField:'accountname',
							url:'".$this->createUrl('account/index',array('grid'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							queryParams:{
								trxcom:true
							},
							onBeforeLoad: function(param) {
								 param.companyid = $('#companyid').combogrid('getValue');
							},
							onHidePanel: function() {
									var tr = $(this).closest('tr.datagrid-row');
									var index = parseInt(tr.attr('datagrid-row-index'));
									var plantid = $('#dg-genjournal-detail').datagrid('getEditor', {index: index, field:'plantid'});
									
									jQuery.ajax({
											'url':'".Yii::app()->createUrl('common/plant/indexcompany',array('grid'=>true))."',
											'data' :{
													'companyid' : $('#companyid').combogrid('getValue'),
													'trxcom' : true,
											},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
													console.log('success');
													$(plantid.target).combogrid('setValue',data.rows[0]['plantid']);
											},
											'cache':false
									});
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'accountid',title:'".GetCatalog('accountid')."',width:'80px'},
								{field:'companyname',title:'".GetCatalog('company')."',width:'350px'},
								{field:'accountcode',title:'".GetCatalog('accountcode')."',width:'120px'},
								{field:'accountname',title:'".GetCatalog('accountname')."',width:'250px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.accountname;
					}
				},
				{
					field:'plantid',
					title:'".GetCatalog('plantcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'plantid',
							textField:'plantcode',
						    url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true)) ."',
							fitColumns:true,
							pagination:true,
							queryParams:{
								trxcom:true
							},
							required:true,
							onBeforeLoad: function(param) {
								 param.companyid = $('#companyid').combogrid('getValue');
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'plantid',title:'".GetCatalog('plantid')."',width:'80px'},
								{field:'plantcode',title:'".GetCatalog('plantcode')."',width:'100px'},
								{field:'description',title:'".GetCatalog('description')."',width:'200px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.plantcode;
					}
				},
				{
					field:'debit',
					title:'".GetCatalog('debit') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
							{
					field:'credit',
					title:'".GetCatalog('credit') ."',
					width:'100px',
										 editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'currencyid',
					title:'".GetCatalog('currency') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'currencyid',
									textField:'currencyname',
									url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true)) ."',
									fitColumns:true,
									pagination:true,
									required:true,
									queryParams:{
										combo:true
									},
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'currencyid',title:'".GetCatalog('currencyid')."',width:'50px'},
										{field:'currencyname',title:'".GetCatalog('currencyname')."',width:'150px'},
									]]
							}	
						},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.currencyname;
					}
				},
				{
					field:'ratevalue',
					title:'".GetCatalog('ratevalue') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							value:1,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'detailnote',
					title:'".GetCatalog('detailnote')."',
					editor: 'textbox',
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),	
));