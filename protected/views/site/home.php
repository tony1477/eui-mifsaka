<div class="easyui-tabs" style="width:100%;height:100%">
	<div title="<?php echo getcatalog('userprofile')?>" style="padding:10px">
	<?php $model = Yii::app()->db->createCommand("select `username`,`password`,realname,email,phoneno from useraccess where lower(username) = lower('".Yii::app()->user->name."')")->queryRow();?>
<div style="padding:10px 30px 20px 30px">
<form id="UserProfile" class="easyui-form" method="post" data-options="novalidate:true">
	<table cellpadding="5">
		<tr>
			<td style="padding:5px">Nama User</td>
			<td><input class="easyui-textbox" type="text" name="username" data-options="required:true" value="<?php echo $model['username']?>"></input></td>
		</tr>
		<tr>
			<td style="padding:5px">Nama Asli</td>
			<td><input class="easyui-textbox" type="text" name="realname" data-options="required:true" value="<?php echo $model['realname']?>" style='width:200px'></input></td>
		</tr>
		<tr>
			<td style="padding:5px">Email</td>
			<td><input class="easyui-textbox" type="text" name="email" data-options="required:true" value="<?php echo $model['email']?>" style='width:300px'></input></td>
		</tr>
		<tr>
			<td style="padding:5px">No Telp</td>
			<td><input class="easyui-textbox" type="text" name="phoneno" data-options="required:true" value="<?php echo $model['phoneno']?>"></input></td>
		</tr>
		<tr>
			<td style="padding:5px">Password</td>
			<td><input class="easyui-textbox" type="text" name="password" data-options="required:true" value="<?php echo $model['password']?>" style='width:300px'></input></td>
		</tr>
		<tr>
			<td style="padding:5px"><a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitProfile()">Submit</a></td>
			<td></td>
		</tr>
	</table>
</form>
</div>
<script>
	function submitProfile(){
		$('#UserProfile').form('submit',{
	url:'<?php echo Yii::app()->createUrl('site/saveprofile') ?>',
	onSubmit:function(){
			return $(this).form('enableValidation').form('validate');
	},
	success:function(data){
		var data = eval('(' + data + ')');  // change the JSON string to javascript object
		show('Pesan',data.msg)
	}
});
	}
</script>
	</div>
</div>