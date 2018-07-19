$(function(){
	function sameHeight(){
		
		// main2ListL data-list="1" 对应   main2ListR_1
		// main2ListL data-list="2" 对应   main2ListR_2
		$('.main2ListL').each(function(){
			var list=$(this).data('list');
			var hl=parseInt($(this).css('height'));
			var hr=parseInt($(this).parent('.main2List').find('.main2ListR').css('height'));
			var h=Math.max(hl,hr);
			
			$('.main2ListR_'+list).css('min-height',h);
			$(this).css('min-height',h);
		});
	}
	sameHeight();
	$('.main2 .main2List').last().css('margin-bottom','0');

	// 复杂的js部分（标展光地）
	/*
	greenBorder:默认绿色边框
	data-list="1"：第一大组
	标展，光地：选中class为greenBorder

	data-money="1000"   :对应的费用为1000

	data-type="1"   类型为优惠
	data-type="2"   类型为打折
	data-type="0"   类型为不优惠

	data-save="1000"   直降1000
	data-save="9"    9折优惠


	*/
	// 如果标展第一个有优惠
	$('.main2List').each(function(){
		if($(this).find('.groupTwoBz1').length >0){
			var money=$(this).find('.groupTwoBz1').data('money');
			var type=$(this).find('.groupTwoBz1').data('type');
			var save=$(this).find('.groupTwoBz1').data('save');  
		}else{  
			var money=$(this).find('.groupTwoGd1').data('money');
			var type=$(this).find('.groupTwoGd1').data('type');
			var save=$(this).find('.groupTwoGd1').data('save');
			$(this).find('.groupOneChild_2,.groupTwoChildGd_1').css('display','block');
			$(this).find('.contentOne').eq(0).find('img').css('display','block');
			$(this).find('.contentOne').eq(0).addClass('greenBorder');
		}
		// 费用
		if(type==0){
			$(this).find('.money span').html(money);
			$(this).find('.moneyAgo,.moneySave').hide();
		}else if(type==2){
			$(this).find('.moneyAgo span').html(money);
			money=money-save;
			$(this).find('.money span').html(money);
			$(this).find('.moneySave').html('直降'+save);
			$(this).find('.moneyAgo,.moneySave').show(); 
		}else{
			$(this).find('.moneyAgo span').html(money);
			money=money*save*0.1;
			$(this).find('.money span').html(money);
			$(this).find('.moneySave').html(save+'折优惠');
			$(this).find('.moneyAgo,.moneySave').show();
		}
	});
	// 选择标展/光地
	$('body').on('click','.groupOne',function(){
		var exhib=$(this).data('exhib');
		var _this=$(this).parents('.main2List');
		// 添加边框颜色
		_this.find('.groupOne').removeClass('greenBorder');
		_this.find(this).addClass('greenBorder');
		// 展示点击的内容
		_this.find('.groupOneChild').css('display','none');
		_this.find('.groupOneChild_'+exhib).css('display','block');
		// 默认展示第一个对应的展位
		_this.find('.groupTwoChildBz').css('display','none');
		_this.find('.groupTwoChildGd').css('display','none');
		_this.find('.groupTwo').removeClass('greenBorder');
		if(exhib==1){
			var money=_this.find('.groupTwoBz1').data('money');
			var type=_this.find('.groupTwoBz1').data('type');
			var save=_this.find('.groupTwoBz1').data('save');
			// 标展默认选中第一组
			_this.find('.groupTwoChildBz_1').css('display','block');
			_this.find('.groupTwoBz1').addClass('greenBorder');
			// 费用
			if(type==0){
				_this.find('.money span').html(money);
				_this.find('.moneyAgo,.moneySave').hide();
			}else if(type==2){
				_this.find('.moneyAgo span').html(money);
				money=money-save;
				_this.find('.money span').html(money);
				_this.find('.moneySave').html('直降'+save);
				_this.find('.moneyAgo,.moneySave').show();
			}else{
				_this.find('.moneyAgo span').html(money);
				money=money*save*0.1;
				_this.find('.money span').html(money);
				_this.find('.moneySave').html(save+'折优惠');
				_this.find('.moneyAgo,.moneySave').show();
			}
		}else{
			var money=_this.find('.groupTwoGd1').data('money');
			var type=_this.find('.groupTwoGd1').data('type');
			var save=_this.find('.groupTwoGd1').data('save');
			// 光地默认选中第一组
			_this.find('.groupTwoChildGd_1').css('display','block');
			_this.find('.groupTwoGd1').addClass('greenBorder');
			// 费用
			if(type==0){
				_this.find('.money span').html(money);
				_this.find('.moneyAgo,.moneySave').hide();
			}else if(type==2){
				_this.find('.moneyAgo span').html(money);
				money=money-save;
				_this.find('.money span').html(money);
				_this.find('.moneySave').html('直降'+save);
				_this.find('.moneyAgo,.moneySave').show();
			}else{
				_this.find('.moneyAgo span').html(money);
				money=money*save*0.1;
				_this.find('.money span').html(money);
				_this.find('.moneySave').html(save+'折优惠');
				_this.find('.moneyAgo,.moneySave').show();
			}
			
		}
		sameHeight();		
	});

	// 点击标展分组
	$('body').on('click','.groupTwoBz',function(){
		var _this=$(this).parents('.main2List');
		var bzname=$(this).data('bzname');
		// 选中的添加绿色边框
		_this.find('.groupTwoBz').removeClass('greenBorder');
		$(this).addClass('greenBorder');
		// 展示对应的配置
		_this.find('.groupTwoChildBz').css('display','none');
		_this.find('.groupTwoChildBz_'+bzname).css('display','block');
		// 价格改变
		var money=$(this).data('money');
		var type=$(this).data('type');
		var save=$(this).data('save');
		// 费用
		if(type==0){
			_this.find('.money span').html(money);
			_this.find('.moneyAgo,.moneySave').hide();
		}else if(type==2){
			_this.find('.moneyAgo span').html(money);
			money=money-save;
			_this.find('.money span').html(money);
			_this.find('.moneySave').html('直降'+save);
			_this.find('.moneyAgo,.moneySave').show();
		}else{
			_this.find('.moneyAgo span').html(money);
			money=money*save*0.1;
			_this.find('.money span').html(money);
			_this.find('.moneySave').html(save+'折优惠');
			_this.find('.moneyAgo,.moneySave').show();
		}
		sameHeight();
	});

	// 点击光地分组
	$('body').on('click','.groupTwoGd',function(){
		var _this=$(this).parents('.main2List');
		var gdname=$(this).data('gdname');
		// 选中的添加绿色边框
		_this.find('.groupTwoGd').removeClass('greenBorder');
		_this.find(this).addClass('greenBorder');
		// 展示对应的配置
		_this.find('.groupTwoChildGd').css('display','none');
		_this.find('.groupTwoChildGd_'+gdname).css('display','block');
		// 价格改变
		var money=$(this).data('money');
		var type=$(this).data('type');
		var save=$(this).data('save');
		// 费用
		if(type==0){
			_this.find('.money span').html(money);
			_this.find('.moneyAgo,.moneySave').hide();
		}else if(type==2){
			_this.find('.moneyAgo span').html(money);
			money=money-save;
			_this.find('.money span').html(money);
			_this.find('.moneySave').html('直降'+save);
			_this.find('.moneyAgo,.moneySave').show();
		}else{
			_this.find('.moneyAgo span').html(money);
			money=money*save*0.1;
			_this.find('.money span').html(money);
			_this.find('.moneySave').html(save+'折优惠');
			_this.find('.moneyAgo,.moneySave').show();
		}
		sameHeight();
	});
	
// 展开展位图，搭建图
	$('body').on('click','.main2ListLContentR .green',function(){
		var src=$(this).data('src');
		$('#img').attr('src',src);
		$('.pictures_zz').show();
	});
	// 点击放大展位图
	$('body').on('click','#img',function(){
		var w=$(window).width();
		var h=$(window).height();
		$(this).animate({'width':h,'height':h});
	});
	// 关闭
	$('body').on('click','.closeImg',function(){
		$('.pictures_zz').hide();
		$('#img').css({'width':'300px','height':'300px'});;
	});
	//滚动监听 
	$(window).scroll(function(){
		//为页面添加页面滚动监听事件
		var pageheight = $(document).height();  
		var scrollheight =  $(window).scrollTop(); //滚动条距离顶端值
		var windowheight =  $(window).height() //窗口搞度
		//alert(pageheight)
		//alert(pageheight-500)  
	//	alert(scrollheight)
	//console.log(pageheight-530)
	console.log(scrollheight)
	console.log(pageheight)
		if(scrollheight > pageheight-550-windowheight){
			$('.topImg,.callus').css('position','absolute');
			$('.topImg').css('top',pageheight-650+'px');
			$('.callus').css('top',pageheight-600+'px');
		//	/alert(1)
		}else{ 
			$('.topImg,.callus').css('position','fixed');
			
			$('.topImg').css('top',windowheight-100+'px'); 
			$('.callus').css('top',windowheight-50+'px'); 
		}
		
	})  
	
});
