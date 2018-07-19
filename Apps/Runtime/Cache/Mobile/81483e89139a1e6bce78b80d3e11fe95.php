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
<!--标准mui.css-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<link href="/Mobile/Public/Mobile/css/mui.min.css" rel="stylesheet" />
<link href="/Mobile/Public/Mobile/css/app.css" rel="stylesheet" />
<link href="/Mobile/Public/Mobile/css/mui.picker.css" rel="stylesheet" />
<link href="/Mobile/Public/Mobile/css/mui.poppicker.css" rel="stylesheet" />
	<style>
		/***********头部修改**************/
			.mobilePublicNav{
				height:5rem;
				margin-left:0rem;
			}
			.navDiv{
				height:5rem;
				line-height:5rem;
				font-size:2rem;
			}
			.oneNavDiv img{
				height:2rem;
			}
			.threeNavDiv img{
				height:2rem;
			} 
		/***********头部修改**************/
		/***********面包屑修改**************/
		.mainBox{
			width:100%;
			margin-left:0rem;
		}
		/***********面包屑修改**************/
		/***********底部修改**************/
		.mobilePublicFot{
			height:6rem;
		}
		.fotDiv{
			height:6rem;
		}
		.fotDivFirst{
			height:3rem;
		}
		.fotDivFirst img{
			height:2rem;
		}
		.firstImg{
			margin-top:1rem;
			margin-left:3.3rem;
		}
		.fotDivTwo{
			height:3rem;
			line-height:3rem;
			font-size:1.5rem;
		}
		/***********底部修改**************/
		/*主体部分开始*/
		.mainBox{
			width:100%;
			height:auto;
			background:#f0f0f0;
			position:absolute;
		}
		.BreadcrumbTrail{
			width:100%;
			height:3rem;
			background:#fff;
			margin-top:5rem;
		}
		.breadLeftImg{
			width:20%;
			float:left;
			height:3rem;
			line-height:3rem;
			color:#999;
			text-align:center;
			font-size:1.5rem;
		}
		.breadLeftImg img{
			width:1.3rem;
		}
		.breadLeftText{
			width:80%;
			height:3rem;
			line-height:3rem;
			float:left;
			font-size:1.5rem;
			color:#999;
			text-align:left;
			overflow: hidden;
			text-overflow:ellipsis;
			white-space: nowrap;
		}
		.questionListBox{
			width:100%;
			height:auto;
			margin-top:1rem;
			padding:1rem;
			background:#fff;
		}
		.questionListTitle{
			width:100%;
			height:auto;
		}
		.questionList{
			width: 50%;
			border: 0.1rem solid #009FA8;
			height: 4rem;
			line-height: 4rem;
			float: left;
			color: #009FA8;
			text-align: center;
			font-size: 1.5rem;
		}
		.questionListBoxWait{
			width:100%;
			height:auto;
			margin-top:1rem;
			display:none;
		}
		.questionLists{
			width:100%;
			height:3rem;
			line-height:3rem;
		}
		.questionListsL{
			width:88%;
			height:3rem;
			line-height:3rem;
			float:left;
			color:#666;
			font-size:1.5rem;
			overflow: hidden;
			text-overflow:ellipsis;
			white-space: nowrap;
		}
		.questionListsR{
			width:12%;
			height:3rem;
			line-height:3rem;
			float:left;
			font-size:1.2rem;
			color:#666;
		}
		.questionListBoxNice{
			width:100%;
			height:auto;
			margin-top:1rem;
		}
		.questionListNices{
			width:100%;
			height:7rem;
			margin-top:1rem;
		}
		.questionListNiceU{
			width:100%;
			height:3rem;
			line-height:3rem;
			font-size:1.5rem;
			display: -webkit-box;
			-webkit-box-orient: vertical;
			-webkit-line-clamp: 2;
			overflow: hidden;
		}
		.questionListNiceD{
			width:100%;
			height:4rem;
		}
		.questionListNiceDL{
			width:15%;
			height:4rem;
			line-height:4rem;
			text-align:left;
			float:left;
		}
		.questionListNiceDL img{
			width:4rem;
		}
		.questionListNiceDR{
			width:85%;
			height:4rem;
			line-height:2rem;
			font-size:1.2rem;
			color:#666;
			float:left;
			display: -webkit-box;
			-webkit-box-orient: vertical;
			-webkit-line-clamp: 2;
			overflow: hidden;
		}
		.ScreeningConditions{
			width:100%;
			height:5rem;
		}
		.ScreeningConditionsList{
			width:100%;
			font-size:1.5rem;
			color:#666;
			background:#fff;
			border-top:0.1rem solid #f0f0f0;
		}
		
		.mui-pciker-list li{
			font-size:1.5rem;
			line-height:3rem;
		}
		.mui-pciker-list, .mui-pciker-rule{
			height:3rem;
			line-height:3rem;
		}
		.mui-poppicker-header .mui-btn{
			font-size:1.5rem;
		}
		
		.screenList span{
			width:auto;
			display:inline-block;
			text-align:center;
			padding-right:0.4rem;
			line-height:1.2rem;
		}
		.screenList img{
			position:absolute;
			right:0rem;
		}
		/**********公共底部**********/
		.mobilePublicFotNavTwo{
			bottom:6rem;
		}
		.fotNavList{
			height:4rem;
			line-height:4rem;
			font-size:1.5rem;
			border-bottom:0.1rem solid rgba(220,220,220,1);
		}
		.mobilePublicFotNav{
			top:4.2rem !important;
		}
		/**********公共底部**********/
	</style>
	<div class="mainBox">
		<div class="BreadcrumbTrail">
			<div class="breadLeftImg"><img src="/Mobile/Public/Mobile/image/20180627backtoindex.png"> 首页</div>
			<div class="breadLeftText"> > 互动问答 > 肿瘤类</div>
		</div>
		<div class="ScreeningConditions">
			<div id='showCityPicker' class="ScreeningConditionsList mui-btn mui-btn-block screenList" >
				<span id='cityResult' >选择癌种</span>>
			</div>
		</div>
		<div class="questionListBox">
			<div class="questionListTitle">
				<div class="questionList" data-n="1" data-t="1">优质问答</div>
				<div class="questionList" data-n="2" data-t="2">待解决</div>
				<div class="cl"></div>
			</div>
			<div class="questionListBoxWait">
				<a href="http://192.168.1.21/Mobile/index.php/Mobile/Index/questionDetails">
					<div class="questionLists">
						<div class="questionListsL">女性右腰部隐痛的原因是什么呢？</div>
						<div class="questionListsR">6 回答</div>
					</div>
				</a>
				<div class="questionLists">
					<div class="questionListsL">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListsR">6 回答</div>
				</div>
				<div class="questionLists">
					<div class="questionListsL">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListsR">6 回答</div>
				</div>
				<div class="questionLists">
					<div class="questionListsL">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListsR">6 回答</div>
				</div>
				<div class="questionLists">
					<div class="questionListsL">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListsR">6 回答</div>
				</div>
				<div class="questionLists">
					<div class="questionListsL">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListsR">6 回答</div>
				</div>
				<div class="questionLists">
					<div class="questionListsL">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListsR">6 回答</div>
				</div>
			</div>
			<div class="questionListBoxNice">
				<a href="http://192.168.1.21/Mobile/index.php/Mobile/Index/questionDetails">
					<div class="questionListNices">
						<div class="questionListNiceU">女性右腰部隐痛的原因是什么呢？</div>
						<div class="questionListNiceD">
							<div class="questionListNiceDL"><img src="/Mobile/Public/Mobile/image/20180626xinneike.png"></div>
							<div class="questionListNiceDR">很高兴为您解答，病因很多，目前不能确定决定的患病原因，就应该要面对现实，去专业的医</div>
						</div>
					</div>
				</a>
				<div class="questionListNices">
					<div class="questionListNiceU">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListNiceD">
						<div class="questionListNiceDL"><img src="/Mobile/Public/Mobile/image/20180626xinneike.png"></div>
						<div class="questionListNiceDR">很高兴为您解答，病因很多，目前不能确定决定的患病原因，就应该要面对现实，去专业的医</div>
					</div>
				</div>
				<div class="questionListNices">
					<div class="questionListNiceU">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListNiceD">
						<div class="questionListNiceDL"><img src="/Mobile/Public/Mobile/image/20180626xinneike.png"></div>
						<div class="questionListNiceDR">很高兴为您解答，病因很多，目前不能确定决定的患病原因，就应该要面对现实，去专业的医</div>
					</div>
				</div>
				<div class="questionListNices">
					<div class="questionListNiceU">女性右腰部隐痛的原因是什么呢？</div>
					<div class="questionListNiceD">
						<div class="questionListNiceDL"><img src="/Mobile/Public/Mobile/image/20180626xinneike.png"></div>
						<div class="questionListNiceDR">很高兴为您解答，病因很多，目前不能确定决定的患病原因，就应该要面对现实，去专业的医</div>
					</div>
				</div>
			</div>
			<div style="width:100%;height:20rem;"></div>
		</div>
	<div id="sssss" style="width:100px;height:100px;background:#333;display:none;">11111</div>
	</div>
	<script src="/Mobile/Public/Mobile/js/zepto.min.js"></script>
	<script type="text/javascript" src="/Mobile/Public/Mobile/js/mui.min.js"></script>
	<script type="text/javascript" src="/Mobile/Public/Mobile/js/mui.picker.js"></script>
	<script type="text/javascript" src="/Mobile/Public/Mobile/js/mui.poppicker.js"></script>
	<script>
		//动态改变导航栏信息
		$('.oneNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180627reback.png">');
		$('.oneNavDiv').data('n',2);
		$('.twoNavDiv').html('肿瘤类问答');
		//遍历切换问题
		$('.questionList').each(function(){
			var n = $(this).data('n');
			if(n == 1){
				$(this).css({'background':'#009FA8','color':'#fff'});
			}
		});
		//问答列表点击切换
		$('.questionList').on('click',function(){
			var n = $(this).data('n');
			var t = $(this).data('t');
			if(n == 2){
				$('.questionList').css({'background':'#fff','color':'#009FA8'});
				$('.questionList').data('n',2);
				$(this).css({'background':'#009FA8','color':'#fff'});
				$(this).data('n',1);
			}
			if(t==1){
				$('.questionListBoxWait').hide('fast');
				$('.questionListBoxNice').show('fast');
			}else if(t==2){
				$('.questionListBoxNice').hide('fast');
				$('.questionListBoxWait').show('fast');
			}
		});
		//------------------行业筛选开始-----------------------
		//定义疾病分类Json数组
		

		eval('var diseaseJson = <?php echo ($diseaseArr); ?>');

		var diseaseid = null;
		//行业联动
		(function(mui, doc) {
			mui.init();
			mui.ready(function() {
				//级联示例
				var cityPicker = new mui.PopPicker({
					layer: 2
				}); 
				cityPicker.setData(diseaseJson);
				var showCityPickerButton = doc.getElementById('showCityPicker');
				var cityResult = doc.getElementById('cityResult');
				showCityPickerButton.addEventListener('tap', function(event) {
					cityPicker.show(function(items){
						//获取疾病列表选中的数据
						cityResult.innerHTML = items[1].text;
						diseaseid = items[1].id;
						//返回 false 可以阻止选择框的关闭
						//return false;
					});
					
				}, false); 

			});
		})(mui, document); 
		//点击弹框的确定按钮
		$('body').on('tap','.mui-poppicker-btn-ok',function(){
			alert(diseaseid);
		});

		//------------------行业筛选结束-----------------------


				
	
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