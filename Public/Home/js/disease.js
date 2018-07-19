$(function(){
	/*右下角滚动标题*/
	// mainRight7到顶部距离
	var topmainRight7=$('.mainRight7').offset().top;
	// mainRight6到顶部距离
	var topmainRight6=$('.mainRight6').offset().top;
	// footer到顶部的距离
	var topFooter=$('.footer').offset().top;
	// main距离左侧距离
	var mainLeft=($(window).width()-1240)/2;
	// 鼠标滚动到
	$(window).scroll(function(){
		var windowTop=$(window).scrollTop();
		if(windowTop>topmainRight6&&topFooter-windowTop>857){
			$('.mainRight7').css({'display':'block','position':'fixed','bottom':'150px','left':mainLeft+850+'px'});
		}else if(topFooter-windowTop<857){
			$('.mainRight7').css({'display':'block','position':'absolute','bottom':'150px','left':'850px'});
		}else{
			$('.mainRight7').hide();
		}
		// 滚轮滑动时，小图标滚动
		$('.titleHs').each(function(){
			var titleHsTop=$(this).offset().top;
			var thisId=$(this).attr('id');
			if(windowTop>titleHsTop-10){
				$('.mainRight7 a').each(function(){
					var hId=$(this).attr('class');
					if(hId==thisId){
						var h=$(this).find('li').data('h');
						$('.mainRight7Img').stop().animate({'top':44+(h-1)*29+'px'},100);
					}
				});
			}
		});
		
	});
	// 点击mainRight7里面的标题，小图标滚动
	$('body').on('click','.mainRight7 li',function(){
		var h=$(this).data('h');
		$('.mainRight7Img').animate({'top':44+(h-1)*29+'px'});
	});
	/*右下角滚动标题**结束**********/

	// 胃癌治疗药物,切换图片
	$('body').on('click','.medicNameLi',function(){
		var n=$(this).data('n');
		$('.medicPicImg').hide();
		$('.medicPicImg_'+n).show();
	});
	
});