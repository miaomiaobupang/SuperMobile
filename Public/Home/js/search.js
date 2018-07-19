$(function(){
	$('.listOne').hover(function(){
		var a=$(this).data('a');
		var top=$(this).offset().top-216-25;
		$('.listOne').find('img').css('display','none');
		$('.listOne_div').css('display','none');
		$(this).find('img').css('display','block');
		$('.listOne_div_'+a).css({'display':'block','top':top+40});
		$('.listOne').css('color','#616161');
		$(this).css('color','#00c18d');
	},function(){
		$('.listOne').css('color','#616161');
		$('.listOne_div').css('display','none');
		$('.listOne').find('img').css('display','none');
	});

	$('.listOne_div').hover(function(){
		$(this).css({'display':'block','top':top+60})
		var a = $(this).data('a');
		$('span[data-a='+a+']').find('img').css('display','block');
		$('.listOne[data-a='+a+']').css('color','#00c18d');
	},function(){
		$('.listOne_div').css('display','none');
		$('.listOne').find('img').css('display','none');
		$('.listOne').css('color','#616161');
	});

	// 更多
	var searchListBoxH=$('.searchListBox').css('height');
	$('body').on('click','.searchListBoxR_three .more',function(){
		var click=$(this).data('click');
		if(click==1){
			$('.searchListBoxR_three_China,.searchListBoxR_three_Other,.searchListBoxR,.searchListBox').css({'height':'auto','overflow':'auto'});
			$(this).html('收起 <i class="icon-angle-up"></i>');
			$(this).data('click','2');
		}else{
			$('.searchListBoxR_three_China,.searchListBoxR_three_Other').css({'height':'35px','overflow':'hidden'});
			$('.searchListBoxR,.searchListBox').css({'height':searchListBoxH});
			$(this).html('更多 <i class="icon-angle-down"></i>');
			$(this).data('click','1');
		}
		
	});


	
	// 仅显示主办，折扣...
	//$('body').on('click','.main2_2',function(){
	//	$('.main2_2 i').addClass('icon-check-empty').removeClass('icon-check');
	//	$('.main2_2').css('color','#333');
	//	$(this).find('i').removeClass('icon-check-empty').addClass('icon-check');
	//	$(this).css('color','#00c18d');
	//});

	


});