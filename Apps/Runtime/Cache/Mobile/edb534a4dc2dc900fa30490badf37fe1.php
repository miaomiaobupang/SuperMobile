<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>超级医生</title>
	<link rel="stylesheet" href="/Mobile/Public/Mobile/css/bootstrap.min.css">
	<link rel="stylesheet" href="/Mobile/Public/Mobile/css/font-awesome.css">
	<link rel="stylesheet" href="/Mobile/Public/Mobile/css/swiper-4.3.3.min.css">
	<script src="/Mobile/Public/Mobile/js/swiper-4.3.3.min.js"></script>
	<script src="/Mobile/Public/Mobile/js/jquery-3.3.1.min.js"></script>
	<style>
		/*共公头部结束*/
		html{
			font-size:62.5%;
			margin:0 0 0rem;
		}
		input[type=button],input[type=text],input[type=password]{
			-webkit-appearance:none;
			outline:none;
		}
		textarea {
			-webkit-appearance: none;
			outline:none;
		} 
		select{
			-webkit-appearance: none;
			outline:none;
		}
		.mobilePublicNav{
			width:102%;
			height:13rem;
			margin-left:-0.8rem;
			margin-top:-0.8rem;
			background:rgba(0,159,168,1);
			position:fixed;
			z-index:10;
		}
		.navDiv{
			height:13rem;
			line-height:13rem;
			font-size:6rem;
			margin-left:3%;
			margin-top: 0.5rem;
			float:left;
		}
		.cl{
			clear:both;
		}
		.oneNavDiv{
			width:20%;
			text-align:left;
		}
		.twoNavDiv{
			width:50%;
			text-align:center;
			color:white;
			overflow: hidden;
			text-overflow:ellipsis;
			white-space: nowrap;
		}
		.threeNavDiv{
			text-align:right;
			width:20%;
		}
		/*公共底部开始*/
		.mobilePublicFot{
			width:100%;
			height:15rem;
			right:0rem;
			bottom:0rem;
			background:rgba(0,159,168,1);
			position:fixed;
			z-index:10;
		}
		.fotDiv{
			width:24.8%;
			height:15rem;
			color:white;
			float:left;
		}
		.fotDivFirst{
			height:7.5rem;
			width:24.8%;
		}
		.fotDivTwo{
			width:100%;
			height:7.5rem;
			line-height:7.5rem;
			font-size:4rem;
			text-align:center;
		}
		.firstImg{
			margin-top:3rem;
			margin-left:10rem;
		}
		.fotDivBorder{
			border-right:0.2rem solid #058C94;
		}
		/*左下角导航菜单*/
		.mobilePublicFotNav{
			width:104%;
			left:-1rem;
			top:-1rem;
			position:fixed;
			background:rgba(0,0,0,0.7);
			display:none;
			z-index:100;
		}
		.mobilePublicFotNavTwo{
			width:100%;
			height:auto;
			position:fixed;
			bottom:15rem;
			left:0rem;
		}
		.fotNavList{
			width:100%;
			height:10rem;
			line-height:10rem;
			background:#fff;
			border-bottom:0.2rem solid rgba(220,220,220,1);
			font-size:4rem;
			text-align:center;
			color:rgba(51,51,51,1);
		}
		a{
			text-decoration:none;
		}
		.fl{
			float:left;
		}
		
		/*********公共登录********/
		.loginPublicModel{
			width:100%;
			height:100%;
			position:absolute;
			top:0rem;
			left:0rem;
			background:rgba(0,0,0,0.7);
			z-index:1000;
			position:fixed;
			display:none;
		}
		.loginPublicModelOne{
			width:84%;
			height:70rem;
			background:#fff;
			border-radius:2rem;
		}
		.loginTitle{
			width:100%;
			height:12rem;
			line-height:12rem;
			text-align:center;
			font-size:5rem;
			color:#009FA8;
			border-bottom:0.1rem solid #999;
		}
		.publicLoginDiv{
			width:100%;
			position:relative;
			margin-top:5rem;
		}
		.publicLoginInput{
			width:80%;
			height:10rem;
			line-height:10rem;
			font-size:3.5rem;
			color:#666;
			border:0.1rem solid #999;
			border-radius:10rem;
			margin-left:10%;
			padding:0 0 0 2rem;
		}
		.publicLoginQrcode{
			position:absolute;
			width:auto;
			padding:0 1.5rem 0 1.5rem;
			font-size:3.5rem;
			background:#009FA8;
			color:#fff;
			height:6rem;
			line-height:6rem;
			text-align:center;
			border-radius:10rem;
			right:12%;
			top:2rem;
		}
		.publicLoginSubmit{
			width:80%;
			height:10rem;
			line-height:10rem;
			font-size:5rem;
			color:#666;
			border-radius:10rem;
			margin-left:10%;
			margin-top:8rem;
			background:#009FA8;
			text-align:center;
			color:#fff;
		}
		/*********公共登录********/
	</style>
</head>
	<div class="mobilePublicNav">
		<div class="navDiv oneNavDiv" data-n="1"><img src="/Mobile/Public/Mobile/image/20180614logo.png"></div>
		<div class="navDiv twoNavDiv">400-052-0680</div>
		<div class="navDiv threeNavDiv"><img src="/Mobile/Public/Mobile/image/20180614search.png" style="margin-right:2rem;"></div>
		<div class="cl"></div>
	</div>
	<div class="loginPublicModel">
		<div class="loginPublicModelOne">
			<div class="loginTitle">快速登录</div>
			<div class="publicLoginDiv"><input type="text" name="name" value="" class="publicLoginInput" id="loginName" placeholder="请输入手机号"/></div>
			<div class="publicLoginDiv"><input type="text" name="name" value="" class="publicLoginInput" id="loginPass" placeholder="请输入验证码"/><div class="publicLoginQrcode">获取验证码</div></div>
			<div class="publicLoginSubmit">快速登录</div>
		</div>
	</div>
	<style>
		/*主体部分开始*/
		.mainBox{
			width:102%;
			height:auto;
			margin-left:-0.8rem;
			background:#f0f0f0;
			position:absolute;
		}
		.diseaseSearch{
			width:100%;
			height:12rem;
			line-height:12rem;
			text-align:center;
			margin-top:14rem;
			background:#fff;
			padding-top:2rem;
		}
		.searchPublicBody{
			width:80%;
			margin-left:10%;
			height:8rem;
			line-height:8rem;
			border-radius:10rem;
			background:#f0f0f0;
		}
		.searchPublicIco{
			margin:-1rem 0 0 -5rem;
		}
		#diseaseKeyWords{
			width:80%;
			height:8rem;
			line-height:8rem;
			border-radius:10rem;
			border:0.1rem solid #f0f0f0;
			background:#f0f0f0;
			font-size:3.5rem;
			color:#666;
		}
		.swiperBanners{
			margin-top:2rem;
		}
		.autoBanner{
			width:100%;
		}
		.bannerText{
			width:100%;
			height:8rem;
			line-height:8rem;
			position:absolute;
			bottom:0;
			padding:2rem 2rem;
			background:rgba(0,0,0,0.4);
		}
		.bannerText1{
			margin-top:-1.5rem;
			font-size:4rem;
			color:#fff;
			overflow: hidden;
			text-overflow:ellipsis;
			white-space: nowrap;
		}
		.doctorRecommend{
			margin-top:2rem;
			width:100%;
			height:auto;
		}
		.doctorRecommendOne{
			width:100%;
			height:9rem;
			background:#fff;
			border-bottom:0.2rem solid rgba(220,220,220,1);
		}
		.doctorRecommendLine{
			width:20rem;
			height:9rem;
			line-height:9rem;
			font-size:4rem;
			text-align:center;
			float:left;
		}
		.diseaseLink{
			width:100%;
			padding:0rem 2rem 2rem 2rem;
			height:auto;
			background:#fff;
		}
		.hotDisease{
			width:auto;
			height:6rem;
			line-height:6rem;
			text-align:center;
			border:0.2rem solid #f0f0f0;
			padding:0 1rem 0 1rem;
			float:left;
			font-size:3.5rem;
			color:#333;
			margin-right:2rem;
			margin-top:2rem;
		}
		.diseaseLinkInfo{
			width:100%;
			height:auto;
			padding:2rem;
			background:#fff;
		}
		.diseaseLinkInfo li{
			width:50%;
			height:5rem;
			line-height:5rem;
			font-size:4rem;
			float:left;
			padding-left:-2rem;
			margin-top:2rem;
		}
		.diseaseLinkInfo:after{
			content:'.';
			clear:both;
		}
	</style>
	<div class="mainBox">
		<div class="diseaseSearch">
			<div class="searchPublicBody">
			<img class="searchPublicIco" src="/Mobile/Public/Mobile/image/20180703searchico.png">
			<input type="text" name="keywords" value="" id="diseaseKeyWords" placeholder="请输入疾病"/>
			</div>
		</div> 
		<div class="swiperBanners"> 
			<div class="swiper-container disSwiperOne">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<a href=""><img class="autoBanner" src="/Mobile/Public/Mobile/image/20180615banner1.jpg"></a>
						<div class="bannerText">
							<div class="bannerText1">5种食物竟然是防癌圣品</div>
						</div>
					</div>
					<div class="swiper-slide">
						<a href=""><img class="autoBanner" src="/Mobile/Public/Mobile/image/20180615banner2.jpg"></a>
						<div class="bannerText">
							<div class="bannerText1">5种食物竟然是防癌圣品</div>
						</div>
					</div>
					<div class="swiper-slide">
						<a href=""><img class="autoBanner" src="/Mobile/Public/Mobile/image/20180615banner3.jpg"></a>
						<div class="bannerText">
							<div class="bannerText1">5种食物竟然是防癌圣品</div>
						</div>
					</div>
				</div>
			</div>
		</div> 
		<div class="doctorRecommend">
			<div class="doctorRecommendOne">
				<div class="doctorRecommendLine" style="width:auto;margin-left:2rem;color:#009FA8;font-size:5rem;">当季高发重疾</div>
				<div class="doctorRecommendLine" style="float:right;color:#999;font-size:3.5rem;text-align:right;">更多<img style="position:relative;top:-0.3rem;margin-left:1rem;" src="/Mobile/Public/Mobile/image/20180615right.png"> &nbsp;&nbsp;</div>
			</div> 
			<div class="diseaseLink"> 
				<div class="hotDisease">肺癌</div>
				<div class="hotDisease">胃癌</div>
				<div class="hotDisease">卵巢癌</div>
				<div class="hotDisease">卵巢癌</div>
				<div class="hotDisease">呼吸道感染</div>
				<div class="hotDisease">小细胞肺癌</div>
				<div class="hotDisease">肠癌</div>
				<div class="hotDisease">呼吸道感染</div>
				<div class="hotDisease">卵巢癌</div>
				<div class="hotDisease">呼吸道感染</div>
				<div class="hotDisease">小细胞肺癌</div>
				<div class="hotDisease">肠癌</div>
				<div class="hotDisease">小细胞肺癌</div>
				<div class="hotDisease">肠癌</div>
				<div class="cl"></div>
			</div> 
		</div> 
		<div class="doctorRecommend">
			<div class="doctorRecommendOne">
				<div class="doctorRecommendLine" style="width:auto;margin-left:2rem;color:#009FA8;font-size:5rem;">更多疾病</div>
				<div class="doctorRecommendLine" style="float:right;color:#999;font-size:3.5rem;text-align:right;">更多<img style="position:relative;top:-0.3rem;margin-left:1rem;" src="/Mobile/Public/Mobile/image/20180615right.png"> &nbsp;&nbsp;</div>
						<div class="cl"></div>
			</div> 
			<div class="diseaseLink"> 
				<div class="swiper-container disSwiperTwo">
					<div class="swiper-wrapper">
						<div class="swiper-slide" data-history="slide1" data-n="1">头颈部</div>
						<div class="swiper-slide" data-history="slide2" data-n="2">胸腹部</div>
						<div class="swiper-slide" data-history="slide3" data-n="2">盆腔</div>
						<div class="swiper-slide" data-history="slide3" data-n="2">皮肤</div>
						<div class="swiper-slide" data-history="slide3" data-n="2">儿童肿瘤</div>
						<div class="swiper-slide" data-history="slide3" data-n="2">其他</div>
						<div class="cl"></div>
					</div>
				</div>
			</div>
			<div class="diseaseLinkInfo">
				<ul> 
					<li>脑瘤</li>
					<li>颊癌</li>
					<li>鼻咽癌</li>
					<li>垂体癌</li>
					<li>口腔癌</li>
					<li>脑膜瘤</li>
					<li>眼部肿瘤</li>
					<li>胶质瘤</li>
					<li>舌癌</li>
					<li>视网膜母细胞癌</li>
					<li>牙龈癌</li>
					<li>甲状腺癌</li>
					<li>胶质瘤</li>
					<li>喉癌</li>
				</ul> 
			</div> 
			<div style="width:100%;height:30rem;"> 
			</div> 
		</div> 
	</div>
	<script>
		//轮播图
		var disSwiperOne = new Swiper('.disSwiperOne',{
				autoplay: true,
			});
			
		var disSwiperTwo = new Swiper('.disSwiperTwo',{
				history: true,
			});
		//动态调整导航宽高
		$('.disSwiperTwo .swiper-slide').css({'width':'auto','font-size':'4rem','padding':'2rem'});
		$('.disSwiperTwo .swiper-slide').each(function(){
			var n = $(this).data('n');
			if(n==1){
				$(this).css({'border-bottom':'0.2rem solid #009FA8'});
			}
		});
		//动态改变导航栏信息
		$('.oneNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180627reback.png">');
		$('.oneNavDiv').data('n',2);
		$('.twoNavDiv').html('疾病库');
	</script> 
	<div class="mobilePublicFot">
		<div class="fotDiv fotDivBorder fotDivBorderNav" data-n="1">
			<div class="fotDivFirst"><img src="/Mobile/Public/Mobile/image/20180614daohang.png" class="firstImg"></div>
			<div class="fotDivTwo">导航</div>
		</div>
		<div class="fotDiv fotDivBorder">
			<div class="fotDivFirst"><img src="/Mobile/Public/Mobile/image/20180614dianhuazixun.png" class="firstImg"></div>
			<div class="fotDivTwo">电话咨询</div>
		</div>
		<div class="fotDiv fotDivBorder">
			<div class="fotDivFirst"><img src="/Mobile/Public/Mobile/image/20180614zaixianzixun.png" class="firstImg"></div>
			<div class="fotDivTwo">在线咨询</div>
		</div>
		<div class="fotDiv">
			<div class="fotDivFirst"><img src="/Mobile/Public/Mobile/image/20180614liuyan.png" class="firstImg"></div>
			<div class="fotDivTwo">立即留言</div>
		</div>
	</div>
	<div class="mobilePublicFotNav">
		<div class="mobilePublicFotNavTwo">
			<a href="http://192.168.1.21/Mobile/index.php/Mobile/Index/index"><div class="fotNavList">首页</div></a>
			<a href="http://192.168.1.21/Mobile/index.php/Mobile/Index/questionIndex"><div class="fotNavList">互动问答</div></a>
			<a href="http://192.168.1.21/Mobile/index.php/Mobile/Index/newMessages"><div class="fotNavList">前沿资讯</div></a>
			<a href="http://192.168.1.21/Mobile/index.php/Mobile/Index/diseaseIndex"><div class="fotNavList">疾病知识</div></a>
			<a href="http://192.168.1.21/Mobile/index.php/Mobile/Index/hospitalIndex"><div class="fotNavList">权威医院</div></a>
			<a href="http://192.168.1.21/Mobile/index.php/Mobile/Index/expertIndex"><div class="fotNavList">超级专家</div></a>
		</div>
	</div>
	<script>
		var height = $(window).height()/12-2;
		$('.mobilePublicFotNav').css({'height':height+'rem','top':'12.2rem'});
		//点击展开导航
		$('.fotDivBorderNav').click(function(){
			var n = $(this).data('n');
			if(n==1){
				$('.mobilePublicFotNav').slideDown('fast');
				$(this).css({'backgroundColor':'#008c95'});
				$(this).data('n',2);
			}else if(n==2){
				$(this).css({'backgroundColor':'#009FA8'});
				$('.mobilePublicFotNav').slideUp('fast');
				$(this).data('n',1);
			}
		});
		$('.mobilePublicFotNav').on("click",function(){
			$('.mobilePublicFotNav').slideUp('fast');
			$('.fotDivBorderNav').css({'backgroundColor':'#009FA8'});
			$('.fotDivBorderNav').data('n',1);
		});
		//定时刷新页面
		setInterval(function(){
			<!-- window.location.reload() -->
		},5000);
		$('.oneNavDiv').click(function(){
			var n = $(this).data('n');
			if(n==2){
				//返回上个页面
				window.location.href=document.referrer;
			}
		});
		//透明导航栏
		var publicN = $('.oneNavDiv').data('n');
		$(window).scroll(function(){
			var bodyScrollTop = $(window).scrollTop();
			if(bodyScrollTop > 100){
				$('.mobilePublicNav').css({'background':'#fff'});
				$('.twoNavDiv').css({'color':'#999'});
				<!-- alert(publicN); -->
				if(publicN == 2){
					$('.oneNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180627newreback.png">');
				}else{
					$('.oneNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180627newlogo.png">');
				}
				$('.threeNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180627newsearch.png" style="margin-right:2rem;">');
				$('.mobilePublicNav').css({'box-shadow':'0rem 0rem 4rem rgba(220,220,220,1)'});
			}else{
				$('.mobilePublicNav').css({'background':'#009FA8'});
				$('.twoNavDiv').css({'color':'#fff'});
				if(publicN == 2){
					$('.oneNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180627reback.png">');
				}else{
					$('.oneNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180614logo.png">');
				}
				$('.threeNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180614search.png" style="margin-right:2rem;">');
				
			}
		});
		//公共登录框
		$(function(){
			var loginTop = ($(window).height()/12-70)/2;
			$('.loginPublicModelOne').css({'margin-top':loginTop+'rem','margin-left':'8%'});
			$('.loginPublicModelOne').on('click',function(e){
				e.stopPropagation();
			});
			$('.fotNavList').on('click',function(e){
				e.stopPropagation();
			});
			$('.loginPublicModel').on('click',function(){
				$('.loginPublicModel').css({'display':'none'});
			});
		});
	</script>
</html>