// +----------------------------------------------------------------------
// | 分页组件
// +----------------------------------------------------------------------
// | 一款基于Jquery直接调用Ajax的分页组件
// +----------------------------------------------------------------------
// | 参数：	pagingFid			分页ID
// | 		pagingShowSum		分页数据总条数
// | 		pagingShowNum		分页每页数据条数
// | 		ajaxAjax			是否启用Ajax				true/false
// | 		ajaxUrl				Ajax接口请求地址							Ajax禁用时，该参数可忽略
// | 		ajaxParameter		Ajax接口参数拼接字符串						Ajax禁用时，该参数可忽略
// | 		ajaxAsync			Ajax是否同步（反之异步）	true/false		Ajax禁用时，该参数可忽略
// | 		ajaxShowDiv			Ajax内容展示区域DIV							Ajax禁用时，该参数可忽略
// | 		ajaxFunction		Ajax成功回调函数							Ajax禁用时，该参数可忽略
// +----------------------------------------------------------------------
// | 依赖: 	1、Jquery框架
// | 		2、Ajax启用时需在调用页面写成功回调函数（Ajax禁用时，可忽略）
// | 			function successData(data,showDiv){
// | 				//data		Ajax返回的Json数据包
// | 				//showDiv	Ajax内容展示区域DIV
// | 			}
// +----------------------------------------------------------------------
// | Author: 猫鱼mint天空蓝 <2292005044@qq.com>
// +----------------------------------------------------------------------
// |PS：有问题请直接联系我
// +----------------------------------------------------------------------


(function ($) {
	//声明全局变量
	//二维单分页参数数组
	var settings;
	//三维多分页参数数组
	var settingsN=new Array();
	//相关操作对象
	var pagingShowDiv;
    var methods = {
        init: function (options) {
			//获取相关操作对象
			pagingShowDiv=this.attr("id");
			//获取分页参数，并合并默认参数
			settings = $.extend({
				'pagingFid': 0,
				'pagingShowDiv': pagingShowDiv,
				'pagingShowSum':0,
				'pagingShowNum':0,
				'ajaxAjax': false,
				'ajaxUrl': 'http://www.baidu.com',
				'ajaxParameter': '',
				'ajaxAsync': true,
				'ajaxShowDiv': '',
				'ajaxFunction': function(data,showDiv){},
				//请忽略
				'titleTotal': 0,
				'titleEach': 0,
				'titlePage': 0,
				'yushu': 0,
				'page_n': 0,
			}, options);
			//声明三维多分页参数分组
			settingsN[settings.pagingFid]=new Array();
			settingsN[settings.pagingFid]=settings;
			
			//执行分页初始化
			// 先清空
			$('#'+pagingShowDiv+'').html('');
			$('#'+pagingShowDiv+'').addClass("pagingDiv-"+settings.pagingFid);
			$(settings.ajaxShowDiv).addClass("ajaxDiv-"+settings.pagingFid);
			// pagingShowDiv:名称，all:总共几条，num:每页几条
			//914 分页
			var text='<div class="title" style="display:none">为您找到<span>'+settings.pagingShowSum+'</span>个结果 共<span class="allpages"></span>页</div>'+
					'<div class="paginationf">'+
						'<div class="paginations" style="float: left;">'+
							'<li class="page_left"  data-fid="'+settings.pagingFid+'"><i class="icon-angle-left"></i></li>'+
							'<div class="page" data-n="1"></div>'+
							'<li class="page_right" data-fid="'+settings.pagingFid+'"><i class="icon-angle-right"></i></li>'+
							'<div style="clear:both"></div>'+
						'</div>'+ 
						'<div style="float:left;color:#999;margin-top:2px">共<span style="color:#999">'+Math.ceil(settings.pagingShowSum/settings.pagingShowNum)+'</span>页</div>'+
						'<div class="jump">'+
							'<span>跳转到：</span>'+
							'<input type="text" value="" class="jumpInput" data-fid="'+settings.pagingFid+'">'+
							'<button  data-fid="'+settings.pagingFid+'">GO</button>'+
						'</div>'+
						'<div style="clear:both"></div>'+
					'</div>'

			$('#'+pagingShowDiv+'').html(text);
			settings.titleTotal = $('#'+pagingShowDiv+' .title span').html();
			// 页数
			settings.titleEach = Math.ceil(settings.titleTotal/settings.pagingShowNum);
			$('#'+pagingShowDiv+' .allpages').html(settings.titleEach);
			// 分页数
			settings.titlePage = Math.ceil(settings.titleEach/10);
			// 分页数取余
			settings.yushu=settings.titleEach%10;
			if(settings.yushu==0){
				settings.yushu=10
			}
			settings.page_n=$('#'+pagingShowDiv+' .page').data('n');

			if(settings.titlePage>1){
				$('#'+pagingShowDiv+' .paginations .page').html('');
				for(var i=0;i<10;i++){
					var ii=i+1;
					$('#'+pagingShowDiv+' .paginations .page').append("<li data-num="+ii+"  data-fid="+settings.pagingFid+">"+ii+"</li>");
					
				}
				$('#'+pagingShowDiv+' .paginations .page').append('<div style="clear:both"></div>');
			}else if(settings.titlePage==1){
				$('#'+pagingShowDiv+' .paginations .page').html('');
				$('#'+pagingShowDiv+' .page_left,#'+pagingShowDiv+' .page_right,.jump').css('display','none');
				for(var i=0;i<settings.titleEach;i++){
					var ii=i+1;
					$('#'+pagingShowDiv+' .paginations .page').append("<li data-num="+ii+"  data-fid="+settings.pagingFid+">"+ii+"</li>");
				}
			}
			if(settings.titleTotal<=settings.pagingShowNum){
				
				$('#'+pagingShowDiv+' .paginationf').css('display','none');
			}else{
				
				$('#'+pagingShowDiv+' .paginationf').css('display','block');
			}
			//加载遮罩层
			if(!$("#main_zz_state").val()){
				$('body').append("<div class='main_zz' style='width:100%;height:100%;background:transparent;position:fixed;top:0;left:0;z-index:100;display:none;'><div class='main_zz_img' style='width:200px;height:150px;border-radius:4px;background:rgba(0,0,0,0.4);position:absolute;margin:auto;top:0;left:0;right:0;bottom:0;'><img src='http://www.fairso.com/Public/Home/images/5-121204193R0-50.gif' alt='' style='width:60px;position:absolute;display:block;margin:auto;top:0;left:0;right:0;bottom:0;'></div><input type='hidden' id='main_zz_state' value='1'></div>");
			}
			//执行内容请求初始化
			this.paging("ajaxstarSwitch",1);
			//绑定相当事件
			//绑定分页页码点击事件
			$('body').on('click','#'+pagingShowDiv+' .page li',function(){
				var padingFid=$(this).data('fid');
				settings=settingsN[padingFid];
				var pagingShowDivN='.pagingDiv-'+padingFid;
				$(pagingShowDivN+' .jump input').val('');
				$(pagingShowDivN+' .paginations li').css({'background':'#fff','border':'1px solid #ddd','color':'#999'});
				$(this).css({'background':'transparent','border':'1px solid transparent','color':'#00c18d'});
				$('#'+settings.pagingShowDiv).paging("ajaxstarSwitch",$(this).html());
			});
			// 绑定分页向右页码点击事件
			$('body').on('click','#'+pagingShowDiv+' .page_right',function(){
				var padingFid=$(this).data('fid');
				settings=settingsN[padingFid];
				var pagingShowDivN='.pagingDiv-'+padingFid;
				$(pagingShowDivN+' .jump input').val('');
				$(pagingShowDivN+' .paginations div li').css({'background':'#fff','border':'1px solid #ddd','color':'#999'})
				$(pagingShowDivN+' .paginations div li').eq(0).css({'background':'transparent','border':'1px solid transparent','color':'#00c18d'});
				if(settings.page_n+1==settings.titlePage){
					for(var i=0;i<settings.yushu+1;i++){
						var ii=i+1;
						var nums=ii+(settings.page_n*10);
						$(pagingShowDivN+' .paginations div li').eq(ii-1).html(nums);
						$(pagingShowDivN+' .paginations div li').eq(settings.yushu-1).nextAll().css('display','none');
					}
				}else{
					for(var i=0;i<10;i++){
						var ii=i+1;
						var nums=ii+(settings.page_n*10);
						$(pagingShowDivN+' .paginations div li').eq(i).html(nums);
					}
				}
				settings.page_n=settings.page_n+1;
				if(settings.page_n<=1){
					$(pagingShowDivN+' .page_left').css('visibility','hidden');
				}else{
					$(pagingShowDivN+' .page_left').css('visibility','visible');
				}
				if(settings.titlePage==settings.page_n){
					$(pagingShowDivN+' .page_right').css('visibility','hidden');
				}else{
					$(pagingShowDivN+' .page_right').css('visibility','visible');
				}
				$('#'+settings.pagingShowDiv).paging("ajaxstarSwitch",$(pagingShowDivN+' .paginations div li').eq(0).html());
			});
			// 绑定分页向左页码点击事件
			$('body').on('click','#'+pagingShowDiv+' .page_left',function(){
				var padingFid=$(this).data('fid');
				settings=settingsN[padingFid];
				var pagingShowDivN='.pagingDiv-'+padingFid;
				$(pagingShowDivN+' .jump input').val('');
				$(pagingShowDivN+' .paginations div li').css({'background':'#fff','border':'1px solid #ddd','color':'#999'})
				$(pagingShowDivN+' .paginations div li').eq(0).css({'background':'transparent','border':'1px solid transparent','color':'#00c18d'});
				settings.page_n=settings.page_n-1;
				for(var i=0;i<10;i++){
					var ii=i+1;
					var nums=ii+(settings.page_n-1)*10;
					$(pagingShowDivN+' .paginations div li').eq(ii-1).html(nums);
					$(pagingShowDivN+' .paginations div li').css('display','block');
				}
				if(settings.page_n<=1){
					$(pagingShowDivN+' .page_left').css('visibility','hidden');
				}else{
					$(pagingShowDivN+' .page_left').css('visibility','visible');
				}
				if(settings.titlePage==settings.page_n){
					$(pagingShowDivN+' .page_right').css('visibility','hidden');
				}else{
					$(pagingShowDivN+' .page_right').css('visibility','visible');
				}
				$('#'+settings.pagingShowDiv).paging("ajaxstarSwitch",$(pagingShowDivN+' .paginations div li').eq(0).html());
			});
			// 绑定分页跳转回车事件
			$('#'+pagingShowDiv+' .jump input').keyup(function(e){
				this.value=this.value.replace(/\D/g,'');
				// 点击回车键，搜索		
				if(e.keyCode==13){
					var padingFid=$(this).data('fid');
					settings=settingsN[padingFid];
					$('#'+settings.pagingShowDiv).paging("jumpXn",padingFid);
				}
			});
			// 绑定分页跳转GO按钮点击事件
			$('body').on('click','#'+pagingShowDiv+' .jump button',function(){
				var padingFid=$(this).data('fid');
				settings=settingsN[padingFid];
				$('#'+settings.pagingShowDiv).paging("jumpXn",padingFid);
			});
			//请忽略
            return this.each(function () {

                var $this = $(this),
                    data = $this.data('paging'),
                    paging = $('<div />', {
                        text: $this.attr('title')
                    });

                // If the plugin hasn't been initialized yet
                if (!data) {

                    /*
                     Do more setup stuff here
                     */

                    $(this).data('paging', {
                        target: $this,
                        paging: paging
                    });

                }
            });
        },
		/* 
		*名称：分页插件之跳转方法
		*参数	padingFid	分页ID
		*/
		jumpXn:function (padingFid) {
			settings=settingsN[padingFid];
			var pagingShowDivN='.pagingDiv-'+padingFid;
			var numbers=parseInt($(pagingShowDivN+' .jump input').val());
			var numbers_yu=numbers%10;
			// 空
			if($(pagingShowDivN+' .jump input').val()==''){
				return false
			}
			// 大于最大
			if(numbers>settings.titleEach){
				$(pagingShowDivN+' .jump input').val(settings.titleEach);
				// 跳
				for(var i=0;i<settings.yushu+1;i++){
					var ii=i+1;
					var page_big=settings.titlePage-1;
					var nums=ii+(page_big*10);
					$(pagingShowDivN+' .paginations div li').eq(ii-1).html(nums);
					$(pagingShowDivN+' .paginations div li').eq(settings.yushu-1).nextAll().css('display','none');
				}
				$(pagingShowDivN+' .paginations div li').css({'background':'#fff','border':'1px solid #ddd','color':'#999'})
				$(pagingShowDivN+' .paginations div li').eq(settings.yushu-1).css({'background':'transparent','border':'1px solid transparent','color':'#00c18d'});
				$(pagingShowDivN+' .paginations div li').removeClass('ss');
				$(pagingShowDivN+' .paginations div li').eq(settings.yushu-1).addClass('ss');
				settings.page_n=settings.titlePage;
				$(pagingShowDivN+' .page_right').css('visibility','hidden');
				$(pagingShowDivN+' .page_left').css('visibility','visible');
			}else if(numbers<=0){
				$(pagingShowDivN+' .jump input').val(1);
				// 跳
				$(pagingShowDivN+' .paginations div').html('');
				for(var i=0;i<10;i++){
					var ii=i+1;
					$(pagingShowDivN+' .paginations .page').append("<li data-num="+ii+" data-fid="+settings.pagingFid+">"+ii+"</li>");
				}
				settings.page_n=1;
				$(pagingShowDivN+' .paginations .page').append('<div style="clear:both"></div>');
				$(pagingShowDivN+' .page_left').css('visibility','hidden');
				$(pagingShowDivN+' .page_right').css('visibility','visible');
				$(pagingShowDivN+' .paginations div li').removeClass('ss');
				$(pagingShowDivN+' .paginations div li').eq(0).addClass('ss');
			}else{
				$(pagingShowDivN+' .paginations div').html('');
				var page_num=Math.ceil(numbers/10);
				var page_yu=numbers%10;
				for(var i=0;i<10;i++){
					var ii=i+1;
					var nums=ii+((page_num-1)*10);
					$(pagingShowDivN+' .paginations .page').append("<li data-num="+ii+" data-fid="+settings.pagingFid+">"+nums+"</li>");
				}
				$(pagingShowDivN+' .paginations div li').css({'background':'#fff','border':'1px solid #ddd','color':'#999'})
				$(pagingShowDivN+' .paginations div li').eq(page_yu-1).css({'background':'transparent','border':'1px solid transparent','color':'#00c18d'});
				$(pagingShowDivN+' .paginations div li').removeClass('ss');
				$(pagingShowDivN+' .paginations div li').eq(page_yu-1).addClass('ss');
				settings.page_n=page_num;
				if(numbers<=10){
					$(pagingShowDivN+' .page_left').css('visibility','hidden');
					$(pagingShowDivN+' .page_right').css('visibility','visible');
				}else if(settings.titleEach-numbers>=0&&settings.titleEach-numbers<settings.yushu){
					$(pagingShowDivN+' .page_left').css('visibility','visible');
					$(pagingShowDivN+' .page_right').css('visibility','hidden');
					for(var i=0;i<settings.yushu+1;i++){
						var ii=i+1;
						var nums=ii+((page_num-1)*10);
						$(pagingShowDivN+' .paginations div li').eq(ii-1).html(nums);
						$(pagingShowDivN+' .paginations div li').eq(settings.yushu-1).nextAll().css('display','none');
						$(pagingShowDivN+' .paginations div li').css({'background':'#fff','border':'1px solid #ddd','color':'#999'})
						$(pagingShowDivN+' .paginations div li').eq(page_yu-1).css({'background':'transparent','border':'1px solid transparent','color':'#00c18d'});
					}
				}else{
					$(pagingShowDivN+' .page_left').css('visibility','visible');
					$(pagingShowDivN+' .page_right').css('visibility','visible');
				}
				if(page_yu==settings.yushu&&page_num==settings.titlePage){
					for(var i=0;i<settings.yushu+1;i++){
						var ii=i+1;
						var nums=ii+((page_num-1)*10);
						$(pagingShowDivN+' .paginations div li').eq(ii-1).html(nums);
						$(pagingShowDivN+' .paginations div li').eq(settings.yushu-1).nextAll().css('display','none');
						$(pagingShowDivN+' .paginations div li').css({'background':'#fff','border':'1px solid #ddd','color':'#999'})
						$(pagingShowDivN+' .paginations div li').eq(page_yu-1).css({'background':'transparent','border':'1px solid transparent','color':'#00c18d'});
					}
					
					settings.page_n=page_num;
					$(pagingShowDivN+' .page_left').css('visibility','visible');
					$(pagingShowDivN+' .page_right').css('visibility','hidden');
				}
			}
			//执行ajax
			$('#'+settings.pagingShowDiv).paging("ajaxstarSwitch",$(pagingShowDivN+' .jump input').val());
        },
		/* 
		*名称：分页插件之Ajax执行方法
		*参数	无
		*/
		ajaxstar: function (pagenum) {
			$('.main_zz').show();
			$.ajax({
			   type: "POST",
			   url: settings.ajaxUrl,
			   data: settings.ajaxParameter+pagenum,
			   async:settings.ajaxAsync,
			   success: function(data){successData(data,settings.ajaxShowDiv);}
			});
		},
		/* 
		*名称：分页插件之Ajax开关方法
		*参数	无
		*/
		ajaxstarSwitch: function (pagenum) {
			if(settings.ajaxAjax){
				this.paging("ajaxstar",pagenum);
			}
		},
		//请忽略
        destroy: function () {

            return this.each(function () {

                var $this = $(this),
                    data = $this.data('paging');
                // Namespacing FTW
                $(window).unbind('.paging');
                data.paging.remove();
                $this.removeData('paging');

            })

        }
    };
	//分页插件执行函数
    $.fn.paging = function (method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.paging');
        }

    };

})(jQuery);