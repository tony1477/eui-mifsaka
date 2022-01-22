<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productconversionid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('common/productconversion/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('common/productconversion/getData'),
	'saveurl'=>Yii::app()->createUrl('common/productconversion/save'),
	'destroyurl'=>Yii::app()->createUrl('common/productconversion/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/productconversion/upload'),
	'downpdf'=>Yii::app()->createUrl('common/productconversion/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/productconversion/downxls'),
	'columns'=>"
		{
			field:'productconversionid',
			title:'". GetCatalog('productconversionid') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productid',
			title:'". GetCatalog('productname') ."',
			sortable: true,
			width:'500px',
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'qty',
			title:'". GetCatalog('qty') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'uomid',
			title:'". GetCatalog('uom') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.uomcode;
		}},
        {
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('productconversionid','productname','uomcode'),
	'headerform'=> "
		<input type='hidden' name='productconversionid' id='productconversionid'></input>
		<table cellpadding='5'>
			<tr>
				<td>". GetCatalog('productname')."</td>
				<td><select class='easyui-combogrid' id='productid' name='productid' style='width:250px' data-options=\"
						panelWidth: '500px',
						idField: 'productid',
						required: true,
						textField: 'productname',
						pagination:true,
						url: '". Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						mode:'remote',
						onChange: function(newValue, oldValue){
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								jQuery.ajax({'url':'". Yii::app()->createUrl('common/productconversion/generatedetail') ."',
									'data':{
											'id':newValue
										},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$('#uomid').combogrid('setValue',data.uomid);				
									} ,
									'cache':false});
							}
						},
						columns: [[
								{field:'productid',title:'". GetCatalog('productid') ."'},
								{field:'productname',title:'". GetCatalog('productname') ."'},
						]],
						fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('qty')."</td>
				<td><input class='easyui-numberbox' type='text' name='qty' data-options=\"
					required:true,
					type: 'numberbox',
					options:{
						precision:4,
						decimalSeparator:',',
						groupSeparator:'.',
						value:'0',
						required:true,
					}\" >
				</td>
			</tr>
			<tr>
				<td>". GetCatalog('uomcode')."</td>
				<td><select class='easyui-combogrid' id='uomid' name='uomid' style='width:250px' data-options=\"
					panelWidth: '500px',
					idField: 'unitofmeasureid',
					required: true,
					textField: 'uomcode',
					pagination:true,
					url: '". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					method: 'get',
					mode:'remote',
					columns: [[
						{field:'unitofmeasureid',title:'". GetCatalog('uomid') ."',width:'80px'},
						{field:'uomcode',title:'". GetCatalog('uomcode') ."',width:'120px'},
					]],
					fitColumns: true\">
				</select></td>
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
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'productconversiondetailid',
			'urlsub'=>Yii::app()->createUrl('common/productconversion/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/productconversion/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/productconversion/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/productconversion/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/productconversion/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'". GetCatalog('productname') ."',width:'300px'},
				{field:'qty',title:'". GetCatalog('qty') ."',width:'100px'},
				{field:'uomcode',title:'". GetCatalog('uomcode') ."',width:'150px'},
			",
			'columns'=>"
				{
					field:'productconversionid',
					title:'". GetCatalog('productconversionid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'productconversiondetailid',
					title:'". GetCatalog('productconversiondetailid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'productid',
					title:'". GetCatalog('product') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'". Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							onChange:function(newValue,oldValue) {
								if ((newValue !== oldValue) && (newValue !== ''))
								{
									var tr = $(this).closest('tr.datagrid-row');
									var index = parseInt(tr.attr('datagrid-row-index'));
									var productid = $('#dg-productconversion-detail').datagrid('getEditor', {index: index, field:'productid'});
									var uomid = $('#dg-productconversion-detail').datagrid('getEditor', {index: index, field:'uomid'});
									jQuery.ajax({'url':'". Yii::app()->createUrl('common/productplant/getdata') ."',
										'data':{'productid':$(productid.target).combogrid('getValue')},
										'type':'post','dataType':'json',
										'success':function(data)
										{
											$(uomid.target).combogrid('setValue',data.uomid);
										} ,
										'cache':false});
								}
							},
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'". GetCatalog('productid')."',width:'80px'},
								{field:'productname',title:'". GetCatalog('productname')."',width:'300px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.productname;
					}
				},
				{
					field:'qty',    
					title:'". GetCatalog('qty') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'uomid',
					title:'". GetCatalog('uom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'80px'},
								{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.uomcode;
					}
				}
			"
		),
	),	
));
