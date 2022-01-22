<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'bomid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('production/bom/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/bom/getData'),
	'saveurl'=>Yii::app()->createUrl('production/bom/save'),
	'destroyurl'=>Yii::app()->createUrl('production/bom/purge'),
	'uploadurl'=>Yii::app()->createUrl('production/bom/upload'),
	'downpdf'=>Yii::app()->createUrl('production/bom/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/bom/downxls'),
	'columns'=>"
		{
			field:'bomid',
			title:'".getCatalog('bomid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bomversion',
			title:'".getCatalog('bomversion') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bomdate',
			title:'".getCatalog('bomdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productid',
			title:'".getCatalog('product') ."',
			sortable: true,
			width:'450px',
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'qty',
			title:'".getCatalog('qty') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'uomid',
			title:'".getCatalog('uom') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return row.uomcode;
		}},
    {
      field:'cycletime',
      title:'".getCatalog('cycletimesec') ."',
      sortable: true,
      width:'80px',
      formatter: function(value,row,index){
        return value;
    }},
    {
      field:'cycletimemin',
      title:'".getCatalog('cycletimemin') ."',
      sortable: true,
      width:'80px',
      formatter: function(value,row,index){
        return value;
    }},
    {
      field:'hppcycletime',
      title:'".getCatalog('hppcycletime') ."',
      sortable: true,
      hidden:".GetMenuAuth('hppct').",
      width:'80px',
      formatter: function(value,row,index){
        return value;
    }},
    {
      field:'hppcycletimemin',
      title:'".getCatalog('hppcycletimemin') ."',
      sortable: true,
      hidden:".GetMenuAuth('hppct').",
      width:'80px',
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
			align:'center',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'rowstyler'=>"
		if (row.count >= 1){
			return 'background-color:blue;color:#fff;font-weight:bold;';
		}
	",
	'searchfield'=> array ('bomid','bomversion','bomdate','product','description','uom','productdetail','cycletime','recordstatus'),
	'headerform'=> "
		<input type='hidden' id='bomid' name='bomid'></input>
		<table cellpadding='5'>
		<tr>
				<td>".getCatalog('bomdate')."</td>
				<td><input class='easyui-datebox' type='text' id='bomdate' name='bomdate' data-options='formatter:dateformatter,required:true,parser:dateparser' ></input></td>
			</tr>
			<tr>
				<td>".getCatalog('bomversion')."</td>
				<td><input class='easyui-textbox' type='text' id='bomversion' name='bomversion' data-options='required:true' style='width:250px'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('product')."</td>
				<td><select class='easyui-combogrid' id='productid' name='productid' style='width:500px' data-options=\"
								panelWidth: '500px',
								idField: 'productid',
								required: true,
								textField: 'productname',
								pagination:true,
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true)) ."',
								method: 'get',
								onChange: function(newValue, oldValue){
									if ((newValue !== oldValue) && (newValue !== ''))
									{
										jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/getdata') ."',
											'data':{
												'productid':$('#productid').combogrid('getValue'),
											},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#uomid').combogrid('setValue',data.uomid);	
												$('#uomid').combogrid('grid').datagrid('load','".Yii::app()->createUrl('common/unitofmeasure/index',array('productplant'=>true)) ."?productid='+$('#productid').combogrid('getValue'));	
											},
											'cache':false});
									}
								},
								queryParams:{
									combo:true
								},
								columns: [[
										{field:'productid',title:'".getCatalog('productid') ."',width:'80px'},
										{field:'productname',title:'".getCatalog('productname') ."',width:'300px'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('qty')."</td>
				<td><input class='easyui-numberbox' type='text' id='qty' name='qty' value='1' data-options='required:true,precision:4' ></td>
			</tr>
			<tr>
				<td>".getCatalog('uom')."</td>
				<td><select class='easyui-combogrid' id='uomid' name='uomid' style='width:250px' data-options=\"
								panelWidth: '500px',
								idField: 'unitofmeasureid',
								required: true,
								textField: 'uomcode',
								mode : 'remote',
								url: '".Yii::app()->createUrl('common/unitofmeasure/index',array('productplant'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'unitofmeasureid',title:'".getCatalog('unitofmeasureid') ."',width:'80px'},
										{field:'uomcode',title:'".getCatalog('uomcode') ."',width:'100px'},
										{field:'description',title:'".getCatalog('description') ."',width:'150px'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>			
			<tr>
				<td>".getCatalog('description')."</td>
				<td><input class='easyui-textbox' type='textarea' name='description' data-options='required:true' ></input></td>
			</tr>
            <tr>
                <td>".getCatalog('cycletimesec')."</td>
                <td><input class='easyui-numberbox' type='text' id='cycletime' name='cycletime' value='1' 
                data-options=
                'required:true,
                 precision:2,
                 onChange:function(newValue,oldValue) {
						if ((newValue !== oldValue))
						{
                            var n = $(this).val();
                            $(\"#cycletimemin\").numberbox(\"setValue\",n/60);
                            //console.log(n/60);
                        }
                        }'
                ></td>
            </tr>
            <tr>
                <td>".getCatalog('cycletimemin')."</td>
                <td><input class='easyui-numberbox' type='text' id='cycletimemin' name='cycletimemin' value='1' data-options='disabled:true,precision:2' ></td>
            </tr>
            <tr>
                <td>".getCatalog('hppcycletime')."</td>
                <td><input class='easyui-numberbox' type='text' id='hppcycletime' name='hppcycletime' value='1' 
                data-options=
                'required:true,
                 precision:2,
                 onChange:function(newValue,oldValue) {
						if ((newValue !== oldValue))
						{
                            var l = $(this).val();
                            $(\"#hppcycletimemin\").numberbox(\"setValue\",l/60);
                            //console.log(n/60);
                        }
                        }'
                ></td>
            </tr>
            <tr>
                <td>".getCatalog('hppcycletimemin')."</td>
                <td><input class='easyui-numberbox' type='text' id='hppcycletimemin' name='hppcycletimemin' value='1' data-options='disabled:true,precision:2' ></td>
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
		<a href='javascript:void(0)' title='Copy'class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='copyBom()'></a>
	",
	'addonscripts'=>"
		function copyBom() {
			var ss = [];
			var rows = $('#dg-bom').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.bomid);
			}
			jQuery.ajax({'url':'".$this->createUrl('bom/copybom') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-bom').edatagrid('reload');				
				} ,
				'cache':false});
		};
        
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'bomdetailid',
			'urlsub'=>Yii::app()->createUrl('production/bom/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/bom/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/bom/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/bom/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/bom/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'bomdetailid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'productname',title:'".getCatalog('productname') ."',width:'400px'},
				{field:'qty',title:'".getCatalog('qty') ."',align:'right',width:'80px'},
				{field:'uomcode',title:'".getCatalog('uom') ."',width:'80px'},
				{field:'productbomversion',title:'".getCatalog('bom') ."',width:'300px'},
			",
			'onsuccess'=>"
				$('#dg-bom-detail').edatagrid('reload');
			",
			'onerror'=>"
				$('#dg-bom-detail').edatagrid('reload');
			",
			'onbeforesave'=>"
				var row = $('#dg-bom-detail').edatagrid('getSelected');
				if (row)
				{
					row.bomid = $('#bomid').val();
				}
			",
			'columns'=>"
				{
					field:'bomid',
					title:'".getCatalog('bomid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'bomdetailid',
					title:'".getCatalog('bomdetailid') ."',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'productid',
					title:'".getCatalog('product') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							pagination:true,		
							required:true,
							onChange:function(newValue,oldValue) {
								if ((newValue !== oldValue) && (newValue !== ''))
								{
									var tr = $(this).closest('tr.datagrid-row');
									var index = parseInt(tr.attr('datagrid-row-index'));
									var productid = $('#dg-bom-detail').datagrid('getEditor', {index: index, field:'productid'});
									var uomid = $('#dg-bom-detail').datagrid('getEditor', {index: index, field:'uomid'});
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/getdata') ."',
										'data':{'productid':$(productid.target).combogrid('getValue')},
										'type':'post','dataType':'json',
										'success':function(data)
										{
											$(uomid.target).combogrid('setValue',data.uomid);
										} ,
										'cache':false});
								}
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'80px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'300px'},
							]]
						}	
					},
					width:'350px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productname;
					}
				},
				{
					field:'qty',
					title:'".getCatalog('qty') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.'
						}
					},
					sortable: true,
					required:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'uomid',
					title:'".getCatalog('uom') ."',
					editor:{
						type:'combogrid',
						options:{
								panelWidth:'450px',
								mode : 'remote',
								method:'get',
								idField:'unitofmeasureid',
								textField:'uomcode',
								url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
								fitColumns:true,
								pagination:true,
								loadMsg: '".getCatalog('pleasewait')."',
								columns:[[
									{field:'unitofmeasureid',title:'".getCatalog('unitofmeasureid')."',width:'80px'},
									{field:'uomcode',title:'".getCatalog('uomcode')."',width:'120px'},
								]]
						}	
					},
					required:true,
					sortable: true,
					formatter: function(value,row,index){
						return row.uomcode;
					}
				},
				{
					field:'productbomid',
					title:'".getCatalog('bom') ."',
					editor:{
						type:'combogrid',
						options:{
								panelWidth:'550px',
								mode : 'remote',
								method:'get',
								idField:'bomid',
								textField:'bomversion',
								url:'".$this->createUrl('bom/index',array('grid'=>true,'combo'=>true)) ."',
								fitColumns:true,
								pagination:true,
								loadMsg: '".getCatalog('pleasewait')."',
								columns:[[
									{field:'bomid',title:'".getCatalog('bomid')."',width:'80px'},
									{field:'bomversion',title:'".getCatalog('bomversion')."',width:'120px'},
									{field:'productname',title:'".getCatalog('productname')."',width:'200px'},
								]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productbomversion;
					}
				},
				{
					field:'description',
					title:'".getCatalog('description') ."',
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