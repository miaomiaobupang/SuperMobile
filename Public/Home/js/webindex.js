// �������ĸı�
$('.Icon').mouseover(function(){
	var nicos = $(this).data('nico');
	var ncolors = $(this).data('ncolor');
	$(this).css("background","rgb("+ncolors+") url('"+IMGURL+"/"+nicos+"') no-repeat scroll center center");
	
});
// ����Ƴ��ĸı�
$('.Icon').mouseout(function(){
	var yicos = $(this).data('yico');
	var ycolors = $(this).data('ycolor');
	$(this).css("background","rgb("+ycolors+") url('"+IMGURL+"/"+yicos+"') no-repeat scroll center center");
	
});

//��ȡ��Ļ���
$('.leftNavigation').css('left',($(window).width()-1200)/2-40);
$('.leftNavigation').css('top',($(window).height()-$('.leftNavigation').height())/2);