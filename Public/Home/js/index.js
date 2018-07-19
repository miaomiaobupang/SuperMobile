$(function(){
	$('body').on('click','.moreKeys',function(){
		var n=$(this).data('n');
		if(n==1){
			$('.keywordBox').css('height','auto');
			$(this).find('span').html('收起');
			$(this).find('img').css('transform','rotate(180deg)');
			$(this).data('n','2');
		}else{
			$('.keywordBox').css('height','77px');
			$(this).find('span').html('更多');
			$(this).find('img').css('transform','rotate(0deg)');
			$(this).data('n','1');
		}
	});
	// 最新公益
	$('.main6DocLeftCon li').hover(function(){
		var n=$(this).data('n');
		$('.main6DocrRight').hide();
		$('.main6DocrRight_'+n).show();
		$('.main6DocLeftCon li').removeClass('main6DocLeftTextGreen');
		$(this).addClass('main6DocLeftTextGreen');
	},function(){
		$('.main6DocLeftCon li').removeClass('main6DocLeftTextGreen');
		$('.main6DocLeftCon li:eq(0)').addClass('main6DocLeftTextGreen');
		$('.main6DocrRight').hide();
		$('.main6DocrRight_1').show();
	});

});