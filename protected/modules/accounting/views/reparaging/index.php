<div id="tb-reparaging">
	<?php





 
if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a>Submit</a>
		<a href="javascript:void(0)" title="Submit"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="downpdfreparaging()"></a>
<?php }?>
</div>

<script type="text/javascript">

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
function downpdfreparaging() {
	window.open('<?php echo $this->createUrl('reparaging/downpdf') ?>');
}
</script>