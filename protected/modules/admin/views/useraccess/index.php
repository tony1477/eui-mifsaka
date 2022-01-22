<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'useraccessid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/useraccess/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/useraccess/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/useraccess/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/useraccess/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/useraccess/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/useraccess/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/useraccess/downxls'),
	'columns'=>"
		{
			field:'useraccessid',
			title:'".GetCatalog('useraccessid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'username',
			title:'".GetCatalog('username') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'realname',
			title:'".GetCatalog('realname') ."',
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'password',
			title:'".GetCatalog('password') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'email',
			title:'".GetCatalog('email') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'phoneno',
			title:'".GetCatalog('phoneno') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wanumber',
			title:'".GetCatalog('wanumber') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'telegramid',
			title:'".GetCatalog('telegramid') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'languageid',
			title:'".GetCatalog('language') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'languageid',
						textField:'languagename',
						url:'".$this->createUrl('language/index',array('grid'=>true)) ."',
						required:true,
						fitColumns:true,
						queryParams: {
							combo:true,
						},
						pagination:true,
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'languageid',title:'".GetCatalog('languageid')."',width:'50px'},
							{field:'languagename',title:'".GetCatalog('languagename')."',width:'150px'},
						]]
				}	
			},
			formatter: function(value,row,index){
				return row.languagename;
		}},
		{
			field:'themeid',
			title:'".GetCatalog('theme') ."',
			sortable: true,
			width:'100px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'themeid',
					textField:'themename',
					pagination:true,
					url:'".$this->createUrl('theme/index',array('grid'=>true)) ."',
					required:true,
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'themeid',title:'".GetCatalog('themeid')."',width:'50px'},
						{field:'themename',title:'".GetCatalog('themename')."',width:'150px'},
					]]
				}	
			},
			formatter: function(value,row,index){
				return row.themename;
		}},
		{
			field:'signature',
			title:'".GetCatalog('signature') ."',
			align:'center',
			width:'200px',
			editor:{
                type:'filebox',
                options:{
                    buttonText:'Browse',
                    accept:'image/*',
                    onChange: function(){
                        //e.preventDefault();    
                        var files = $(this).filebox('files')
                        var formData = new FormData();
                        for(var i=0; i<files.length; i++){
                            var file = files[i];
                            formData.append('file-sign',file,file.name);
                        }
                        $.ajax({
                            url:'".Yii::app()->createUrl('admin/useraccess/uploadsign')."',
                            type:'post',
                            data:formData,
                            contentType:false,
                            processData:false,
                            success:function(data){
                                console.log(data);
                            }
                        })
                        return false;
                    }
                }
            },
			sortable: true,
			formatter: function(value,row,index){
				if (row.images == '1'){
					return '<a href=".Yii::app()->request->baseUrl."/images/useraccess/' + value + ' target=_blank><img src=".Yii::app()->request->baseUrl."/images/useraccess/' + value + ' width=100 height=60 ></img></a>';
				} else {
					return '';
				}
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == '1'){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('useraccessid','username','realname','email','phoneno','languagename','themename','recordstatus')
));