<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'hppstdid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('accounting/hppstd/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/hppstd/getData'),
	'saveurl'=>Yii::app()->createUrl('accounting/hppstd/save'),
	'destroyurl'=>Yii::app()->createUrl('accounting/hppstd/purge'),
	'uploadurl'=>Yii::app()->createUrl('accounting/hppstd/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/hppstd/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/hppstd/downxls'),
	'columns'=>"
		{
			field:'hppstdid',
			title:'".getCatalog('hppstdid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title:'".getCatalog('plantcode') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'companyname',
			title:'".getCatalog('companyname') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'docdate',
			title:'". GetCatalog('docdate')."', 
			sortable:'true',
			width:'80px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productid',
			title:'".getCatalog('product') ."',
			sortable: true,
			width:'450px',
			formatter: function(value,row,index){
				return row.productname;
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
			field:'price',
			title:'".getCatalog('price') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return row.price;
		}},
    {
			field:'pricegenerate',
			title:'".getCatalog('pricegenerate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return row.pricegenerate;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
        return row.statusname;
		}},",
	'rowstyler'=>"
		if (row.count >= 1){
			return 'background-color:blue;color:#fff;font-weight:bold;';
		}
	",
	'searchfield'=> array ('hppstdid','plantcode','docdate','product','uomcode','recordstatus'),
	'headerform'=> "
		<input type='hidden' id='hppstdid' name='hppstdid'></input>
		<table cellpadding='5'>
			<tr>
				<td>".getCatalog('plantcode')."</td>
				<td><select class='easyui-combogrid' id='plantid' name='plantid' style='width:150px' data-options=\"
								panelWidth: '150px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								pagination:true,
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true)) ."',
								method: 'get',
								queryParams:{
									trxauth:true
								},
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('docdate')."</td>
				<td><input class='easyui-datebox' type='text' id='docdate' name='docdate' data-options='formatter:dateformatter,required:true,parser:dateparser' ></input></td>
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
				<td>".getCatalog('uom')."</td>
				<td><select class='easyui-combogrid' id='uomid' name='uomid' style='width:100px' data-options=\"
								panelWidth: '100px',
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
		<a href='javascript:void(0)' title='Copy'class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='copyHppstd()'></a>
	",
	'addonscripts'=>"
		function copyHppstd() {
			var ss = [];
			var rows = $('#dg-hppstd').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.hppstdid);
			}
			jQuery.ajax({'url':'".$this->createUrl('hppstd/copyhppstd') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-hppstd').edatagrid('reload');				
				} ,
				'cache':false});
		};
        
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'hppstddetid',
			'urlsub'=>Yii::app()->createUrl('accounting/hppstd/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/hppstd/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/hppstd/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/hppstd/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/hppstd/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'hppstddetid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'productname',title:'".getCatalog('productname') ."',width:'400px'},
				{field:'uomcode',title:'".getCatalog('uom') ."',width:'80px'},
				{field:'isheader',title:'".getCatalog('isheader') ."',width:'80px',
        formatter: function(value) {
            if (value == 1){
              return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
            } else {
              return '';
            }
        }},
				{field:'qtyheader',title:'".getCatalog('qtyheader') ."',align:'right',width:'80px'},
				{field:'qty',title:'".getCatalog('qty') ."',align:'right',width:'80px'},
				{field:'price',title:'".getCatalog('price') ."',align:'right',width:'150px'},
				{field:'pricegenerate',title:'".getCatalog('pricegenerate') ."',align:'right',width:'150px'},
				{field:'isbold',title:'".getCatalog('isbold') ."',width:'100px',
        formatter: function(value) {
            if (value == 1){
              return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
            } else {
              return '';
            }
        }},
				{field:'nourut',title:'".getCatalog('nourut') ."',align:'right',width:'50px'},
			",
			'onsuccess'=>"
				$('#dg-hppstd-detail').edatagrid('reload');
			",
			'onerror'=>"
				$('#dg-hppstd-detail').edatagrid('reload');
			",
			'onbeforesave'=>"
				var row = $('#dg-hppstd-detail').edatagrid('getSelected');
				if (row)
				{
					row.hppstdid = $('#hppstdid').val();
				}
			",
			'columns'=>"
				{
					field:'hppstdid',
					title:'".getCatalog('hppstdid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'hppstddetid',
					title:'".getCatalog('hppstddetid') ."',
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
									let tr = $(this).closest('tr.datagrid-row');
									let index = parseInt(tr.attr('datagrid-row-index'));
									let productid = $('#dg-hppstd-detail').datagrid('getEditor', {index: index, field:'productid'});
									let uomid = $('#dg-hppstd-detail').datagrid('getEditor', {index: index, field:'uomid'});
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
          width:'80px',
					formatter: function(value,row,index){
						return row.uomcode;
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
              groupSeparator:'.',
              required:true,
            }
          },
          width:'100px',
          required:true,
          sortable: true,
          formatter: function(value,row,index){
            return value;
          }
        },
        {
          field:'isheader',
          title:'". GetCatalog('isheader') ."',
          align:'center',
          width:'70px',
          editor:{type:'checkbox',options:{on:'1',off:'0'}},
          sortable: true,
          formatter: function(value,row,index){
            if (value == 1){
              return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
            } else {
              return '';
            }
        }},
        {
          field:'parentid',
          title:'". GetCatalog('parent') ."',
          editor:{
            type:'combogrid',
            options:{
              panelWidth:'500px',
              mode : 'remote',
              method:'get',
              idField:'hppstddetid',
              textField:'hppstdcode',
              pagination:true,
              url:'". $this->createUrl('hppstd/GetProductParent') ."',
              queryParams:{
                trxparent:true
              },
              onChange:function(newValue,oldValue) {
								if ((newValue !== oldValue) && (newValue !== ''))
								{
									let tr = $(this).closest('tr.datagrid-row');
									let index = parseInt(tr.attr('datagrid-row-index'));
									let hppstdcodes = $('#dg-hppstd-detail').datagrid('getEditor', {index: index, field:'hppstdcode'});
									let parentids = $('#dg-hppstd-detail').datagrid('getEditor', {index: index, field:'parentid'});
									jQuery.ajax({'url':'".Yii::app()->createUrl('accounting/hppstd/gethppstdcode') ."',
										'data':{'parentid':$(parentids.target).combogrid('getValue')},
										'type':'post','dataType':'json',
										'success':function(data)
										{
											$(hppstdcodes.target).textbox('setValue',data.hppstdcode);
										} ,
										'cache':false});
								}
							},
              fitColumns:true,
              onBeforeLoad: function(param) {
                const hppstdid = $(\"input[name='hppstdid']\").val();
                param.hppstdid = hppstdid;
              },
              loadMsg: '". GetCatalog('pleasewait')."',
              columns:[[
                {field:'hppstddetid',title:'". GetCatalog('hppstddetid')."',width:'100px'},
                {field:'hppstdcode',title:'". GetCatalog('hppstdcode')."',width:'100px'},
                {field:'productname',title:'". GetCatalog('productname')."',width:'350px'},
              ]]
            }	
          },
          width:'350px',
          sortable: true,
          formatter: function(value,row,index){
            return row.productparent;
        }},
        {
					field:'hppstdcode',
					title:'".getCatalog('hppstdcode') ."',
					editor:'textbox',
					sortable: true,
					required:true,
          width:'150px',
					formatter: function(value,row,index){
						return value;
					}
				},
        {
          field:'price',
          title:'".getCatalog('price') ."',
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
          required:true,
          sortable: true,
          formatter: function(value,row,index){
            return value;
          }
        },
        {
          field:'pricegenerate',
          title:'".getCatalog('pricegenerate') ."',
          editor:{
            type:'numberbox',
            options:{
              precision:4,
              decimalSeparator:',',
              groupSeparator:'.',
              readonly:true,
            }
          },
          width:'100px',
          sortable: true,
          formatter: function(value,row,index){
            return value;
          }
        },
				{
          field:'isbold',
          title:'". GetCatalog('isbold') ."',
          align:'center',
          width:'100px',
          editor:{type:'checkbox',options:{on:'1',off:'0'}},
          sortable: true,
          formatter: function(value,row,index){
            if (value == 1){
              return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
            } else {
              return '';
            }
        }},
        {
          field:'isview',
          title:'". GetCatalog('isview') ."',
          align:'center',
          width:'100px',
          editor:{type:'checkbox',options:{on:'1',off:'0'}},
          sortable: true,
          formatter: function(value,row,index){
            if (value == 1){
              return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
            } else {
              return '';
            }
        }},
        {
					field:'nourut',
					title:'".getCatalog('nourut') ."',
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