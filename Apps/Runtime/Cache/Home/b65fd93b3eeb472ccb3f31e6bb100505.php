<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>超级医生-首页</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9,chrome=1">
	<meta name="keywords" content=""/>
	<meta name="description" content=""/>
	<link rel="stylesheet" href="/Mobile/Public/Home/css/public.css">
	<link rel="stylesheet" href="/Mobile/Public/Home/css/idangerous.swiper.css">
	<link rel="stylesheet" href="/Mobile/Public/Home/css/index.css">
	<script src="/Mobile/Public/Home/js/jquery.min.js"></script>
	<script src="/Mobile/Public/Home/js/idangerous.swiper.min.js"></script>
	<script src="/Mobile/Public/Home/js/index.js"></script>
</head>
<body>
	<div class="box">
<style>
	#header{
		width:100%;
		height:100px;
		background:#fff;
	}
	.headerCont{
		width:1240px;
		height:100px;
		margin:0 auto;
	}
	.logoText{
		font-size:36px;
		color:#009FA8;
		line-height:100px;
	}
	.logoText2{
		font-size:18px;
		margin-left:20px;
		line-height:100px;
	}
	.inputDiv{
		width:412px;
		height:40px;
		margin-left:116px;
		border-radius:2px;
		margin-top:30px;
		margin-right:10px;
	}
	#searchInput{
		width:344px;
		height:40px;
		border:2px solid #009FA8;
		padding:5px 12px;
		font-size:14px;
		border-radius:2px 0 0 2px;
	}
	#searchBtn{
		width:68px;
		height:40px;
		background:#009FA8;
		font-size:16px;
		color:#fff;
		border:none;
		cursor:pointer;
		border-radius:0 2px 2px 0;
	}
	.headerHot{
		color:#999;
		font-size:14px;
		margin-left:10px;
		line-height:100px;
		cursor:pointer;
	}
	#nav{
		width:100%;
		height:60px;
		background:#009FA8;
	}
	.navCont{
		width:1240px;
		height:60px;
		margin:0 auto;
		padding:5px 35px;
	}
	.navList{
		font-size:16px;
		color:#fff;
		line-height:50px;
		margin-right:25px;
		display:inline-block;
		cursor:pointer;
	}
	.navListNow{
		border-bottom:2px solid #fff;
	}
	.login,.register{
		font-size:16px;
		color:#fff;
		line-height:50px;
		cursor:pointer;
	}
	.line{
		font-size:16px;
		color:#fff;
		line-height:50px;
		margin:0 10px;
	}
	.QuestionBut{
		width:60px;
		height:60px;
		line-height:60px;
		background:#009FA8;
		text-align:center;
		border-radius:5px;
		margin-bottom:5px;
	}
	.QuestionBut:hover{
		
	}
	.rightPublicDiv{
		width:60px;
		height:250px;
		position:fixed;
		right:0px;
		top:300px;
	}
	.telephoneView{
		z-index:10;
	}
	.phoneTextStyle{
		width:250px;
		height:60px;
		line-height:60px;
		background:#009FA8;
		color:white;
		font-size:24px;
		font-weight:bold;
		margin-top:-35px;
		z-index:9;
		position:absolute;
		left:60px;
		border-radius:5px;
	}
	.headFixedDivStyle{
		width:100%;
		height:60px;
		background:white;
		margin:0px auto;
		position:fixed;
		top:0px;
		z-index:100;
		display:none;
		box-shadow:0px 0px 5px #009FA8;
	}
	.navLists{
		color:black;
		font-size:16px;
		height:60px;
		line-height:60px;
		margin-right:25px;
		display:inline-block;
		cursor:pointer;
		top:-20px;
	}
	.navTwiceStyle{
		width:auto;
		height:100%;
		float:left;
	}
	.navTwicePhone{
		width:300px;
		height:50px;
		line-height:50px;
		text-align:center;
		float:right;
		border-radius:30px;
		margin-top:5px;	
		background:#009FA8;
	}
	.navTwicePhone img{
		-o-transform:rotate(100deg); 
		transform:rotate(100deg);
		-ms-transform:rotate(100deg); 	/* IE 9 */
		-moz-transform:rotate(100deg); 	/* Firefox */
		-webkit-transform:rotate(100deg); /* Safari 和 Chrome */
		margin-top:8px;
		margin-right:20px;
	}
	.navTwicePhones{
		width:auto;
		float:left;
		height:50px;
		color:white;
	}
	.headerHot{
		font-size:24px;
		color:#009FA8;
		float:left;
		margin-left:10px;
	}
</style>
<div id="header">
	<div class="headerCont">
		<span class="logoText fl">超级医生</span>
		<span class="logoText2 fl">前沿重疾解决方案提供平台</span>
		<div class="inputDiv fl">
			<input type="text" placeholder="请在此输入您想查询的重疾信息" id="searchInput" class="fl">
			<button id="searchBtn" class="fl">搜索</button>
		</div>
		<div class="fl" style="float:right;">
			<div class="headerHot"><img src="/Mobile/Public/Home/images/20180606154942hhhhhh.png" style="margin-top:38px;"></div>
			<div class="headerHot">400-661-1156</div>
		</div>
		<div id="backTop" name="backTop" class="cl"></div>
	</div>
</div>
<div id="nav">
	<div class="navCont">
		<span class="navList navListNow">首页</span>      
		<span class="navList">疾病</span>      
		<span class="navList">互动问答</span>     
		<span class="navList">前沿咨讯</span>      
		<span class="navList">最新临床</span>      
		<span class="navList">超级专家</span>      
		<span class="navList">顶级医院</span>   
		<span class="navList">海外医疗</span>
		<div class="fr">
			<span class="login">登录</span> 
			<span class="line">|</span> 
			<span class="register">注册</span>
		</div>
	</div>
</div>
<div class="rightPublicDiv">
	<a href="http://192.168.1.21/Mobile/Interlocution/questions" target="_blank">
		<div class="QuestionBut">
			<div style="color:white;width:100%;height:20px;"><img src="/Mobile/Public/Home/images/20180511onlineTalk.png" style="width:25px;"></div>
			<div style="color:white;width:100%;height:20px;margin-top:-5px;font-size:14px;font-weight:bold;">问医生</div>
		</div>
	</a>
	<a href="http://192.168.1.21/Mobile/Interlocution/questions" target="_blank">
		<div class="QuestionBut telephoneView">
			<div style="color:white;width:100%;height:20px;"><img src="/Mobile/Public/Home/images/20180606105430phone.png" style="width:25px;"></div>
			<div style="color:white;width:100%;height:20px;margin-top:-5px;font-size:14px;font-weight:bold;">电话</div>
		</div>
	</a>
	<div class="phoneTextStyle">400-661-1156</div>
	<a href="#backTop">
		<div class="QuestionBut">
			<div style="color:white;width:100%;height:20px;"><img src="/Mobile/Public/Home/images/20180606105416top.png" style="width:25px;"></div>
			<div style="color:white;width:100%;height:20px;margin-top:-5px;font-size:14px;font-weight:bold;">回顶部</div>
		</div>
	</a>
</div>
<div class="headFixedDivStyle">
	<div style="width:1245px;height:100%;line-height:100%;margin:0px auto;">
		<div class="navTwiceStyle"><img src="/Mobile/Public/Home/images/20180414chaojiyishenglogo.png" style="margin-top:15px;margin-right:50px;"></div>
		<div class="navTwiceStyle">
			<a href="http://192.168.1.21/Mobile"><span class="navLists" data-type="1">首页</span></a>      
			<a href="http://192.168.1.21/Mobile/Entry"><span class="navLists" data-type="2">疾病</span></a>      
			<a href="http://wenda.superdoctor.cn"><span class="navLists" data-type="3">互动问答</span></a>     
			<a href="http://news.superdoctor.cn"><span class="navLists" data-type="4">前沿咨讯</span></a>  
			<a href="http://yiyuan.superdoctor.cn"><span class="navLists" data-type="5">权威医院</span></a>      
			<a href="http://yisheng.superdoctor.cn"><span class="navLists" data-type="6">超级专家</span></a>      
		</div>
		<div class="navTwiceStyle navTwicePhone">
			<div class="navTwicePhones"><img src="/Mobile/Public/Home/images/20180606105430phone.png" style="margin-top:13px;margin-left:40px;"></div>
			<div class="navTwicePhones" style="font-size:24px;font-weight:bold;">400-661-1156</div>
		</div>
	</div>
</div>	
<script>
	$('.telephoneView').hover(
		function(){  
			$('.phoneTextStyle').animate({left:"-240px"},"slow");  
		} ,  
		function(){  
			$('.phoneTextStyle').animate({left:'+60px'},"slow");  
		}   
	);
	//------------------------固定头部导航开始---------------------------
	$(window).scroll(function(){
		var scrollTo = $(window).scrollTop();
		if(scrollTo >= 160){
			$('.headFixedDivStyle').css({"display":"block"});
		}else if(scrollTo < 160){
			$('.headFixedDivStyle').css({"display":"none"});
		}
	}).trigger('scroll');
	//------------------------固定头部导航结束---------------------------
</script>
	<!-- keyword -->
	<div class="boxShow">
		<div style="width:1240px;margin:0 auto">
			<div class="keywordBox">
				<div class="fl"><span class="keyAll fl">不限</span></div>
				<div class="keyContentB fl">
					<?php if(is_array($data)): foreach($data as $key=>$data): ?><div class="keyContent">
							<span class="keyList fl"><?php echo ($data["name"]); ?></span>
							<span class="fl keyLine">|</span>
							<div class="keyContR fl">
								<?php echo ($data["listHtml"]); ?>
							</div>
							<div class="cl"></div>
						</div><?php endforeach; endif; ?>
				</div>
				<div class="cl"></div>
			</div>
			<div class="moreKeys fr" data-n="1">
				<span class="fl">更多</span>
				<img src="/Mobile/Public/Home/images/20180316cjys3.png" alt="" class="fl">
			</div>
			<div class="cl"></div>
		</div>
	</div>
	<div class="main1Box">
		<div class="mainContent">
			<div class="main1Left fl">
				<div class="main1_title">每日推荐</div>
				<div class="main1Banner swiper-container">
					<div class="swiper-wrapper">
						<div class="swiper-slide">
						  <img src="/Mobile/Public/Home/images/20180316cjys9.png">
						  <div class="bannerText">
							<div class="bannerText1">1最多贡献用户：厚朴方舟海外医疗</div>
							<div class="bannerText2">最多贡献用户：厚朴方舟海外医疗最多贡献用户：厚朴方舟海外医疗最多贡献用户：厚朴方舟海外医疗</div>
						  </div>
						</div>
						<div class="swiper-slide">
						  <img src="/Mobile/Public/Home/images/20180316cjys9.png">
						  <div class="bannerText">
							<div class="bannerText1">2最多贡献用户：厚朴方舟海外医疗</div>
							<div class="bannerText2">最多贡献用户：厚朴方舟海外医疗最多贡献用户：厚朴方舟海外医疗最多贡献用户：厚朴方舟海外医疗</div>
						  </div>
						</div>
						<div class="swiper-slide">
						  <img src="/Mobile/Public/Home/images/20180316cjys9.png">
						  <div class="bannerText">
							<div class="bannerText1">3最多贡献用户：厚朴方舟海外医疗</div>
							<div class="bannerText2">最多贡献用户：厚朴方舟海外医疗最多贡献用户：厚朴方舟海外医疗最多贡献用户：厚朴方舟海外医疗</div>
						  </div>
						</div>
					</div>
					<div class="my-pagination"></div>
				</div>
				<script>
					var mySwiper1 = new Swiper('.main1Banner',{
						loop: true,
						autoplay: 5000,
						pagination:'.my-pagination',
						paginationClickable :true
					});
				</script>
			</div>
			<div class="main1Right fr">
				<div class="main1_title2 fl">hot</div>
				<span class="main1_title3 fl">用户榜单</span>
				<span class="main1Right_more fr">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
				<div class="cl"></div>
				<div class="main1RightList">
					<li>最多贡献用户：厚朴方舟海外医疗</li>
					<li>最新入驻专家：孟娜-北医三院</li>
					<li>做多贡献客户：林蕾</li>
					<li>最新加入用户：肺癌咨询</li>
					<li>推荐专家：铃木建司</li>
					<li>最新入驻机构：厚朴方舟</li>
				</div>
			</div>
		</div>
	</div>
	<div class="main2Box">
		<div class="mainContent">
			<div class="main2_title">
				<span class="main2_title1">全球超级医生</span>
				<span class="main1Right_more">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
			</div>
			<div class="main2Doc">
				<div class="main2One fl">
					<div class="main2DocPic">
						<img src="/Mobile/Public/Home/images/20180316cjys13.png" alt="">
						<div class="main2DocPicText">
							<span class="main2DocName"><span>幕内雅敏</span>教授</span>
						</div>
						<div class="main2DocPicText">
							<li class="main2DocName">IASGO主席</li>
							<li class="main2DocName">日本外科学会会长</li>
							<li class="main2DocName">日本红十字会总医院院长</li>
						</div>
					</div>
					<div class="main2DocNews">
						<div class="main2DocNewsTitle">相关资讯</div>
						<li class="main2DocNewsList">
							<span class="main2DocNewsCircle fl"></span>
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNewsCircle fl"></span>
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNewsCircle fl"></span>
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNewsCircle fl"></span>
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
					</div>
				</div>
				<div class="main2One fl">
					<div class="main2DocPic main2DocPic2">
						<img src="/Mobile/Public/Home/images/20180316cjys13.png" alt="">
						<div class="main2DocPicText">
							<span class="main2DocName"><span>幕内雅敏</span>教授</span>
						</div>
						<div class="main2DocPicText">
							<li class="main2DocName">IASGO主席</li>
							<li class="main2DocName">日本外科学会会长</li>
							<li class="main2DocName">日本红十字会总医院院长</li>
						</div>
					</div>
					<div class="main2DocNews">
						<div class="main2DocNewsTitle">相关资讯</div>
						<li class="main2DocNewsList">
							<span class="main2DocNewsCircle fl"></span>
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNewsCircle fl"></span>
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNewsCircle fl"></span>
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNewsCircle fl"></span>
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
					</div>
				</div>
				<div class="main2Two fr">
					<div class="main2TwoTitle">
						<span class="main2TwoTitle1 fl">专家列表</span>
						<span class="main1Right_more fr">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
					</div>
					<li class="main2TwoList">
						<span class="main2TwoList1 fl">鹤丸昌彦教授-日本消化学会会长</span>
						<span class="main2TwoList2 fr">详情 ></span>
					</li>
					<li class="main2TwoList">
						<span class="main2TwoList1 fl">鹤丸昌彦教授-日本消化学会会长</span>
						<span class="main2TwoList2 fr">详情 ></span>
					</li>
				</div>
				<div class="cl"></div>
			</div>
			<div class="main2Banner swiper-container">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
					  <div class="banner2Text">1协和医院：协和医院举办xx活动</div>
					</div>
					<div class="swiper-slide">
					  <div class="banner2Text">2协和医院：协和医院举办xx活动</div>
					</div>
					<div class="swiper-slide">
					  <div class="banner2Text">3协和医院：协和医院举办xx活动</div>
					</div>
				</div>
				<div class="my-pagination2"></div>
			</div>
			<script>
				var mySwiper2 = new Swiper('.main2Banner',{
					loop: true,
					autoplay: 5000,
					pagination:'.my-pagination2',
					paginationClickable :true
				});
			</script>
		</div>
	</div>
	<div class="main3Box">
		<div class="mainContent">
			<div class="main2_title">
				<span class="main2_title1">世界顶级医院</span>
				<span class="main1Right_more">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
			</div>
			<div class="main2Doc">
				<div class="main3DocPic fl">
					<div class="main3DocPicTop">
						<img src="/Mobile/Public/Home/images/20180316cjys10.png" alt="" class="fl">
						<div class="fl main3DocPicTopList">
							<li class="main3DocPicTopList1">国立癌症研究中心</li>
							<li class="main3DocPicTopList2">排&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：亚洲质子治疗排名第一</li>
							<li>优势科室：放射线治疗科</li>
						</div>
					</div>
					<div class="main3DocNews">
						<div class="main2DocNewsTitle">相关资讯</div>
						<li class="main2DocNewsList">
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
					</div>
				</div>
				<div class="main3DocPic fr">
					<div class="main3DocPicTop main3DocPicTop2">
						<img src="/Mobile/Public/Home/images/20180316cjys10.png" alt="" class="fl">
						<div class="fl main3DocPicTopList">
							<li class="main3DocPicTopList1">国立癌症研究中心</li>
							<li class="main3DocPicTopList2">排&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：亚洲质子治疗排名第一</li>
							<li>优势科室：放射线治疗科</li>
						</div>
					</div>
					<div class="main3DocNews">
						<div class="main2DocNewsTitle">相关资讯</div>
						<li class="main2DocNewsList">
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
						<li class="main2DocNewsList">
							<span class="main2DocNews1 over2 fl">墓内亚敏肝脏外科研讨会在墓内亚敏</span>  
							<span class="main2DocNews2 fr">2017-11-10</span>
						</li>
					</div>
				</div>
				<div class="cl"></div>
			</div>
			<div class="main3Banner swiper-container">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
					  <div class="banner2Text">1协和医院：协和医院举办xx活动</div>
					</div>
					<div class="swiper-slide">
					  <div class="banner2Text">2协和医院：协和医院举办xx活动</div>
					</div>
					<div class="swiper-slide">
					  <div class="banner2Text">3协和医院：协和医院举办xx活动</div>
					</div>
				</div>
				<div class="my-pagination3"></div>
			</div>
			<script>
				var mySwiper3 = new Swiper('.main3Banner',{
					loop: true,
					autoplay: 5000,
					pagination:'.my-pagination3',
					paginationClickable :true
				});
			</script>
		</div>
	</div>
	<div class="main4Box">
		<div class="mainContent">
			<div class="main4Doc">
				<div class="main4Doc1 fl">
					<div class="main1_title2 fl">new</div>
					<span class="main1_title3 fl">最新临床</span>
					<span class="main1Right_more fr">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
					<div class="cl"></div>
					<div class="main4Doc1List">
						<div class="main4Doc1List_li">
							<div class="main4Doc1ListCircle fl"></div>
							美国丹娜法伯非小细胞肺癌受试者招募？
						</div>
						<div class="main4Doc1List_Text">美国丹娜法伯非小细胞肺癌受试者招募美国丹娜法伯非小细胞肺癌受试者招募...</div>
					</div>
					<div class="main4Doc1List">
						<div class="main4Doc1List_li">
							<div class="main4Doc1ListCircle fl"></div>
							美国丹娜法伯非小细胞肺癌受试者招募？
						</div>
						<div class="main4Doc1List_Text">美国丹娜法伯非小细胞肺癌受试者招募美国丹娜法伯非小细胞肺癌受试者招募...</div>
					</div>
					<div class="main4Doc1List">
						<div class="main4Doc1List_li">
							<div class="main4Doc1ListCircle fl"></div>
							美国丹娜法伯非小细胞肺癌受试者招募？
						</div>
						<div class="main4Doc1List_Text">美国丹娜法伯非小细胞肺癌受试者招募美国丹娜法伯非小细胞肺癌受试者招募...</div>
					</div>
				</div>
				<div class="main4Doc1 fl" style="margin:0 20px">
					<div class="main1_title2 fl">hot</div>
					<span class="main1_title3 fl">推荐回答</span>
					<span class="main1Right_more fr">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
					<div class="cl"></div>
					<div class="main4Doc1List">
						<li class="main2TwoList">
							<span class="main2TwoList1 fl">肺癌骨转移后怎么治疗？</span>
							<span class="main2TwoList2 fr">2回答</span>
						</li>
						<li class="main2TwoList">
							<span class="main2TwoList1 fl">肺癌骨转移后怎么治疗？</span>
							<span class="main2TwoList2 fr">2回答</span>
						</li>
					</div>
				</div>
				<div class="main4Doc1 fl">
					<div class="main1_title2 fl">new</div>
					<span class="main1_title3 fl">最新回答</span>
					<span class="main1Right_more fr">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
					<div class="cl"></div>
					<div class="main4Doc1List">
						<li class="main2TwoList">
							<span class="main2TwoList1 fl">肺癌骨转移后怎么治疗？</span>
							<span class="main2TwoList2 fr">2回答</span>
						</li>
						<li class="main2TwoList">
							<span class="main2TwoList1 fl">肺癌骨转移后怎么治疗？</span>
							<span class="main2TwoList2 fr">2回答</span>
						</li>
					</div>
				</div>
				<div class="cl"></div>
			</div>
			<div class="main4Banner swiper-container">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
					  <div class="banner2Text">1协和医院：协和医院举办xx活动</div>
					</div>
					<div class="swiper-slide">
					  <div class="banner2Text">2协和医院：协和医院举办xx活动</div>
					</div>
					<div class="swiper-slide">
					  <div class="banner2Text">3协和医院：协和医院举办xx活动</div>
					</div>
				</div>
				<div class="my-pagination4"></div>
			</div>
			<script>
				var mySwiper4 = new Swiper('.main4Banner',{
					loop: true,
					autoplay: 5000,
					pagination:'.my-pagination4',
					paginationClickable :true
				});
			</script>
			<div class="medicine">
				<div>
					<span class="medicineTitle">药物</span>
					<span class="main1Right_more fr">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
					<div class="cl"></div>
				</div>
				<div class="medicineList" style="width:1260px">
					<div class="medicineList_li fl">
						<img src="/Mobile/Public/Home/images/20180316cjys10.png" alt="">
						<div class="medicineList_liText">阿瓦斯丁</div>
					</div>
					<div class="medicineList_li fl">
						<img src="/Mobile/Public/Home/images/20180316cjys10.png" alt="">
						<div class="medicineList_liText">阿瓦斯丁</div>
					</div>
					<div class="medicineList_li fl">
						<img src="/Mobile/Public/Home/images/20180316cjys10.png" alt="">
						<div class="medicineList_liText">阿瓦斯丁</div>
					</div>
					<div class="medicineList_li fl">
						<img src="/Mobile/Public/Home/images/20180316cjys10.png" alt="">
						<div class="medicineList_liText">阿瓦斯丁</div>
					</div>
					<div class="medicineList_li fl">
						<img src="/Mobile/Public/Home/images/20180316cjys10.png" alt="">
						<div class="medicineList_liText">阿瓦斯丁</div>
					</div>
					<div class="medicineList_li fl">
						<img src="/Mobile/Public/Home/images/20180316cjys10.png" alt="">
						<div class="medicineList_liText">阿瓦斯丁</div>
					</div>
					<div class="cl"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="main5Box">
		<div class="mainContent">
			<div>
				<span class="medicineTitle">医疗前言</span>
			</div>
			<div class="main5Doc">
				<div class="main5DocList fl">
					<img src="/Mobile/Public/Home/images/20180316cjys11.png" alt="" class="fl">
					<div class="main5DocListText fl">
						<li class="main5DocListText1">质子治疗四大突破 肿瘤患者的新希望</li>
						<li class="main5DocListText2">随着时代的进步和医疗水平的提升，“质子治疗”这一概念被越来越多的人所熟知。作为...</li>
					</div>
				</div>
				<div class="main5DocList fl">
					<img src="/Mobile/Public/Home/images/20180316cjys11.png" alt="" class="fl">
					<div class="main5DocListText fl">
						<li class="main5DocListText1">质子治疗四大突破 肿瘤患者的新希望</li>
						<li class="main5DocListText2">随着时代的进步和医疗水平的提升，“质子治疗”这一概念被越来越多的人所熟知。作为...</li>
					</div>
				</div>
				<div class="main5DocList fl">
					<img src="/Mobile/Public/Home/images/20180316cjys11.png" alt="" class="fl">
					<div class="main5DocListText fl">
						<li class="main5DocListText1">质子治疗四大突破 肿瘤患者的新希望</li>
						<li class="main5DocListText2">随着时代的进步和医疗水平的提升，“质子治疗”这一概念被越来越多的人所熟知。作为...</li>
					</div>
				</div>
				<div class="main5DocList fl">
					<img src="/Mobile/Public/Home/images/20180316cjys11.png" alt="" class="fl">
					<div class="main5DocListText fl">
						<li class="main5DocListText1">质子治疗四大突破 肿瘤患者的新希望</li>
						<li class="main5DocListText2">随着时代的进步和医疗水平的提升，“质子治疗”这一概念被越来越多的人所熟知。作为...</li>
					</div>
				</div>
				<div class="main5DocList fl">
					<img src="/Mobile/Public/Home/images/20180316cjys11.png" alt="" class="fl">
					<div class="main5DocListText fl">
						<li class="main5DocListText1">质子治疗四大突破 肿瘤患者的新希望</li>
						<li class="main5DocListText2">随着时代的进步和医疗水平的提升，“质子治疗”这一概念被越来越多的人所熟知。作为...</li>
					</div>
				</div>
				<div class="main5DocList fl">
					<img src="/Mobile/Public/Home/images/20180316cjys11.png" alt="" class="fl">
					<div class="main5DocListText fl">
						<li class="main5DocListText1">质子治疗四大突破 肿瘤患者的新希望</li>
						<li class="main5DocListText2">随着时代的进步和医疗水平的提升，“质子治疗”这一概念被越来越多的人所熟知。作为...</li>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div class="main6Box">
			<div class="mainContent">
				<div style="margin-bottom:30px">
		    		<span class="medicineTitle">最新公益</span>
		    	</div>
		    	<div class="main6Doc">
					<div class="main6DocLeft fl">
						<div class="main6DocLeftCon">
							<div class="main6DocLeftTitle">【厚朴方舟  希望童行】</div>
							<li data-n="1" class="main6DocLeftTextGreen">
								<div class="main6DocLeftCir fl"></div>
								<div class="main6DocLeftText fl">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
							</li>
							<li data-n="2">
								<div class="main6DocLeftCir fl"></div>
								<div class="main6DocLeftText fl">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
							</li>
							<li data-n="3">
								<div class="main6DocLeftCir fl"></div>
								<div class="main6DocLeftText fl">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
							</li>
							<li data-n="4">
								<div class="main6DocLeftCir fl"></div>
								<div class="main6DocLeftText fl">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
							</li>
							<span class="main1Right_more">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
						</div>
						<div class="main6DocLeftCon">
							<div class="main6DocLeftTitle">【世界医学大师中国行】</div>
							<li data-n="5">
								<div class="main6DocLeftCir fl"></div>
								<div class="main6DocLeftText fl">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
							</li>
							<li data-n="6">
								<div class="main6DocLeftCir fl"></div>
								<div class="main6DocLeftText fl">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
							</li>
							<li data-n="7">
								<div class="main6DocLeftCir fl"></div>
								<div class="main6DocLeftText fl">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
							</li>
							<li data-n="8">
								<div class="main6DocLeftCir fl"></div>
								<div class="main6DocLeftText fl">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
							</li>
							<span class="main1Right_more">更多<img src="/Mobile/Public/Home/images/20180316cjys1.png" alt=""></span>
						</div>
					</div>
					<div class="main6DocrRight fr main6DocrRight_1">
						<img src="/Mobile/Public/Home/images/20180316cjys12.png" alt="">
						<div class="main6DocrRightTitle">日本肺癌外科第一人铃木健司： 肺癌的最新进展及治疗极限</div>
						<div class="main6DocrRightText">位于东京的顺天堂大学医学部附属顺天堂医院，是日本肺癌外科的最高殿堂，医院呼吸器外科的铃木健司被誉为日本肺癌外科第一人，近日，他应海外医疗机构厚朴方舟之邀来到中国，讲述了肺癌的最新进展及治疗极限...</div>
					</div>
					<div class="main6DocrRight fr main6DocrRight_2">
						<img src="/Mobile/Public/Home/images/20180316cjys12.png" alt="">
						<div class="main6DocrRightTitle">2222222</div>
						<div class="main6DocrRightText">位于东京的顺天堂大学医学部附属顺天堂医院，是日本肺癌外科的最高殿堂，医院呼吸器外科的铃木健司被誉为日本肺癌外科第一人，近日，他应海外医疗机构厚朴方舟之邀来到中国，讲述了肺癌的最新进展及治疗极限...</div>
					</div>
					<div class="main6DocrRight fr main6DocrRight_3">
						<img src="/Mobile/Public/Home/images/20180316cjys12.png" alt="">
						<div class="main6DocrRightTitle">333333333</div>
						<div class="main6DocrRightText">位于东京的顺天堂大学医学部附属顺天堂医院，是日本肺癌外科的最高殿堂，医院呼吸器外科的铃木健司被誉为日本肺癌外科第一人，近日，他应海外医疗机构厚朴方舟之邀来到中国，讲述了肺癌的最新进展及治疗极限...</div>
					</div>
					<div class="main6DocrRight fr main6DocrRight_4">
						<img src="/Mobile/Public/Home/images/20180316cjys12.png" alt="">
						<div class="main6DocrRightTitle">44444444444444</div>
						<div class="main6DocrRightText">位于东京的顺天堂大学医学部附属顺天堂医院，是日本肺癌外科的最高殿堂，医院呼吸器外科的铃木健司被誉为日本肺癌外科第一人，近日，他应海外医疗机构厚朴方舟之邀来到中国，讲述了肺癌的最新进展及治疗极限...</div>
					</div>
					<div class="main6DocrRight fr main6DocrRight_5">
						<img src="/Mobile/Public/Home/images/20180316cjys12.png" alt="">
						<div class="main6DocrRightTitle">5555555555555</div>
						<div class="main6DocrRightText">位于东京的顺天堂大学医学部附属顺天堂医院，是日本肺癌外科的最高殿堂，医院呼吸器外科的铃木健司被誉为日本肺癌外科第一人，近日，他应海外医疗机构厚朴方舟之邀来到中国，讲述了肺癌的最新进展及治疗极限...</div>
					</div>
					<div class="main6DocrRight fr main6DocrRight_6">
						<img src="/Mobile/Public/Home/images/20180316cjys12.png" alt="">
						<div class="main6DocrRightTitle">6666666666666</div>
						<div class="main6DocrRightText">位于东京的顺天堂大学医学部附属顺天堂医院，是日本肺癌外科的最高殿堂，医院呼吸器外科的铃木健司被誉为日本肺癌外科第一人，近日，他应海外医疗机构厚朴方舟之邀来到中国，讲述了肺癌的最新进展及治疗极限...</div>
					</div>
					<div class="main6DocrRight fr main6DocrRight_7">
						<img src="/Mobile/Public/Home/images/20180316cjys12.png" alt="">
						<div class="main6DocrRightTitle">777777777777777</div>
						<div class="main6DocrRightText">位于东京的顺天堂大学医学部附属顺天堂医院，是日本肺癌外科的最高殿堂，医院呼吸器外科的铃木健司被誉为日本肺癌外科第一人，近日，他应海外医疗机构厚朴方舟之邀来到中国，讲述了肺癌的最新进展及治疗极限...</div>
					</div>
					<div class="main6DocrRight fr main6DocrRight_8">
						<img src="/Mobile/Public/Home/images/20180316cjys12.png" alt="">
						<div class="main6DocrRightTitle">88888888888888888</div>
						<div class="main6DocrRightText">位于东京的顺天堂大学医学部附属顺天堂医院，是日本肺癌外科的最高殿堂，医院呼吸器外科的铃木健司被誉为日本肺癌外科第一人，近日，他应海外医疗机构厚朴方舟之邀来到中国，讲述了肺癌的最新进展及治疗极限...</div>
					</div>
					<div class="cl"></div>
				</div>
			</div>
		</div>
	<!-- footer -->
<style>
	/*footer*/
	.footerBox{
		width:100%;
		height:240px;
		background:#333;
		padding-top:64px;
	}
	.footer{
		width:812px;
		height:69px;
		margin:0 auto;
		text-align:center;
	}
	.footer *{
		color:#f4f4f4;
	}
	.footer2{
		margin:15px 0;
	}
</style>
<div class="footerBox">
	<div class="footer">
		<div class="footer1">关于我们 | 联系我们 | 招聘信息 | 合作伙伴 | 法律声明 | 服务协议 | 隐私条款 | 意见与建议</div>
		<div class="footer2">Copyright©2017 超级医生百科网 京ICP证 110448号 京卫网审[2011] 第0473号 京公网安备 11010202003110号</div>
		<div class="footer3">互联网药品信息服务资格证书 (京)-经营性-2016-0022</div>
	</div>
</div>

</div>
</body>
</html>