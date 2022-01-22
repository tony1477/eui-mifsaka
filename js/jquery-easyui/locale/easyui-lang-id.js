if ($.fn.pagination){
	$.fn.pagination.defaults.beforePageText = 'Hal';
	$.fn.pagination.defaults.afterPageText = 'dari {pages}';
	$.fn.pagination.defaults.displayMsg = 'Menampilkan data {from} ke {to} dari {total} items';
}
if ($.fn.datagrid){
	$.fn.datagrid.defaults.loadMsg = 'Mohon tunggu sebentar ...';
}
if ($.fn.treegrid && $.fn.datagrid){
	$.fn.treegrid.defaults.loadMsg = $.fn.datagrid.defaults.loadMsg;
}
if ($.messager){
	$.messager.defaults.ok = 'Ok';
	$.messager.defaults.cancel = 'Batal';
}
$.map(['validatebox','textbox','filebox','searchbox',
		'combo','combobox','combogrid','combotree',
		'datebox','datetimebox','numberbox',
		'spinner','numberspinner','timespinner','datetimespinner'], function(plugin){
	if ($.fn[plugin]){
		$.fn[plugin].defaults.missingMessage = 'Field ini wajib diisi.';
	}
});
if ($.fn.validatebox){
	$.fn.validatebox.defaults.rules.email.message = 'Silahkan isi alamat email yang benar.';
	$.fn.validatebox.defaults.rules.url.message = 'Silahkan isi alamat web yang benar.';
	$.fn.validatebox.defaults.rules.length.message = 'Silahkan isi nilai antara {0} dan {1}.';
	$.fn.validatebox.defaults.rules.remote.message = 'Silahkan perbaiki nilai field ini.';
}
if ($.fn.calendar){
	$.fn.calendar.defaults.weeks = ['S','M','T','W','T','F','S'];
	$.fn.calendar.defaults.months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
}
if ($.fn.datebox){
	$.fn.datebox.defaults.currentText = 'Hari ini';
	$.fn.datebox.defaults.closeText = 'Tutup';
	$.fn.datebox.defaults.okText = 'Ok';
}
if ($.fn.datetimebox && $.fn.datebox){
	$.extend($.fn.datetimebox.defaults,{
		currentText: $.fn.datebox.defaults.currentText,
		closeText: $.fn.datebox.defaults.closeText,
		okText: $.fn.datebox.defaults.okText
	});
}
