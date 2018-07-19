// 鼠标移入的改变
$('.Icon').mouseover(function(){
	var nicos = $(this).data('nico');
	var ncolors = $(this).data('ncolor');
	$(this).css("background","rgb("+ncolors+") url('"+IMGURL+"/"+nicos+"') no-repeat scroll center center");
	
});
// 鼠标移出的改变
$('.Icon').mouseout(function(){
	var yicos = $(this).data('yico');
	var ycolors = $(this).data('ycolor');
	$(this).css("background","rgb("+ycolors+") url('"+IMGURL+"/"+yicos+"') no-repeat scroll center center");
	
});

//获取屏幕宽度
$('.leftNavigation').css('left',($(window).width()-1200)/2-40);
$('.leftNavigation').css('top',($(window).height()-$('.leftNavigation').height())/2);