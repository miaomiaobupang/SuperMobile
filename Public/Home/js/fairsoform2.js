// +----------------------------------------------------------------------
// | 非速搜WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://fairso.com All rights reserved.
// +----------------------------------------------------------------------
// | Fairso 通用表单
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------

/*
	标准表单div示例
	<div class="inputDiv">
		//表单字段名
		<div class="inputText">
			<span>账户名：</span>
		</div>
		<div class="inputValue">
		//表单输入框
			<input id="passinput" class="formFrame  inputTxt username" placeholder="邮箱/手机号/会员名" type="text" data-id="1" data-state="2" data-title="账户名" data-ischeck="1" data-checktype="8" data-useronly="1"  data-userchecktype="7">
		</div>
		//表单提示框
		<div class="formPrompt formPrompt-1">
			<div class="alert alert-success" role="alert">
				<span class="icon-info-sign promptIco"></span><span class="promptText-1">请输入账户名</span>
			</div>
		</div>
		//表单报错框
		<div class="formError formError-1">
			<div class="alert alert-danger" role="alert">
				<span class="icon-remove-sign errorIco"></span><span class="errorText-1">账户名错误！</span>
			</div>
		</div>
	</div>
*/
//输入框执行绿色输入状态
$('.inputTxt').focus(function(){
	var id =$(this).data('id');
	//初始化
	$('.inputTxt').each(function(){
		//判断是否为错误状态，如果不是错误状态，则执行灰色边框初始化
		if($(this).data('state')!=3){
			$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
		}
	});
	//输入框执行绿色输入状态
	$(this).css({ "border": "1px solid #06da24", "box-shadow": "0 0 2px #a1f3ab" });
	//隐藏当前错误框
	$('.formError-'+id).hide();
	//打开当前提示
	$('.formPrompt-'+id).show();
});
//输入框执行校验
//该校验使用data缓存传值校验
//参数：	id				表单项ID
//			state			表单状态 1正确 2未操作（默认） 3错误
//			title			表单文本 
//			ischeck			是否启用校验 1启用 2不启用 
//			checktype		校验方式 
//									1手机号 2邮箱 3验证数字 4验证n位的数字（依赖length参数） 5验证至少n位数字（依赖length参数） 
//									6验证m-n位的数字（依赖slength、olength参数） 7验证有两位小数的正实数 8验证用户唯一 9验证密码
//									10验证重复密码 11校验验证码
//			slength			校验长度开始数（checktype为6、9时起作用，选其他忽略）
//			olength			校验长度结束数 （checktype为6、9时起作用，选其他忽略）
//			length			校验长度 （checktype为4、5时起作用，选其他忽略）
//			useronly		校验用户存在类型（checktype为8时起作用，选其他忽略） 1校验用户是否存在（不存在报错） 2校验用户是否不存在（存在报错）注意：依赖地址接口，请保证地址接口畅通
//			userchecktype	用户校验类型（checktype为8时起作用，选其他忽略） 1用户名 2手机号 3邮箱 4用户名和手机号 5用户名和邮箱 6手机号和邮箱 7用户名，手机号和邮箱
//			passid			密码框的ID名（请保证页面唯一） 
$('.inputTxt').focusout(function(){
	var id = $(this).data('id');
	//关闭当前提示
	$('.formPrompt-'+id).hide();
	if($(this).data('ischeck')!=undefined && $(this).data('ischeck')==1){
		var value = $(this).val();
		var input = $(this);
		if(value){
			if($(this).data('checktype')!=undefined){
				if($(this).data('checktype')==1){
					//校验是否为手机号
					//校验手机号是否为11位
					var re = /^1\d{10}$/;//手机正则表达式
					if(re.test(value)){
						$(this).data('state',1);
						$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
						$('.errorText-'+id).html('');
						$('.formError-'+id).hide();
					}else{
						$(this).data('state',3);
						$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
						$('.errorText-'+id).html('手机号格式错误！');
						$('.formError-'+id).show();
					}
				}else if($(this).data('checktype')==2){
					//校验是否为邮箱
					//校验手机号是否为11位
					var re = /^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/;//邮箱正则表达式
					if(re.test(value)){
						$(this).data('state',1);
						$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
						$('.errorText-'+id).html('');
						$('.formError-'+id).hide();
					}else{
						$(this).data('state',3);
						$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
						$('.errorText-'+id).html('邮箱格式错误！');
						$('.formError-'+id).show();
					}
				}else if($(this).data('checktype')==3){
					//校验是否为纯数字
					var re = /^[0-9]*$/;//纯数字正则表达式
					if(re.test(value)){
						$(this).data('state',1);
						$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
						$('.errorText-'+id).html('');
						$('.formError-'+id).hide();
					}else{
						$(this).data('state',3);
						$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
						$('.errorText-'+id).html($(this).data('title')+'不是纯数字！');
						$('.formError-'+id).show();
					}
				}else if($(this).data('checktype')==4){
					//校验是否为n位的数字（依赖length参数）
					var length = $(this).data('length');
					if(length!=undefined){
						var re = new RegExp('^\\d{'+length+'}$');//n位纯数字正则表达式
						if(re.test(value)){
							$(this).data('state',1);
							$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
							$('.errorText-'+id).html('');
							$('.formError-'+id).hide();
						}else{
							$(this).data('state',3);
							$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
							$('.errorText-'+id).html($(this).data('title')+'不是'+length+'位纯数字！');
							$('.formError-'+id).show();
						}
					}else{
						//校验是否为纯数字
						var re = /^[0-9]*$/;//纯数字正则表达式
						if(re.test(value)){
							$(this).data('state',1);
							$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
							$('.errorText-'+id).html('');
							$('.formError-'+id).hide();
						}else{
							$(this).data('state',3);
							$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
							$('.errorText-'+id).html($(this).data('title')+'不是纯数字！');
							$('.formError-'+id).show();
						}
					}
				}else if($(this).data('checktype')==5){
					//校验是否为至少n位数字（依赖length参数）
					var length = $(this).data('length');
					if(length!=undefined){
						//var re = /^\d{length,}$/;//至少n位数字正则表达式
						var re = new RegExp('^\\d{'+length+',}$');//至少n位数字正则表达式
						if(re.test(value)){
							$(this).data('state',1);
							$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
							$('.errorText-'+id).html('');
							$('.formError-'+id).hide();
						}else{
							$(this).data('state',3);
							$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
							$('.errorText-'+id).html($(this).data('title')+'不得少于'+length+'位纯数字！');
							$('.formError-'+id).show();
						}
					}else{
						//校验是否为纯数字
						var re = /^[0-9]*$/;//纯数字正则表达式
						if(re.test(value)){
							$(this).data('state',1);
							$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
							$('.errorText-'+id).html('');
							$('.formError-'+id).hide();
						}else{
							$(this).data('state',3);
							$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
							$('.errorText-'+id).html($(this).data('title')+'不是纯数字！');
							$('.formError-'+id).show();
						}
					}
				}else if($(this).data('checktype')==6){
					//校验是否为m-n位的数字（依赖slength、olength参数）
					var slength = $(this).data('slength');
					var olength = $(this).data('olength');
					if(slength!=undefined && olength!=undefined){
						//var re = /^\d{slength,olength}$/;//m-n位的数字正则表达式
						var re = new RegExp('^\\d{'+slength+','+olength+'}$');//m-n位的数字正则表达式
						if(re.test(value)){
							$(this).data('state',1);
							$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
							$('.errorText-'+id).html('');
							$('.formError-'+id).hide();
						}else{
							$(this).data('state',3);
							$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
							$('.errorText-'+id).html($(this).data('title')+'不得少于'+slength+'位或多于'+olength+'位纯数字！');
							$('.formError-'+id).show();
						}
					}else{
						//校验是否为纯数字
						var re = /^[0-9]*$/;//纯数字正则表达式
						if(re.test(value)){
							$(this).data('state',1);
							$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
							$('.errorText-'+id).html('');
							$('.formError-'+id).hide();
						}else{
							$(this).data('state',3);
							$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
							$('.errorText-'+id).html($(this).data('title')+'不是纯数字！');
							$('.formError-'+id).show();
						}
					}
				}else if($(this).data('checktype')==7){
					//校验是否为有两位小数的正实数 
					var re = /^[0-9]+(.[0-9]{2})?$/;//有两位小数的正实数正则表达式
					if(re.test(value)){
						$(this).data('state',1);
						$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
						$('.errorText-'+id).html('');
						$('.formError-'+id).hide();
					}else{
						$(this).data('state',3);
						$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
						$('.errorText-'+id).html($(this).data('title')+'格式非法！');
						$('.formError-'+id).show();
					}
				}else if($(this).data('checktype')==8){
					//校验用户存在类型非法时，默认校验用户存在
					if($(this).data('useronly')!=1 && $(this).data('useronly')!=2 ){
						$(this).data('useronly',1);
					}
					//校验用户类型非法时，默认校验全部用户
					if($(this).data('userchecktype')!=1 && $(this).data('userchecktype')!=2 && $(this).data('userchecktype')!=3 && $(this).data('userchecktype')!=4 && $(this).data('userchecktype')!=5 && $(this).data('userchecktype')!=6 && $(this).data('userchecktype')!=7){
						$(this).data('userchecktype',7);
					}
					//校验手机号或邮箱时进行校验
					if($(this).data('userchecktype') == 2 || $(this).data('userchecktype') == 3){
						if($(this).data('userchecktype') == 2){
							//校验手机
							//校验是否为手机号
							//校验手机号是否为11位
							var re = /^1\d{10}$/;//手机正则表达式
							if(re.test(value)){
								//进行唯一校验
								//校验是否为有两位小数的正实数 
								if($(this).data('useronly')==1){
									$.ajax({
										// 地址
										url:WEBURL+"/User/registerUserOnly",
										//传送方式
										type:'post',
										//数据类型
										dataType:'json',
										//数据
										data: "value="+value+"&type="+$(this).data('userchecktype'),
										//成功执行函数
										success:function(data){
											if(data){
												if(data.status!=1){
													input.data('state',3);
													input.css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
													$('.errorText-'+id).html('账户不存在！');
													$('.formError-'+id).show();
												}else{
													input.data('state',1);
													input.css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
													$('.errorText-'+id).html('');
													$('.formError-'+id).hide();
												}
											}
										}
									});
								}else{
									$.ajax({
										// 地址
										url:WEBURL+"/User/registerUserOnly",
										//传送方式
										type:'post',
										//数据类型
										dataType:'json',
										//数据
										data: "value="+value+"&type="+$(this).data('userchecktype'),
										//成功执行函数
										success:function(data){
											if(data){
												if(data.status!=5){
													input.data('state',3);
													input.css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
													$('.errorText-'+id).html('账户存在！');
													$('.formError-'+id).show();
												}else{
													input.data('state',1);
													input.css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
													$('.errorText-'+id).html('');
													$('.formError-'+id).hide();
												}
											}
										}
									});
								}
							}else{
								$(this).data('state',3);
								$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
								$('.errorText-'+id).html('手机号格式错误！');
								$('.formError-'+id).show();
							}
						}else{
							//校验邮箱
							var re = /^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/;//邮箱正则表达式
							if(re.test(value)){
								//进行唯一校验
								//校验是否为有两位小数的正实数 
								if($(this).data('useronly')==1){
									$.ajax({
										// 地址
										url:WEBURL+"/User/registerUserOnly",
										//传送方式
										type:'post',
										//数据类型
										dataType:'json',
										//数据
										data: "value="+value+"&type="+$(this).data('userchecktype'),
										//成功执行函数
										success:function(data){
											if(data){
												if(data.status!=1){
													input.data('state',3);
													input.css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
													$('.errorText-'+id).html('账户不存在！');
													$('.formError-'+id).show();
												}else{
													input.data('state',1);
													input.css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
													$('.errorText-'+id).html('');
													$('.formError-'+id).hide();
												}
											}
										}
									});
								}else{
									$.ajax({
										// 地址
										url:WEBURL+"/User/registerUserOnly",
										//传送方式
										type:'post',
										//数据类型
										dataType:'json',
										//数据
										data: "value="+value+"&type="+$(this).data('userchecktype'),
										//成功执行函数
										success:function(data){
											if(data){
												if(data.status!=5){
													input.data('state',3);
													input.css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
													$('.errorText-'+id).html('账户存在！');
													$('.formError-'+id).show();
												}else{
													input.data('state',1);
													input.css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
													$('.errorText-'+id).html('');
													$('.formError-'+id).hide();
												}
											}
										}
									});
								}
							}else{
								$(this).data('state',3);
								$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
								$('.errorText-'+id).html('邮箱格式错误！');
								$('.formError-'+id).show();
							}
						}
					}else{
						//校验是否为有两位小数的正实数 
						if($(this).data('useronly')==1){
							$.ajax({
								// 地址
								url:WEBURL+"/User/registerUserOnly",
								//传送方式
								type:'post',
								//数据类型
								dataType:'json',
								//数据
								data: "value="+value+"&type="+$(this).data('userchecktype'),
								//成功执行函数
								success:function(data){
									if(data){
										if(data.status!=1){
											input.data('state',3);
											input.css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
											$('.errorText-'+id).html('账户不存在！');
											$('.formError-'+id).show();
										}else{
											input.data('state',1);
											input.css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
											$('.errorText-'+id).html('');
											$('.formError-'+id).hide();
										}
									}
								}
							});
						}else{
							$.ajax({
								// 地址
								url:WEBURL+"/User/registerUserOnly",
								//传送方式
								type:'post',
								//数据类型
								dataType:'json',
								//数据
								data: "value="+value+"&type="+$(this).data('userchecktype'),
								//成功执行函数
								success:function(data){
									if(data){
										if(data.status!=5){
											input.data('state',3);
											input.css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
											$('.errorText-'+id).html('账户存在！');
											$('.formError-'+id).show();
										}else{
											input.data('state',1);
											input.css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
											$('.errorText-'+id).html('');
											$('.formError-'+id).hide();
										}
									}
								}
							});
						}
					}
				}else if($(this).data('checktype')==9){
					//校验密码
					//校验密码长度范围不存在时给予默认值
					if($(this).data('slength')==undefined || $(this).data('olength')==undefined ){
						$(this).data('slength',6);
						$(this).data('olength',18);
					}
					var slength = $(this).data('slength');
					var olength = $(this).data('olength');
					slength = slength-1;
					olength = olength-1;
					//校验手机号是否为11位
					//var re = /^[a-zA-Z]\w{5,17}$/;//以字母开头，长度在6-18之间，只能包含字符、数字和下划线。正则表达式
					var re = new RegExp('^[a-zA-Z]\\w{'+slength+','+olength+'}$');//m-n位的数字正则表达式
					if(re.test(value)){
						$(this).data('state',1);
						$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
						$('.errorText-'+id).html('');
						$('.formError-'+id).hide();
					}else{
						$(this).data('state',3);
						$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
						$('.errorText-'+id).html('密码格式错误！以字母开头，长度在'+$(this).data('slength')+'-'+$(this).data('olength')+'之间，只能包含字符、数字和下划线。');
						$('.formError-'+id).show();
					}
				}else if($(this).data('checktype')==10){
					//校验重复密码
					if($('#'+$(this).data('passid')).val()){
						if($('#'+$(this).data('passid')).val()==value){
							$(this).data('state',1);
							$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
							$('.errorText-'+id).html('');
							$('.formError-'+id).hide();
						}else{
							$(this).data('state',3);
							$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
							$('.errorText-'+id).html('密码不一致！');
							$('.formError-'+id).show();
						}
					}else{
						$(this).data('state',1);
						$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
						$('.errorText-'+id).html('');
						$('.formError-'+id).hide();
					}
				}else if($(this).data('checktype')==11){
					//校验验证码
					if($(this).data('verifysign')!=undefined){
						$.ajax({
							// 地址
							url:WEBURL+"/Common/verifyCheck",
							//传送方式
							type:'post',
							//数据类型
							dataType:'json',
							//数据
							data: "verify="+value+"&sign="+$(this).data('verifysign'),
							//成功执行函数
							success:function(data){
								if(data){
									if(data.status!=1){
										input.data('state',3);
										input.css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
										$('.errorText-'+id).html('验证码错误！');
										$('.formError-'+id).show();
									}else{
										input.data('state',1);
										input.css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
										$('.errorText-'+id).html('');
										$('.formError-'+id).hide();
									}
								}
							}
						});
					}else{
						$(this).data('state',1);
						$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
						$('.errorText-'+id).html('');
						$('.formError-'+id).hide();
					}
				}
			}else{
				//只需要校验是否为空其他都不需要
				$(this).data('state',1);
				$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
				$('.errorText-'+id).html('');
				$('.formError-'+id).hide();
			}
			//为空报错
			
		}else{
			$(this).data('state',3);
			$(this).css({ "border": "1px solid #ff6569", "box-shadow": "0 0 2px #fda3a5" });
			$('.errorText-'+id).html($(this).data('title')+'不能为空！');
			$('.formError-'+id).show();
		}
	}else{
		//不需要任何校验
		$(this).data('state',1);
		$(this).css({ "border": "1px solid #a0a0a0", "box-shadow": "none" });
		$('.errorText-'+id).html('');
		$('.formError-'+id).hide();
	}
});
//验证码点击获取
//验证码切换
/* $('.verificationPic').click(function(){
	var sign = $(this).data('sign');
	$('.verificationPic').attr("src",WEBURL+"/Common/verifyCreate/sign/"+sign+"/sj/"+parseInt(10*Math.random()));
}); */
//短信发送
$('.PhoneSmsVerifyBtn').click(function(){
	if($(this).data('inputdataid')!=undefined && $(this).data('phoneid')!=undefined && $(this).data('sign')!=undefined){
		var id = $(this).data('inputdataid');
		//校验手机号输入框的状态
		if($('#'+$(this).data('phoneid')).data('state')==1){
			//校验手机号合法性
			var phone = $('#'+$(this).data('phoneid')).val();
			var time = $('#'+$(this).data('phoneid')).val();
			if($(this).data('time')==undefined){
				var time = 60;
			}else{
				var time = $(this).data('time');
			}
			//校验是否为手机号
			//校验手机号是否为11位
			var re = /^1\d{10}$/;//手机正则表达式
			if(re.test(phone)){
				$('.errorText-'+id).html('');
				$('.formError-'+id).hide();
				//执行发送短信请求
				$.ajax({
					// 地址
					url:WEBURL+"/Common/verifySMSCreateSend",
					//传送方式
					type:'post',
					//数据类型
					dataType:'json',
					//数据
					data: "phone="+phone+"&sign="+$(this).data('sign'),
					//成功执行函数
					success:function(data){
						if(data){
							if(data.status==1){
								$(this).data('state',1);
								PhoneSMSBtnTime(time);
							}else{
								$(this).data('state',3);
							}
						}
					}
				});
			}else{
				$('.errorText-'+id).html('手机号格式非法！');
				$('.formError-'+id).show();
			}
		}else{
			$('.errorText-'+id).html('手机号校验失败！');
			$('.formError-'+id).show();
		}
	}
});
//发送手机短信定时
function PhoneSMSBtnTime(times){
	setTimeout(function(){
		if(times == 0){
			//解除锁定Button按钮
			$(".PhoneSmsVerifyBtn").val("获取短信验证码");
			$(".PhoneSmsVerifyBtn").removeAttr("disabled");
		}else{
			//锁定Button按钮
			$(".PhoneSmsVerifyBtn").attr("disabled","disabled");
			$(".PhoneSmsVerifyBtn").val(times+"秒后重新发送");
			times--;
			PhoneSMSBtnTime(times);
		}
		
	},1000);	
}
//提交校验各表单项状态 表单项通过校验返回TRUE 表单项未通过校验返回FALSE
function inputsubmit(){
	var state = 1;
	$('.inputTxt').each(function(){
		//判断是否为错误状态，如果不是错误状态，则执行灰色边框初始化
		if($(this).data('state')!=1){
			state = 2;
		}
	});
	if(state ==1){
		return true;
	}else{
		return false;
	}
}

//================================省市县联动公共类==================================================
//初始化选择省市县数据
//获取省id
var  provinceSelect = $("#bLink").data("pid");
//获取市id
var  citySelect = $("#bLink").data("cid");
//获取区id
var  areaSelect = $("#bLink").data("aid");

$("#"+provinceSelect).change(function(){
	//更新缓存
	$(this).data('id',$(this).val());
	//执行选中
	//市和区执行初始化
	$("#"+citySelect).removeData("id");
	$("#"+areaSelect).removeData("id");
	$("#"+citySelect).html("<option>--请选择城市--</option>");
	$("#"+areaSelect).html("<option>--请选择区域--</option>");
	city(citySelect);	
});

$("#"+citySelect).change(function(){
	//更新缓存
	$(this).data('id',$(this).val());
	//执行选中
	//市和区执行初始化
	$("#"+areaSelect).data('id',null);
	$("#"+areaSelect).html("<option>--请选择区域--</option>");
	area(areaSelect);	
});
$("#"+areaSelect).change(function(){
	//更新缓存
	$(this).data('id',$(this).val());
});
//创建省信息下拉列表
province(provinceSelect);
//获取省信息的方法
function province(provinceName){
	var provinceId = $("#"+provinceName);
	$.ajax({
		url:WEBURL+'/Common/CityList',
		type:'post',
		data:'pid=6'+'&level=2',
		success:function(data){
			if(data.status == 1){
				for(var i in data.citylist){
					var provinceSelectId = provinceId.data("id");
					if(data.citylist[i].id == provinceSelectId){
						provinceId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"' selected>"+data.citylist[i].name+"</option>"
						);
					}else{
						provinceId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"'>"+data.citylist[i].name+"</option>"
						);
					}
				}
			}
		}
	});	
	//创建市信息下拉列表
	if($("#"+provinceName).data('id')>0){
		city(citySelect);
	}
}
//获取市信息的方法
function city(cityName){
	var provinceId = $("#"+provinceSelect).data('id');
	var cityId = $("#"+cityName);
	$.ajax({
		url:WEBURL+'/Common/CityList',
		type:'post',
		data:'pid='+provinceId+'&level=3',
		success:function(data){
			if(data.status == 1){
				for(var i in data.citylist){
					var citySelectId = cityId.data("id");
					if(data.citylist[i].id == citySelectId){
						cityId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"' selected>"+data.citylist[i].name+"</option>"
						);
					}else{
						cityId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"'>"+data.citylist[i].name+"</option>"
						);
					}
				}
			}
		}
	});	
	//创建区域信息下拉列表
	if($("#"+cityName).data('id')>0){
		area(areaSelect);
	}
}
//获取区域信息的方法
function area(areaName){
	var cityId = $("#"+citySelect).data('id');
	var areaId = $("#"+areaName);
	$.ajax({
		url:WEBURL+'/Common/CityList',
		type:'post',
		data:'pid='+cityId+'&level=4',
		success:function(data){
			if(data.status == 1){
				for(var i in data.citylist){
					var areaSelectId = areaId.data("id");
					if(data.citylist[i].id == areaSelectId){
						areaId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"' selected>"+data.citylist[i].name+"</option>"
						);
					}else{
						areaId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"'>"+data.citylist[i].name+"</option>"
						);
					}
				}
			}
		}
	});	
}
//================================省市县联动公共类==================================================