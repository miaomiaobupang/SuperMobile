		function hauto(){
			var heig = parseInt($('.sidebar-toggle').css('height')) + parseInt($('#left').css('height')) + 90;
			$('#navigation').css('height',heig);
		}
		
		var h=$(window).height();
		$('#right').css('minHeight',h);
		// 侧边栏
		setTimeout(function(){
			hauto();
		},1000);
		function listFunc(id){
			$('body').on('click',''+id+' .lists',function(){
				var show=$(this).data('show');
				if(show==1){
					// 展开
					$('.childrenList').stop().slideUp();
					$('.childrenListLi_list').stop().slideUp();
					$('.add_many').html('+');
					$(this).find('.add_many').html('-');
					$('.one .lists').data('show','1');
					$('.childrenList .li').data('show','1');
					$(this).data('show','2');
					$(this).next('.childrenList').stop().slideDown();
					setTimeout(function(){
						hauto();
					},1000);
				}else{
					// 收起
					$('.childrenList').stop().slideUp();
					$('.childrenListLi_list').stop().slideUp();
					$(this).find('.add_many').html('+');
					$('.one .lists').data('show','1');
					$(this).children('.childrenList .li').find('i').removeClass('icon-caret-down').addClass('icon-caret-right');
					$(this).data('show','1');
					setTimeout(function(){
						hauto();
					},1000);
				}
				
			});
			// 点击二级
			$('body').on('click',''+id+' .childrenList .li',function(){
				var showTwo=$(this).data('show');
				if(showTwo==1){
					// 展开
					$('.childrenListLi_list').stop().slideUp();
					$(''+id+'.one i').removeClass('icon-caret-down').addClass('icon-caret-right');
					$(this).find('i').addClass('icon-caret-down').removeClass('icon-caret-right');
					$('.childrenList .li').data('show','1');
					$(this).data('show','2');
					$(this).next('.childrenListLi_list').stop().slideDown();
					setTimeout(function(){
						hauto();
					},1000);
				}else{
					// 收起
					$('.childrenListLi_list').stop().slideUp();
					$('.childrenList .li').data('show','1');
					$(this).find('i').removeClass('icon-caret-down').addClass('icon-caret-right');
					$(this).data('show','1');
					setTimeout(function(){
						hauto();
					},1000);
				}
				
				
			});
		}
		listFunc('#left');
		
		// 点击之后经过
		$("#left .lists").click(function(){
		    $('#left .lists').css('background','transparent');
		    $(this).css({'background':'-webkit-linear-gradient(left, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0) 100%)',
	    'background':'linear-gradient(to right, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0) 100%)'});
		});
		$("#left .childrenList .li").click(function(){
		    $('#left .childrenList .li').css('color','#fff');
		    //$(this).css('color','#72BBE2');
		});
		$("#left .childrenListLi_list li").click(function(){
		    $('#left .childrenListLi_list li').css('color','#fff');
		    //$(this).css('color','#8336D6');
		});
		
		//通过访问路径，自动展开当前菜单
		
		//选择a标签url属性和当前url相同的a标签
		var url = $('a[id="'+menuUrl+'"]');
		$('a[id="'+menuUrl+'"] span').css("color","#ffdf77");
		//alert(menuUrl)
		slideparent(url);
		//展开该菜单所有父级菜单
		function slideparent(menu){
			//alert('ssss');
			var parentUl = menu.parent('.sss');
			if(parentUl.length == 0){
				return;
			}
			parentUl.stop().slideDown();
			parentUl.prev('li').data('show','2');
			slideparent(parentUl);
		}