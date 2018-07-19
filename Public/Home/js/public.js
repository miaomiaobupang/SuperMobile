// placeholder-----------------------------------------------------------------
/*=======用法：
=============HTML引用：
<div class="inputDiv">
	<span>请输入模板名称</span>
	<input type="text" title="请输入模板名称" id="input1" value="">
</div>
或者
<div class="inputDiv">
	<span>请输入邮件主题</span>
	<textarea name="" title="请输入邮件主题" id="input2"></textarea>
</div>

=========js调用
var arrayob = Array('input1','input2');
placeholder(arrayob);

 */
var placeholder = function(ob){
	$('input,textarea').focus(function(){
		$(this).prev('span').css('display','none');
		// $(this).css('border','1px solid none');
	});
	// 判断input是否有值
	function inputPlacehold(id){
		var value=$('#'+id+'').val();
		if(value=='' || value== undefined || value== null){
			$('#'+id+'').prev('span').css('display','block');
			// $('#'+id+'').css('border','1px solid #ddd');
			$('#'+id+'').blur(function(){
				$(this).prev('span').css('display','block');
				// $(this).css('border','1px solid #ddd');
			})
		}else{
			$('#'+id+'').prev('span').css('display','none');
			// $('#'+id+'').css('border','1px solid #ddd');
			$('#'+id+'').blur(function(){
				$(this).prev('span').css('display','none');
				// $(this).css('border','1px solid #ddd');
			})
		}
	}
	// window.onload=function(){
		for( var i  in ob) {
			inputPlacehold(ob[i]);
		}				 
	// }		
	$('body').on('keyup','input,textarea',function(){
		for( var i  in ob) {
			inputPlacehold(ob[i]);
		}	
	});
}

// 遮罩--------------------------------------------------------------------------------------------------
/*
<span class="openMask" data-mask="1">打开遮罩</span>
<div class="maskBox maskBox_1">
	<div class="maskMain">内容</div>
</div>
 */
$('body').on('click','.openMask',function(){
	var mask=$(this).data('mask');
	$('.maskBox').hide();
	$('.maskBox_'+mask).show();
});

// select-------------------------------------------------------------
/*
<div class="select_div" id="select1">
	<div class="select" data-click="1">选择国家</div>
	<ul>
		<li>中国</li>
		<li>美国</li>
		<li>法国</li>
		<li>意大利</li>
		<li>新西兰</li>
	</ul>
</div>
 */
var selectFunction=function(id){
	$('body').on('click','#'+id+' .select',function(event){
		var _this=$(this);
	  // 防止冒泡！！！
	    event.stopPropagation();
	    var click=$(this).data('click');
	    if(click==1){
	     	$('.select_div ul').slideUp();
			$('.select').data('click','1');
			$(this).next('ul').slideDown();
			$(this).data('click','2');
			$(this).parents('.select_div').find('.triangle').removeClass('triangleUp').addClass('triangleDown');
			$('.searchBoxWhite').css('border-radius','6px 6px 6px 0');
	    }else{
	      $(this).next('ul').slideUp();
	      $(this).data('click','1');
	      $(this).parents('.select_div').find('.triangle').removeClass('triangleDown').addClass('triangleUp');
	      setTimeout(function(){
	      	$('.searchBoxWhite').css('border-radius','6px');
	      },300);
	    }
	    // 点击其他地方
	    $(document).click(function(){
	        _this.next('ul').slideUp();
	        _this.data('click','1');
	        _this.parents('.select_div').find('.triangle').removeClass('triangleDown').addClass('triangleUp');
	        setTimeout(function(){
		      	$('.searchBoxWhite').css('border-radius','6px');
		    },300);
	    });
	 });
	 //模拟点击option
	$('body').on('click','#'+id+' li',function(){
		// $(this).parents('.select_div').find('.select').css('border','1px solid #ddd');
		$(this).parents('.select_div').find('.select').data('click','1');
		$(this).parent('ul').slideUp();
		var value=$(this).html();
		$(this).parents('.select_div').find('.select').html(value);
		$(this).parents('.select_div').find('.triangle').removeClass('triangleDown').addClass('triangleUp');
		setTimeout(function(){
	      	$('.searchBoxWhite').css('border-radius','6px');
	    },300);
	}); 
}