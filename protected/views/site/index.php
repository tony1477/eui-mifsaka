<input type="hidden" id='clientippublic'>
<input type="hidden" id='clientiplocal'>
<input type="hidden" id='clientlat'>
<input type="hidden" id='clientlng'>
<input type="hidden" id='identityid' value=''>
<style>
.portlet {width:100%;height:100%}
.portlet-content {width:100%;height:100%}
</style>
<div class="easyui-layout" style="width:100%;height:98%">
	<div data-options="region:'north'" title="Favorit" style="height:80px;" >
		<a href="<?php echo Yii::app()->createUrl('site/logout')?>" data-options="iconCls:'icon-logout'" class="easyui-linkbutton c1" style="width:80px">Logout</a>
		<a href="#" data-options="iconCls:'icon-blog'" class="easyui-linkbutton c1" onclick="opentab('dashboard','admin/dashboard', 'Dashboard','icon-blog')"style="width:100px">Dashboard</a>
		<?php
			$menus = getUserFavs();
			foreach($menus AS $menu) {?>
			<a href="#" data-options="iconCls:'icon-<?php echo $menu['name']?>'" onclick="opentab('<?php echo $menu['name']?>','<?php echo $menu['url']?>', '<?php echo $menu['label']?>','icon-<?php echo $menu['name']?>')" class="easyui-linkbutton c4" ><?php echo $menu['label']?></a>
			<?php }	?>
	</div>
	<div id="mm" class="easyui-menu" style="width:200px;">
		<div onclick="appendit()" data-options="iconCls:'icon-add'">Tambah Menu ke Favorit</div>
		<div onclick="removeit()" data-options="iconCls:'icon-remove'">Hapus Menu dari Favorit</div>
		<div onclick="clearit()" data-options="iconCls:'icon-clear'">Bersihkan Favorit</div>
	</div>
	<div data-options="region:'west',split:true" title="Menu" style="width:300px;" >
		<div class="easyui-accordion" style="width:auto;">
			<?php
			$menus = getItems();
			foreach($menus AS $menu) {
				echo '<div title="'.$menu["label"].'" style="padding:10px;">';?>
				<ul class="easyui-tree" id="menutree" data-options="onContextMenu: function(e,node){
					e.preventDefault();
					$(this).tree('select',node.target);
					$('#mm').menu('show',{
							left: e.pageX,
							top: e.pageY
					});
				}">
			<?php
				$submenus = getSubmenu($menu['parentid']);
				foreach ($submenus as $submenu) {
					echo "<li>".CHtml::link($submenu['label'],'javascript:void(0)',array(
						'onClick'=>'opentab("'.$submenu['name'].'","'.$submenu['url'].'","'.$submenu['label'].'","icon-'.$submenu['name'].'")'
					))."</li>.";
				}
				echo '</ul>';
				echo '</div>';
			}?>
		</div>
	</div>
	<div data-options="region:'center',split:true" style="width:200px;">
	 <div class="easyui-tabs" id ="regtab" style="width:100%;height:100%" data-options="tools:'#tab-tools'">
		<div title="Home" data-options="closable:false" href="<?php echo $this->createUrl('site/home');?>" iconCls="icon-home" style="height:100%"></div>
	</div>
	<div id="tab-tools" style="padding-right:10px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-remove'" onclick="removePanel()"></a>
			<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-mini-refresh'" onclick="minirefresh()"></a>
    </div>
	</div>
</div>
<div id="aboutwindow" class="easyui-window" title="About Window" data-options="iconCls:'icon-save',minimizable:false,modal:true,closed:true" style="width:950px;height:500px;padding:10px;">
	<h1 style="color: black">Mifsaka</h1>
	<h2>Owner : Anugrah Karya Group</h2>
	<h3>Copyright 2015 - 2019 by ITE Development</h3><br><br>
	<table>
	<tr>
		<td style="width:50%"><b>CFO</b></td>
		<td style="width:50%">Suwito</td>
	</tr>
	<tr>
		<td><b>CIO</b></td>
		<td>Adhidharmarius Sutandi</td>
	</tr>
	<tr>
		<td><b>Software Analyst, Database Administrator, Basis</b></td>
		<td>Romy Andre</td>
	</tr>
	<tr>
		<td><b>Developer</b></td>
		<td>Martoni Firman, Andi Setyawan</td>
	</tr>
	<tr>
		<td><b>Support</b></td>
		<td>Febrizal Amir</td>
	</tr>
	</table>
	<br><br><b>Implementor (Trainer, Configurator)</b>
	<table>
	<tr style="padding-bottom:80px">
		<td style="width:50%">1. Adhidharmarius Sutandi</td>
		<td style="width:50%">Implementor Head</td>
	</tr>
	<tr>
		<td>2. Romy Andre</td>
		<td>ITE, Order, Inventory, Purchasing</td>
	</tr>
	<tr>
		<td>3. Risa Era Gultom</td>
		<td>Accounting</td>
	</tr>
	<tr>
		<td>4. Sri Wanti</td>
		<td>Accounting</td>
	</tr>
	<tr>
		<td>5. Ratna Sari Dewi</td>
		<td>Accounting, Common</td>
	</tr>
	<tr>
		<td>6. Rinto Simamora</td>
		<td>Accounting</td>
	</tr>
	<tr>
		<td>7. Martoni Firman</td>
		<td>ITE, Order, Inventory, Purchasing</td>
	</tr>
	<tr>
		<td>8. Ebenson Sihaloho</td>
		<td>Production, PPIC, RND</td>
	</tr>
	<tr>
		<td>9. Maria Nainggolan</td>
		<td>Accounting</td>
	</tr>
	<tr>
		<td>10. Notje</td>
		<td>Production, PPIC, RND</td>
	</tr>
	<tr>
		<td>11. Yuliana</td>
		<td>HRD</td>
	</tr>
	</table>
	 <div id="p" class="easyui-panel" title="Petunjuk" style="width:100%;height:110px;padding:10px;">
			F1: About, F2: New, F3: Edit (Chrome), F4: Find, F5: Page Refresh, F6: Save, F7: Cancel, F8: Purge, F9: Approve, F10: Reject, F11: PDF (Chrome), F12: XLS
			<br>Alt > F1: Upload a File, F2: New Detail, F3: Edit Detail, F11 : PDF (Opera)
			<br>Ctrl > F1: Choose File Upload, F3: Edit (Opera), F5: Tab Refresh<br>        
    </div>
</div>
<div id="loaderwindow" class="easyui-window" title="Loader Window" data-options="iconCls:'icon-save',closable:false,minimizable:false,inline:true,closed:true,border:'false'" style="width:100%;height:100%;padding:10px;">
	<img src="<?php echo Yii::app()->baseUrl?>/images/loading.gif" class="img-responsive" />
</div>
<script type="text/javascript">
var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);
var isChrome = !!window.chrome && !!window.chrome.webstore;
var isBlink = (isChrome || isOpera) && !!window.CSS;
function GetUserLogin() {
	var a = $('#identityid').val(); 
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('site/getuserlogin')?>',
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.msg == null) {
				window.location.href = "<?php echo Yii::app()->createUrl('site/login')?>";
			} else 
				if (a == '') {
					$('#identityid').val(data.identityid);
			} else 
				if (a != data.identityid) {
				window.location.href = "<?php echo Yii::app()->createUrl('site/login')?>";	
			}
		},
		'cache':false});
};
function checkState(){
	if(document.readyState == 'complete'){
		clearInterval(interValRef);
	} 
}
cekkoneksi = setInterval(GetUserLogin, 50000);
interValRef = 0;
interValRef = setInterval(checkState,100);
$(document).ajaxError(function( event, jqxhr, settings, thrownError ) {
	if (settings.url == '<?php echo Yii::app()->createUrl('site/getuserlogin')?>') {
	show('Pesan','Koneksi ke server terputus, jangan tekan refresh halaman (F5) atau ada prosedur '+settings.url+' error','error');
	}
});
$(document).ready(function(){
	jQuery.ajax({'url':'https://api.ipify.org?format=jsonp&callback=?',
		'type':'post','dataType':'json',
		'success':function(data)
		{
				$('#clientippublic').val(data.ip);	
		} ,
		'cache':false});
	getLocation();
});
var RTCPeerConnection = /*window.RTCPeerConnection ||*/ window.webkitRTCPeerConnection || window.mozRTCPeerConnection;
if (RTCPeerConnection) (function () {
	var rtc = new RTCPeerConnection({iceServers:[]});
	if (1 || window.mozRTCPeerConnection) {      // FF [and now Chrome!] needs a channel/stream to proceed
		rtc.createDataChannel('', {reliable:false});
	};
	rtc.onicecandidate = function (evt) {
		if (evt.candidate) grepSDP("a="+evt.candidate.candidate);
	};
	rtc.createOffer(function (offerDesc) {
		grepSDP(offerDesc.sdp);
		rtc.setLocalDescription(offerDesc);
	}, function (e) { console.warn("offer failed", e); });
	var addrs = Object.create(null);
	addrs["0.0.0.0"] = false;
	function updateDisplay(newAddr) {
		if (newAddr in addrs) return;
		else addrs[newAddr] = true;
		var displayAddrs = Object.keys(addrs).filter(function (k) { return addrs[k]; });
		$('#clientiplocal').val (displayAddrs.join(" or perhaps ") || "n/a");
	}
	function grepSDP(sdp) {
		var hosts = [];
		sdp.split('\r\n').forEach(function (line) { // c.f. http://tools.ietf.org/html/rfc4566#page-39
			if (~line.indexOf("a=candidate")) {     // http://tools.ietf.org/html/rfc4566#section-5.13
				var parts = line.split(' '),        // http://tools.ietf.org/html/rfc5245#section-15.1
						addr = parts[4],
						type = parts[7];
				if (type === 'host') updateDisplay(addr);
			} else if (~line.indexOf("c=")) {       // http://tools.ietf.org/html/rfc4566#section-5.7
				var parts = line.split(' '),
						addr = parts[2];
				updateDisplay(addr);
			}
		});
	}
})(); else {
	$('#clientiplocal').val("<code>ifconfig | grep inet | grep -v inet6 | cut -d\" \" -f2 | tail -n1</code>");
}
function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} else { 
		$('#clientlat').val('');
		$('#clientlng').val('');
	}
}    
function showPosition(position) {
	$('#clientlat').val(position.coords.latitude); 
	$('#clientlng').val(position.coords.longitude);
}
function openloader() {
	$('#loaderwindow').window('open');
}
function closeloader() {
	$('#loaderwindow').window('close');
}
$(document).ready(function(){
	$(document).keydown(function (e) {
		var tab = $('#regtab').tabs('getSelected');
		var id = tab.panel('options').id;
		if (e.ctrlKey) {
			switch(e.which) {
				case 112: //Ctrl+F1 - Choose File Upload
					e.preventDefault();
					var elem = document.getElementById("file-"+id);
					if (elem != null) {
						elem.click();
					} 
					break;
				case 113: //Ctrl+F2
					e.preventDefault();
					break	;
				case 114: //F3 - Edit (opera)
					if (isOpera == true) {
						e.preventDefault();
						var elem = document.getElementById("edit-"+id);
						if (elem != null) {
							elem.click();
						} else {
							var selectedrow = $("#dg-"+id).datagrid("getSelected");
							var rowIndex = $("#dg-"+id).datagrid("getRowIndex", selectedrow);
							if (rowIndex > -1) {
								$('#dg-'+id).edatagrid('editRow',rowIndex);
							} else {
								show('Pesan','<?php echo getcatalog('chooseone')?>');
							}
						}
					}
					break;
				case 116: //Ctrl+F5 - Tab Refresh
					e.preventDefault();
					minirefresh();
					break;
			}
		} else if (e.altKey) {
			switch(e.which) {
				case 112: //Alt+F1 - File Upload
					e.preventDefault();
					var elem = document.getElementById("submit-"+id);
					if (elem != null) {
						elem.click();
					} 				
					break;
				case 113: //Alt+F2 - New Detail
					e.preventDefault();
					$('#tabdetails-'+id).tabs('getSelected').find('div > a.adddetail').click();
					break;
				case 114: //Alt+F3 - Edit 
					e.preventDefault();
					var datagrid = $('#tabdetails-'+id).tabs('getSelected').find('table.mytable');
					var selectedrow = datagrid.datagrid('getSelected');
					var rowIndex = datagrid.datagrid("getRowIndex", selectedrow);
					if (rowIndex > -1) {
						datagrid.edatagrid('editRow',rowIndex);
					} else {
						show('Pesan','<?php echo getcatalog('chooseone')?>');
					}
					break;
				case 122: //F11 - PDF (opera)
					if (isOpera == true) {
						e.preventDefault();
						var elem = document.getElementById("pdf-"+id);
						if (elem != null) {
							elem.click();
						}
					}
					break;
			}
		} else {
			switch(e.which) {
				case 112: //F1 - About
					e.preventDefault();
					$('#aboutwindow').window('open');
					break;
				case 113: //F2 - New
					e.preventDefault();
					var elem = document.getElementById("add-"+id);
					if (elem != null) {
						elem.click();
					}
					break;
				case 114: //F3 - Edit (chrome)
					if (isChrome == true) {
						e.preventDefault();
						var elem = document.getElementById("edit-"+id);
						if (elem != null) {
							elem.click();
						} else {
							var selectedrow = $("#dg-"+id).datagrid("getSelected");
							var rowIndex = $("#dg-"+id).datagrid("getRowIndex", selectedrow);
							if (rowIndex > -1) {
								$('#dg-'+id).edatagrid('editRow',rowIndex);
							} else {
								show('Pesan','<?php echo getcatalog('chooseone')?>');
							}
						}
					}
					break;
				case 115: //F4 - Search find focus 
					e.preventDefault();
					var elem = document.getElementById("search-"+id);
					if (elem != null) {
						$('#search-'+id).textbox('textbox').focus();
					}
					break;
				case 116://F5: Refresh Page
					break;
				case 117: //F6 - Save
					e.preventDefault();
					var elem = document.getElementById("save-"+id);
					if (elem != null) {
						elem.click();
					}
					break;
				case 118: //F7 - Cancel
					e.preventDefault();
					var elem = document.getElementById("cancel-"+id);
					if (elem != null) {
						elem.click();
					}
					break;
				case 119: //F8 - Purge
					e.preventDefault();
					var elem = document.getElementById("purge-"+id);
					if (elem != null) {
						elem.click();
					}
					break;
				case 120: //F9 - Approve
					e.preventDefault();
					var elem = document.getElementById("approve-"+id);
					if (elem != null) {
						elem.click();
					}
					break;
				case 121: //F10 - Reject
					e.preventDefault();
					var elem = document.getElementById("reject-"+id);
					if (elem != null) {
						elem.click();
					}
					break;
				case 122: //F11 - PDF (chrome)
					if (isChrome == true) {
						e.preventDefault();
						var elem = document.getElementById("pdf-"+id);
						if (elem != null) {
							elem.click();
						}
					}
					break;
				case 123: //F12 - XLS 
					e.preventDefault();
					var elem = document.getElementById("xls-"+id);
					if (elem != null) {
						elem.click();
					}
					break;
			}
		}
	});
	$.fn.numberbox.defaults.parser = function(s) {
		s = s + '';
		var opts = $(this).numberbox('options');
		if (parseFloat(s) != s) {
			if (opts.groupSeparator) s = s.replace(new RegExp('\\'+opts.groupSeparator,'g'), '');
			if (opts.decimalSeparator) s = s.replace(new RegExp('\\'+opts.decimalSeparator,'g'), '.');
			if (opts.prefix) s = s.replace(new RegExp('\\'+$.trim(opts.prefix),'g'), '');
			if (opts.suffix) s = s.replace(new RegExp('\\'+$.trim(opts.suffix),'g'), '');
			s = s.replace(/\s/g,'');
		}
		var val = parseFloat(s).toFixed(opts.precision);
		if (isNaN(val)) {
			val = '';
		} else if (typeof(opts.min) == 'number' && val < opts.min) {
			val = opts.min.toFixed(opts.precision);
		} else if (typeof(opts.max) == 'number' && val > opts.max) {
			val = opts.max.toFixed(opts.precision);
		}
		return val;
	};
});
function opentab($id,$url,$label,$icon) {
	var count = $('#regtab .panel').length;
	if ($('#regtab').tabs('exists', $label)){
		$('#regtab').tabs('select', $label);
	} else {
	if (count > 13) {
		alert('maximum allowed tabs exceed, close one tab first');
	} 
	else {
		$('#regtab').tabs('add',{
			id:$id,
			title:$label,
			href:$url,
			iconCls:$icon,
			closable:true,
		});
		}
	}
}
function minirefresh() {
	var tab = $('#regtab').tabs('getSelected');  // get selected panel
	tab.panel('refresh');
}
function isJson(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}
function show($title,$msg,$type='info') {
	var pesan = '';var objparse = null;
	if (isJson($msg) === true) {
		objparse = JSON.parse($msg);
		if (obj.isError == true) {
			$type = 'error';
			pesan = objparse.msg;
		} else 
		if (obj.isError == false) {
			$type = 'info';
			pesan = objparse.msg;
		}			
		if (obj.success == false) {
			$type = 'error';
			pesan = objparse.message;
		} else {
			$type = 'info';
			pesan = objparse.message;
		}
	} else {
		pesan = $msg;
	}
	if (pesan != 'Simpan Data Berhasil') {
		if (pesan == 'Data Telah Tersimpan') {
			$type = 'info';
		} else {
			$type = 'error';
		}
	}
	$.messager.show({
		title:$title,
		msg:'<div class="messager-icon messager-'+$type+'"></div><div>'+pesan+'</div>',
		showType:'fade',
		timeOut:5000,
		width:'350px',
		style:{
			right:'',
			top:'',
			bottom:-document.body.scrollTop-document.documentElement.scrollTop
		}
	})
}
function removePanel(){
	var tab = $('#regtab').tabs('getSelected');
	if (tab){
		var index = $('#regtab').tabs('getTabIndex', tab);
		if (index > 0) {
			$('#regtab').tabs('close', index);
		}
	}
}
function appendit(){
	var t = $('#tt');
	var node = t.tree('getSelected');
	t.tree('append', {
		parent: (node?node.target:null),
		data: [{
			text: 'new item1'
		},{
			text: 'new item2'
		}]
	});
}
function removeit(){
	var node = $('#tt').tree('getSelected');
	$('#tt').tree('remove', node.target);
}
function clearit(){
	var node = $('#tt').tree('getSelected');
	$('#tt').tree('collapse',node.target);
}
</script>