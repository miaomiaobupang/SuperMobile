//年月日日历加载-请先加载Jquery再传入ID位置
function dateYearMonthSelect(id){
	//日期日历选择框设置（不带时分秒）
	$('#'+id).datetimepicker({
		language:  'zh-CN',
		format: 'yyyy-mm-dd',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	}); 
}