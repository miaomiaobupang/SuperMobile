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
		.BreadcrumbTrail{
			width:100%;
			height:7rem;
			background:#fff;
			margin-top:12rem;
		}
		.breadLeftImg{
			width:12%;
			float:left;
			height:7rem;
			line-height:7rem;
			font-size:3.5rem;
			color:#999;
			text-align:center;
			margin-left:2rem;
		}
		.breadLeftText{
			width:85%;
			height:7rem;
			line-height:7rem;
			float:left;
			font-size:3.5rem;
			color:#999;
			text-align:left;
			overflow: hidden;
			text-overflow:ellipsis;
			white-space: nowrap;
		}
		.questionInfo{
			margin-top:2rem;
			width:100%;
			height:auto;
			background:#fff;
			padding:0 0 3rem 0;
		}
		.questionInfoLine{
			width:100%;
			height:auto;
			padding-left:2rem;
		}
		.questionName{
			width:100%;
			height:7rem;
			line-height:7rem;
			font-size:4rem;
			overflow:hidden;
			text-overflow:ellipsis;
			white-space:nowrap;
		}
		.questionNameinfo span{
			color:#999;
			font-size:3.5rem;
			height:5rem;
			line-height:5rem;
		}
		.questionContent{	
			width:100%;
			line-height:5rem;
			font-size:3.5rem;
			padding:1rem 0 1rem 0;
			display: -webkit-box;
			-webkit-box-orient: vertical;
			-webkit-line-clamp: 3;
			overflow: hidden;
		}
		.questionTags{
			width:100%;
			height:7rem;
			padding:1rem 0 1rem 0;
			line-height:5rem;
		}
		.questionTagsL{
			width:77%;
			float:left;
		}
		.questionTagsR{
			width:23%;
			float:left;
			height:6rem;
			line-height:6rem;
			text-align:center;
			font-size:3.5rem;
			background:#009FA8;
			color:#fff;
			border-radius:6rem;
			padding:0 1rem 0 1rem;
		}
		.questionTagList{
			width:auto;
			height:6rem;
			line-height:6rem;
			font-size:3.5rem;
			background:rgba(249,249,249,1);
			border-radius:4rem;
			border:0.1rem solid #999;
			float:left;
			text-align:center;
			padding:0 1.5rem 0 1.5rem;
			margin-right:2rem;
		}
		.answersInfo{
			width:100%;
			height:auto;
			padding:2rem;
			margin-top:2rem;
			background:#fff;
		}
		.answersInfoTitle{
			width:auto;
			height:5rem;
			line-height:5rem;
		}
		.answerImgText{
			float:left;
			font-size:5rem;
		}
		.answerImgText img{
			margin-top:-1rem;
		}
		.answersInfoList{
			width:100%;
			height:auto;
			border-bottom:0.2rem solid #DCDCDC;
			margin-top:2rem;
		}
		.answersInfoPerson{
			width:100%;
			height:12rem;
		}
		.answersInfoPersonL{
			width:20%;
			height:12rem;
			line-height:12rem;
			text-align:center;
			float:left;
		}
		.answersInfoPersonL img{
			width:10rem;
		}
		.answersInfoPersonR{
			width:80%;
			height:12rem;
			float:left;
		}
		.answersInfoPersonRU{
			width:100%;
			height:5rem;
			line-height:5rem;
			margin-top:2rem;
			font-size:4rem;
		}
		.answersInfoPersonRD{
			width:100%;
			height:4rem;
			line-height:4rem;
			font-size:3.5rem;
			color:#999;
		}
		.answersInfoContent{
			width:100%;
			height:auto;
			line-height:4rem;
			font-size:3.5rem;
			margin-top:2rem;
		}
		.myAnswersInfo{
			width:100%;
			height:40rem;
			padding:2rem;
			margin-top:2rem;
			background:#fff;
			display:none;
		}
		.myanswersInfoTitle{
			width:auto;
			height:5rem;
			line-height:5rem;
		}
		.myanswerTextDiv{
			width:100%;
			height:30rem;
			border:0.1rem solid #999;
			margin-top:1rem;
		}
		.myanswerTextarea{
			width:100%;
			height:20rem;
			line-height:5rem;
			border:0rem solid #fff;
			font-size:4rem;
			text-indent:2rem;
		}
		.myanswerSubmit{
			width:15%;
			height:6rem;
			line-height:6rem;
			background:#009FA8;
			color:#fff;
			text-align:center;
			font-size:4rem;
			float:right;
			border-radius:6rem;
			margin:2rem 2rem 0 0;
		}
	</style>
	<div class="mainBox">
		<div class="BreadcrumbTrail">
			<div class="breadLeftImg"><img src="/Mobile/Public/Mobile/image/20180627backtoindex.png"> 首页</div>
			<div class="breadLeftText"> > 互动问答 > 肿瘤类 > 女生右腰部隐痛的原因是什么呢?</div>
		</div>
		<div class="questionInfo">
			<div class="questionInfoLine">
				<div class="fl" style="width:6%;height:7rem;line-height:7rem;"><img src="/Mobile/Public/Mobile/image/20180718questioninfo.png"></div>
				<div class="fl" style="width:90%;margin-left:1rem;">
					<div class="questionName">女生右腰部隐痛的原因是什么呢?</div>
					<div class="questionNameinfo"><span>女</span>&nbsp;<span>43岁</span>&nbsp;&nbsp;<span>发病时间：</span>&nbsp;<span>不清楚</span></div>
					<div class="questionContent">病情描述：右下腹隐痛，去医院做检查也没有查出什么问题，这个是什么原因呢，求医生解答。</div>
					<div class="questionNameinfo"><span>蘑菇爬过慢时光</span>&nbsp;|&nbsp;<span>2018-7-18</span></div>
					<div class="questionTags">
						<div class="questionTagsL">
							<div class="questionTagList">肺癌</div>
							<div class="questionTagList">胃癌</div>
							<div class="cl"></div>
						</div>
						<div class="questionTagsR">我要回答</div>
					</div>
				</div>
				<div class="cl"></div>
			</div>
		</div> 
		<div class="myAnswersInfo">
			<div class="myanswersInfoTitle">
				<div class="answerImgText"><img src="/Mobile/Public/Mobile/image/20180615changjianzhongji.png"></div>
				<div class="answerImgText" style="margin-left:1rem;">我要回答</div>
			</div>
			<div class="myanswerTextDiv">
				<textarea class="myanswerTextarea"></textarea>
				<div class="myanswerSubmit"> 提交 </div>
			</div>
		</div>
		<div class="answersInfo">
			<div class="answersInfoTitle">
				<div class="answerImgText"><img src="/Mobile/Public/Mobile/image/20180615changjianzhongji.png"></div>
				<div class="answerImgText" style="margin-left:1rem;">其他答案</div>
			</div>
			<div class="answersInfoList">
				<div class="answersInfoPerson">
					<div class="answersInfoPersonL"><img src="/Mobile/Public/Mobile/image/20180424head5.png"></div>
					<div class="answersInfoPersonR">
						<div class="answersInfoPersonRU">谁手落子应无悔</div>
						<div class="answersInfoPersonRD">2018-7-18 9:23</div>
					</div>
				</div>
				<div class="answersInfoContent"><p>我会粗鲁地报告它。 <br><br>没有双手和外表的残疾人很普通，但公司方有历史<br><br>确认后，突然确认<a href="http://www.superdoctor.cn/diseaseList/37" target="_blank">癫痫</a>状态。我报道。 <br><br>我的<a href="http://www.superdoctor.cn/diseaseList/37" target="_blank">癫痫</a>发生水平是每月1到2次，如果是每年一次，则是几十次<br><br>它是。产生这么多人的人回答说不可能雇用。 <br><br>如果是这样，你可以在多大程度上招募？如果你问和回答<br><br>没有它。 <br><br></p></div>
			</div>
			<div class="answersInfoList">
				<div class="answersInfoPerson">
					<div class="answersInfoPersonL"><img src="/Mobile/Public/Mobile/image/20180424head5.png"></div>
					<div class="answersInfoPersonR">
						<div class="answersInfoPersonRU">谁手落子应无悔</div>
						<div class="answersInfoPersonRD">2018-7-18 9:23</div>
					</div>
				</div>
				<div class="answersInfoContent"><p>我会粗鲁地报告它。 <br><br>没有双手和外表的残疾人很普通，但公司方有历史<br><br>确认后，突然确认<a href="http://www.superdoctor.cn/diseaseList/37" target="_blank">癫痫</a>状态。我报道。 <br><br>我的<a href="http://www.superdoctor.cn/diseaseList/37" target="_blank">癫痫</a>发生水平是每月1到2次，如果是每年一次，则是几十次<br><br>它是。产生这么多人的人回答说不可能雇用。 <br><br>如果是这样，你可以在多大程度上招募？如果你问和回答<br><br>没有它。 <br><br></p></div>
			</div>
			<div class="answersInfoList">
				<div class="answersInfoPerson">
					<div class="answersInfoPersonL"><img src="/Mobile/Public/Mobile/image/20180424head5.png"></div>
					<div class="answersInfoPersonR">
						<div class="answersInfoPersonRU">谁手落子应无悔</div>
						<div class="answersInfoPersonRD">2018-7-18 9:23</div>
					</div>
				</div>
				<div class="answersInfoContent"><p>我会粗鲁地报告它。 <br><br>没有双手和外表的残疾人很普通，但公司方有历史<br><br>确认后，突然确认<a href="http://www.superdoctor.cn/diseaseList/37" target="_blank">癫痫</a>状态。我报道。 <br><br>我的<a href="http://www.superdoctor.cn/diseaseList/37" target="_blank">癫痫</a>发生水平是每月1到2次，如果是每年一次，则是几十次<br><br>它是。产生这么多人的人回答说不可能雇用。 <br><br>如果是这样，你可以在多大程度上招募？如果你问和回答<br><br>没有它。 <br><br></p></div>
			</div>
		</div>
	</div>
	<script>
		//动态改变导航栏信息
		$('.oneNavDiv').html('<img src="/Mobile/Public/Mobile/image/20180627reback.png">');
		$('.oneNavDiv').data('n',2);
		$('.twoNavDiv').html('女生右腰部隐痛的原因');
		//我要回答
		$('.questionTagsR').click(function(){
			$(this).css({'background':'#999'});
			$('.myAnswersInfo').slideDown('fast');
		});
		//提交答案
		$('.myanswerSubmit').click(function(){
			var myAnswer = '';
			myAnswer += '<div class="answersInfoList">';
				myAnswer += '<div class="answersInfoPerson">';
					myAnswer += '<div class="answersInfoPersonL"><img src="/Mobile/Public/Mobile/image/20180424head5.png"></div>';
					myAnswer += '<div class="answersInfoPersonR">';
						myAnswer += '<div class="answersInfoPersonRU">谁手落子应无悔222</div>';
						myAnswer += '<div class="answersInfoPersonRD">2018-7-18 9:23</div>';
					myAnswer += '</div>';
				myAnswer += '</div>';
				myAnswer += '<div class="answersInfoContent"><p>我会粗鲁地报告它。 <br><br>没有双手和外表的残疾人很普通，但公司方有历史<br><br>确认后，突然确认<a href="http://www.superdoctor.cn/diseaseList/37" target="_blank">癫痫</a>状态。我报道。 <br><br>我的<a href="http://www.superdoctor.cn/diseaseList/37" target="_blank">癫痫</a>发生水平是每月1到2次，如果是每年一次，则是几十次<br><br>它是。产生这么多人的人回答说不可能雇用。 <br><br>如果是这样，你可以在多大程度上招募？如果你问和回答<br><br>没有它。 <br><br></p></div>';
			myAnswer += '</div>';
			$('.answersInfoTitle').after(myAnswer);
			$('.myAnswersInfo').slideUp('fast');
			$('.questionTagsR').css({'background':'#009FA8'});
		});
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
			<div class="fotNavList">前沿资讯</div>
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