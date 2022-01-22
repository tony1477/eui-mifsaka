<input type="hidden" name="slocid" id="slocid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'slocaccid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/slocaccounting/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/slocaccounting/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/slocaccounting/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/slocaccounting/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/slocaccounting/upload'),
	'downpdf'=>Yii::app()->createUrl('common/slocaccounting/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/slocaccounting/downxls'),
	'columns'=>"
		{
		field:'slocaccid',
		title:'". GetCatalog('slocaccid') ."',
		sortable: true,
		width: '50px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'slocid',
		title:'". GetCatalog('sloc') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'slocid',
				textField:'sloccode',
				url:'". $this->createUrl('sloc/indexcombo',array('grid'=>true)) ."',
				fitColumns:true,
				required:true,
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'slocid',title:'". GetCatalog('slocid')."',width:50},
					{field:'sloccode',title:'". GetCatalog('sloccode')."',width:100},
					{field:'description',title:'". GetCatalog('description')."',width:150},
				]],
				onBeforeLoad: function(param) {
					var row = $('#dg-slocaccounting').edatagrid('getSelected');
					if(row==null){
						$(\"input[name='slocid']\").val('0');
					}
				},
				onSelect: function(index,row){
					var sloc = row.slocid;
					$(\"input[name='slocid']\").val(row.slocid);
				}
			}	
		},
		width: '200px',
		sortable: true,
		formatter: function(value,row,index){
			return row.sloccode;
	}},
	{
		field:'materialgroupid',
		title:'". GetCatalog('materialgroup') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'materialgroupid',
				textField:'description',
				url:'". $this->createUrl('materialgroup/indextrx',array('grid'=>true)) ."',
				fitColumns:true,
				pagination:true,
				required:true,
				queryParams:{
					combo:true
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'materialgroupid',title:'". GetCatalog('unitofmeasureid')."',width:'50px'},
					{field:'materialgroupcode',title:'". GetCatalog('materialgroupcode')."',width:'80px'},
					{field:'description',title:'". GetCatalog('description')."',width:'200px'},
				]]
			}	
		},
		width:'250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.description;
	}},
	{
		field:'accaktivatetap',
		title:'". GetCatalog('accaktivatetap') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').datagrid('getSelected');
						param.slocid = row.slocid;
					}else{
					param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'250px'},
				]]
			}	
		},
		width: '150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accaktivatetapname;
	}},
	{
		field:'accakumat',
		title:'". GetCatalog('accakumat') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:450,
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
							var row = $('#dg-slocaccounting').edatagrid('getSelected');
							param.slocid = row.slocid;
					}else{
						param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'250px'},
				]]
				}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accakumatname;
	}},
	{
		field:'accbiayaat',
		title:'". GetCatalog('accbiayaat') ."',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:450,
					mode : 'remote',
					method:'get',
					pagination:true,
					idField:'accountid',
					textField:'accountname',
					url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
					fitColumns:true,
					queryParams:{
						slocid:0
					},              
					onBeforeLoad: function(param) {
						var sloc = $(\"input[name='slocid']\").val();
						if(sloc==''){
								var row = $('#dg-slocaccounting').edatagrid('getSelected');
								param.slocid = row.slocid;
						}else{
							param.slocid = $(\"input[name='slocid']\").val(); }
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:50},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:200},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:200},
					]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accbiayaatname;
	}},
	{
		field:'accpersediaan',
		title:'". GetCatalog('accpersediaan') ."',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					pagination:true,
					idField:'accountid',
					textField:'accountname',
					url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
					fitColumns:true,
					queryParams:{
						slocid:0
					},              
					onBeforeLoad: function(param) {
						var sloc = $(\"input[name='slocid']\").val();
						if(sloc==''){
							var row = $('#dg-slocaccounting').edatagrid('getSelected');
							param.slocid = row.slocid;
						} else {
							param.slocid = $(\"input[name='slocid']\").val(); }
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
					]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accpersediaanname;
	}},
	{
		field:'accreturpembelian',
		title:'". GetCatalog('accreturpembelian') ."',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:450,
					mode : 'remote',
					method:'get',
					pagination:true,
					idField:'accountid',
					textField:'accountname',
					url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
					fitColumns:true,
					queryParams:{
						slocid:0
					},              
					onBeforeLoad: function(param) {
						var sloc = $(\"input[name='slocid']\").val();
						if(sloc==''){
							var row = $('#dg-slocaccounting').edatagrid('getSelected');
							param.slocid = row.slocid;
						}else{
							param.slocid = $(\"input[name='slocid']\").val(); }
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
					]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accreturpembelianname;
	}},
	{
		field:'accdiscpembelian',
		title:'". GetCatalog('accdiscpembelian') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
					 param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accdiscpembelianname;
	}},
	{
		field:'accpenjualan',
		title:'". GetCatalog('accpenjualan') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
							var row = $('#dg-slocaccounting').edatagrid('getSelected');
							param.slocid = row.slocid;
					} else {
					param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accpenjualanname;
	}},
	{
		field:'accbiaya',
		title:'". GetCatalog('accbiaya') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accbiayaname;
	}},
	{
		field:'accreturpenjualan',
		title:'". GetCatalog('accreturpenjualan') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accreturpenjualanname;
	}},
	{
		field:'accspsi',
		title:'". GetCatalog('accspsi') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accspsiname;
	}},
	{
		field:'accexpedisi',
		title:'". GetCatalog('accexpedisi') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accexpedisiname;
	}},
	{
		field:'hpp',
		title:'". GetCatalog('hpp') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
					 param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.acchppname;
	}},
	{
		field:'accupahlembur',
		title:'". GetCatalog('accupahlembur') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accupahlemburname;
	}},
	{
		field:'foh',
		title:'". GetCatalog('foh') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accfohname;
	}},
    {
		field:'acckoreksi',
		title:'". GetCatalog('acckoreksi') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').datagrid('getSelected');
						param.slocid = row.slocid;
					}else{
					param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'250px'},
				]]
			}	
		},
		width: '150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.acckoreksiname;
	}},
    {
		field:'acccadangan',
		title:'". GetCatalog('acccadangan') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').datagrid('getSelected');
						param.slocid = row.slocid;
					}else{
					param.slocid = $(\"input[name='slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'250px'},
				]]
			}	
		},
		width: '150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.acccadanganname;
	}}",
	'searchfield'=> array ('slocaccid','sloccode','materialgroupname')
));