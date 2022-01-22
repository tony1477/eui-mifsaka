<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'transdocid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('hr/transdoc/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('hr/transdoc/getData'),
	'saveurl'=>Yii::app()->createUrl('hr/transdoc/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/transdoc/purge',array('grid'=>true)),
    'ispost'=>1,
	'approveurl'=>Yii::app()->createUrl('hr/transdoc/approve'),
	'uploadurl'=>Yii::app()->createUrl('hr/transdoc/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/transdoc/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/transdoc/downxls'),
	'columns'=>"
		{
			field:'transdocid',
			title:'". GetCatalog('transdocid') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'transdate',
			title:'". GetCatalog('sertibdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'transdocno',
			title:'". GetCatalog('docno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fromemployeeid',
			title:'". GetCatalog('fromemployee') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.fromemployee;
		}},
        {
			field:'toemployeeid',
			title:'". GetCatalog('toemployee') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.toemployee;
		}},
        {
                field:'docupload',
                title:'".GetCatalog('docupload') ."',
                align:'center',
                width:'200px',
                sortable: true,
                formatter: function(value,row,index){
                    if (row.uploaded == '1'){
                        return '<a href=".Yii::app()->request->baseUrl."/images/docupload/' + value + ' target=_blank>' + value + ' </a>';
                    } else {
                        return '';
                    }
        }},
        {
        field:'recordstatus',
        title:'". GetCatalog('recordstatus') ."',
        sortable: true,
        width:'120px',
        formatter: function(value,row,index){
            return row.statusname;
		}},",
    'addonscripts'=>"
        $(document).ready(function() {
                $('#uploadFile').form({
                    url:'".Yii::app()->createUrl('hr/transdoc/doccupload')."',
                    ajax:'true',
                    iframe:'false', // pour activer le onProgress
                    onChange: function(nv,ov){
                        return console.log($(this).files);
                    },
                    onProgress: function(percent){
                        // pendant l'envoi du fichier
                        //$('#progressFile').progressbar('setValue', percent);    
                        console.log('progess');
                    },
                    success: function(data){
                            // apres l'envoi du fichier en cas de succes
                            alert('succes');
                    },
                    onLoadError: function(){
                            // apres l'envoi du fichier en cas d'erreur
                            alert('error');
                    }
                });


                $('#btn_add').bind('click', function(e){
                        e.preventDefault();    
                        var files = $('#docupload').filebox('files')
                        var formData = new FormData();
                        for(var i=0; i<files.length; i++){
                            var file = files[i];
                            formData.append('docupload',file,file.name);
                        }
                        $.ajax({
                            url:'".Yii::app()->createUrl('hr/transdoc/docupload')."',
                            type:'post',
                            data:formData,
                            contentType:false,
                            processData:false,
                            success:function(data){
                                show('Pesan',data.pesan);
                                if(data.success==true) {
                                    $('#docname').val(file.name);
                                }
                            }
                        });
                        return false;
                    });
            });

    ",
	'searchfield'=> array ('transdocid','transdocno','fromeployee','toemployee'),
	'headerform'=> "
		<input type='hidden' name='transdocid' id='transdocid'></input>
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('sertibdate')."</td>
				<td><input class='easyui-datebox' type='text' id='transdate' name='transdate' data-options=\"formatter:dateformatter,required:true,parser:dateparser\"/></td>
			</tr>
			<tr>
				<td>". GetCatalog('fromemployee')."</td>
				<td><select class='easyui-combogrid' id='fromemployeeid' name='fromemployeeid' style='width:250px' data-options=\"
					panelWidth: '500px',
					idField: 'employeeid',
					required: true,
					textField: 'fullname',
					pagination:true,
					url: '". Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
					method: 'get',
					mode:'remote',
					columns: [[
                            {field:'employeeid',title:'". GetCatalog('employeeid')."'},
                            {field:'fullname',title:'". GetCatalog('fullname') ."'},
                            {field:'oldnik',title:'". GetCatalog('oldnik') . "'},
                            {field:'companyname',title:'". GetCatalog('companyname') ."'},
                    ]],
					fitColumns: true\">
				</select></td>
			</tr>
            <tr>
				<td>". GetCatalog('toemployee')."</td>
				<td><select class='easyui-combogrid' id='toemployeeid' name='toemployeeid' style='width:250px' data-options=\"
					panelWidth: '500px',
					idField: 'employeeid',
					required: true,
					textField: 'fullname',
					pagination:true,
					url: '". Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
					method: 'get',
					mode:'remote',
					columns: [[
                            {field:'employeeid',title:'". GetCatalog('employeeid')."'},
                            {field:'fullname',title:'". GetCatalog('fullname') ."'},
                            {field:'oldnik',title:'". GetCatalog('oldnik') . "'},
                            {field:'companyname',title:'". GetCatalog('companyname') ."'},
                    ]],
					fitColumns: true\">
				</select></td>
			</tr>
            <tr>
            <td>". GetCatalog('docupload')."</td>
                <td style=\"width:150px;\">
                <form id='uploadFile' enctype='multipart/form-data' method='post'>
                <input id='docupload' class='easyui-filebox' name='docupload' data-options=\"prompt:'Choose a file...'\" style=\"width:250px\">
                <input id='docname' name='docname' type='hidden' />
                </form>
                </td>
                <td style=\"width:30%;\"><a id='btn_add' class=\"easyui-linkbutton\" iconCls=\"icon-upload\" plain=\"true\">Add File</a>
                </td>
            </tr>
		</table>
	",
	'loadsuccess' => "",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'transdocdetailid',
			'urlsub'=>Yii::app()->createUrl('hr/transdoc/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('hr/transdoc/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/transdoc/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/transdoc/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/transdoc/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'docname',title:'". GetCatalog('legaldoc') ."',width:'300px'},
				{field:'storagedocname',title:'". GetCatalog('storagedoc') ."',width:'100px'},
			",
			'columns'=>"
				{
					field:'transdocid',
					title:'". GetCatalog('transdocid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'transdocdetid',
					title:'". GetCatalog('transdocdetid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'legaldocid',
					title:'". GetCatalog('legaldoc') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode: 'remote',
							method:'get',
							idField:'legaldocid',
							textField:'docname',
							url:'". Yii::app()->createUrl('hr/legaldoc/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'legaldocid',title:'". GetCatalog('legaldocid')."',width:'80px'},
								{field:'docname',title:'". GetCatalog('docname')."',width:'300px'},
								{field:'docno',title:'". GetCatalog('docno')."',width:'250px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.docname;
					}
				},
				{
					field:'storagedocid',
					title:'". GetCatalog('storagedoc') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'storagedocid',
							textField:'storagedocname',
							url:'". Yii::app()->createUrl('hr/storagedoc/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'storagedocid',title:'". GetCatalog('storagedocid')."',width:'80px'},
								{field:'storagedocname',title:'". GetCatalog('storagedocname')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.storagedocname;
					}
				},
			"
		),
	),	
));