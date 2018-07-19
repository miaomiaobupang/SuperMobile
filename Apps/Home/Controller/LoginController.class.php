<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UploadFileModel as UploadFile;
use Home\Model\LogFileModel as LogFile;
// +----------------------------------------------------------------------
// | 超级医生WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://hopenoah.com All rights reserved.
// +----------------------------------------------------------------------
// | 词条控制器
// +----------------------------------------------------------------------
// | Author: Davin <yulong@hopenoah.com>
// +----------------------------------------------------------------------
class LoginController extends LimitController {
	private $user;//用户表
	private $logM;//日志类
	private $log_user;//日志用户表
	private $user_device;//用户设备表
	private $sign_record;//签到记录表
	private $school_auth;//学校认证表
	private $school;//学校表
	private $Verifys;//验证码类
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		//实例化用户表
		$this->user     = D('user');
		$this->log_user     = D('log_user');
		//实例化日志类
		$this->logM = new LogFile();
		//实例化//用户设备表
		$this->user_device     = D('user_device');
		//实例化用户密码找回表
		$this->userpass_find   = D('userpass_find');
		//实例化签到记录表
		$this->sign_record     = D('sign_record');
		//实例化学校认证表
		$this->school_auth     = D('school_auth');
		//实例化学校表
		$this->school     = D('school');
		$this->Verifys = new \Think\Verify();
		//判断是否登录
		if($_SESSION['userInfo']){
			$this->redirect('Member/index');
		}
		//初始化redis缓存
		$this->redis = S(array());
	}
	public function index(){
		//加载内容页相关JS模板文件
		$this->assign("Btitle","会员登录");
		$url=$_SERVER['HTTP_REFERER'];
		if(strrpos($url,"Member/updatePass")){
			$url=C('WEBURL')."/Member/index";
		}
		$this->assign("sUrl",$url);
		$this->assign("Js","Login/indexjs");
		$this->display();
	}
	
	public function findPass(){
		$type = $_GET['type'];
		$checkStr = $_GET['checkStr'];
		$this->assign("Js","Login/findpassjs");
		if($type){
			if($type == 1){
				$this->assign("Btitle","找回密码");
				$this->display('Login/findPass1');
			}else if($type == 2){
				$name = I('name','');
				if(!$name){
					$this->redirect('Login/findPass',array('type' => 1));
				}
				$userInfo=$this->user->where("uname='".$name."' or uphone='".$name."' or uemail='".$name."'")->find();
				if(!$userInfo){
					//没有查询到相关信息，重定向
					$this->redirect('Login/findPass',array('type' => 1));
				}
				
				if($userInfo['isphone']==1 or $userInfo['isemail']==1){
					if($userInfo['isphone']==1){
						//准备相关SESSION
						$data=null;
						$data['phone']=$userInfo['uphone'];
						$data['uid']=$userInfo['uid'];
						$data['ctime']=time();
						$data['state']=1;
						session('#phonefindpass',$data);
						//处理相关字段
						$this->assign("phonem",substr_replace($userInfo['uphone'],'****',3,4));
					}
					if($userInfo['isemail']==1){
						/*准备相关SESSION
						$data=null;
						$data['email']=$userInfo['uemail'];
						$data['uid']=$userInfo['uid'];
						$data['ctime']=time();
						$data['state']=1;
						session('#emailfindpass',$data);
						*/
						//dump($userInfo);
						//初始化redis缓存修改密码数据
						$this->redis->set($userInfo['email'].$userInfo['uid'],$userInfo['uid'].'#'.'1');
						//设置redis缓存时间
						$this->redis->EXPIRE($userInfo['email'].$userInfo['uid'], 86400);  //设置24小时后过期
						//处理相关字段
						$this->assign("emailm",substr_replace($userInfo['uemail'],'****',2,4));
					}
					$this->assign("username",$userInfo['nickname']);
					$this->assign("phoneState",$userInfo['isphone']);
					$this->assign("emailState",$userInfo['isemail']);
					//准备邮件数据
					$info['emailUid'] = $userInfo['uemail'].$userInfo['uid'];
					$info['email'] = $userInfo['uemail'];
					$this->assign("info",$info);
				}else{
					//没有查询,重定向
					$this->redirect('Login/findPass',array('type' => 1));
				}
				//查询到相关信息
				$this->display('Login/findPass2');
			}else if($type == 3){
				 //手机设置密码
				$phonefindpass=session('#phonefindpass');
				$phone =$phonefindpass['phone'];
				if($phone){
					$sign ='100110301';
					//校验校验码状态
					
					if(session('?'.$phone.'#100110301')){
						$Sessiondata=null;
						$Sessiondata=session($phone.'#100110301');
						if(time()-C('VERIFY_SMS_USER_FINDPASS_TIME') > $Sessiondata['ctime']){
							//超出验证码有效期
							//没有查询,重定向
							$this->redirect('Login/findPass',array('type' => 1));
						}
						if($Sessiondata['state']==1){
						//var_dump($_POST['pass']==$_POST['surepass']);exit();
							if($_POST['pass'] and $_POST['surepass']){
								if($_POST['pass']==$_POST['surepass']){
									//执行密码修改
									$state=$this->user-> where('uid='.$Sessiondata['uid'])->setField('upass',md5($_POST['pass']));
									if($state){
										//更新SESSION状态
										$data['state']=2;
										session($phone.'#100110301',$data);
										//记录手机密码找回成功日志
										$types=null;
										$types['typefid']=1001103;
										$types['typeid']=100110303;
										$types['title']="使用手机号码".$phone."找回用户ID为".$Sessiondata['uid']."的密码成功";
										$this->logM->logAdd($types);
										$this->redirect('Login/findPass',array('type' => 5));
									}else{
										$this->error('密码找回失败');
									}
								}else{
									$this->display('Login/findPass3');
								}
							}else{
								$this->display('Login/findPass3');
							}
						}else{
							//没有查询,重定向
							$this->redirect('Login/findPass',array('type' => 1));
						}
					}else{
						//没有查询,重定向
						$this->redirect('Login/findPass',array('type' => 1));
					}
				}else{
					//没有查询,重定向
					$this->redirect('Login/findPass',array('type' => 1));
				}
			}else if($type == 4){
				//准备redis缓存的数据
				$emailInfo = $this->redis->get($checkStr);
				//dump($emailInfo);die();
				//$emailfindpass=session('#emailfindpass');
				//$email =$emailfindpass['email'];
				if($emailInfo){
					$sign ='100110302';
					//校验校验码状态
					//执行存储验证
					//$emails=str_replace('.', '',$email);
					if($emailInfo){
						/*
						$Sessiondata=null;
						$Sessiondata=session($emails.'#100110302');
						if(time()-C('VERIFY_EMAIL_USER_FINDPASS_TIME') > $Sessiondata['ctime']){
							//超出验证码有效期
							//没有查询,重定向
							$this->redirect('Login/findPass',array('type' => 1));
						}
						*/
						//var_dump($Sessiondata);
						//准备redis字符串数据
						$emailRedis = explode('#',$emailInfo);
						//dump($emailRedis);
						if($emailRedis[1] == 1){
							if($_POST['pass'] and $_POST['surepass']){
								if($_POST['pass']==$_POST['surepass']){
									//执行密码修改
									$state=$this->user-> where('uid='.$emailRedis[2])->setField('upass',md5($_POST['pass']));
									//dump($emailRedis[2]);
									//dump($state);
									if($state){
										//更新SESSION状态
										//$data['state']=2;
										//session($emails.'#100110302',$data);
										//记录手机密码找回成功日志
										$types=null;
										$types['typefid']=1001103;
										$types['typeid']=100110304;
										$types['title']="使用邮箱".$emailRedis[0]."找回用户ID为".$emailRedis[2]."的密码成功";
										$this->logM->logAdd($types);
										$this->redirect('Login/findPass',array('type' => 6,'checkStr'=>$checkStr));
									}else{
										$this->error('密码找回失败');
									}
								}else{
									$this->display('Login/findPass3');
								}
							}else{
								$this->display('Login/findPass3');
							}
						}else{
							//没有查询,重定向
							$this->redirect('Login/findPass',array('type' => 1));
						}
					}else{
						//没有查询,重定向
						$this->redirect('Login/findPass',array('type' => 1));
					}
				}else{
					//没有查询,重定向
					$this->redirect('Login/findPass',array('type' => 1));
				}
			}else if($type == 5){
				 //手机设置密码
				$phonefindpass=session('#phonefindpass');
				$phone =$phonefindpass['phone'];
				if($phone){
					if(session('?'.$phone.'#100110301')){
						$Sessiondata=null;
						$Sessiondata=session($phone.'#100110301');
						if($Sessiondata['state']!=1){
							session('#phonefindpass',null);
							$this->display('Login/findPass4');
						}else{
							//没有查询,重定向
							$this->redirect('Login/findPass',array('type' => 1));
						}
					}else{
						//没有查询,重定向
						$this->redirect('Login/findPass',array('type' => 1));
					}
				}else{
					//没有查询,重定向
					$this->redirect('Login/findPass',array('type' => 1));
				}
			}else if($type == 6){
				//准备redis缓存的数据
				$emailInfo = $this->redis->get($checkStr);
				$emailRedis = explode('#',$emailInfo);
				
				//$emailfindpass=session('#emailfindpass');
				//email =$emailfindpass['email'];
				//$email =$emailRedis[0];
				//dump($emailRedis);die();
				if($emailInfo){
					//$Sessiondata=null;
					//$Sessiondata=session($emails.'#100110302');
					if($emailRedis[1] == 1){
						//删除redis缓存
						$this->redis->del($checkStr);
						$this->display('Login/findPass4');
					}else{
						//没有查询,重定向
						$this->redirect('Login/findPass',array('type' => 1));
					}
				}else{
					//没有查询,重定向
					$this->redirect('Login/findPass',array('type' => 1));
				}
			}
		}else{
		
		}
	}
	//接收注册会员
	public function regPhoneUser(){
		$phone = I('phone','');  
		if(session('?'.$phone.'#100110102')){
			$Sessiondata=null;
			$Sessiondata=session($phone.'#100110102');
			if($Sessiondata['state']!=1){
				//没有查询,重定向
				$this->redirect('Login/reg');
			}else{
				//验证通过检测手机是否已注册
				$users = $this->user->where("uphone='".$phone."'")->find();
				if($users){
					//没有查询,重定向
					$this->redirect('Login/reg');
				}else{
					//接收密码
					$pass = I('pass','');
					$surepass = I('surepass','');
					if($pass!=$surepass){
						//没有查询,重定向
						$this->redirect('Login/reg');
					}else{
						//接收数据执行注册
						//准备数据执行用户注册
						$data=null;
						$data['uphone']=$phone;
						$data['upass']=md5($pass);
						$data['nickname']=substr_replace($phone,'****',3,4)."@phone";
						$data['headimg']='/Public/huiyuan.png';
						$data['ctime']=time();
						$data['ctype']=1;
						$data['isphone']=1;
						$usernewId=$this->user->add($data);
						if($usernewId){
							//注册成功
							//销毁注册SESSION信息
							session($phone.'#100110102',null);
							//更新用户日志信息
							$loguser=D('log_user');
							//查询当前IP是否有相关用户信息
							$loguseryinfo=$loguser->where("uip='".$_SERVER['REMOTE_ADDR']."' and utype=1")->find();
							if($loguseryinfo){
								//有记录,修改日志用户信息执行绑定用户ID
								$loguser-> where('id='.$loguseryinfo['id'])->setField('uid',$usernewId);
								$loguserInfo=$loguser->where('id='.$loguseryinfo['id'])->find();
							}else{
								//没有记录,执行创建
								$data=null;
								$data['uid']=$usernewId;
								$data['utype']=2;
								$data['uip']=$_SERVER['REMOTE_ADDR'];
								$data['utmie']=time();
								$data['ctime']=time();
								$data['state']=1;
								$loguserNid=$loguser->add($data);
								$loguserInfo=$loguser->where('id='.$loguserNid)->find();
							}
							//获取访问者信息
							$visitor=find_visitor_info();
							$loguserInfo['browseragent']=$visitor['browseragent'];
							$loguserInfo['browserversion']=$visitor['browserversion'];
							$loguserInfo['browserplatform']=$visitor['browserplatform'];
							$loguserInfo['ip']=$visitor['ip'];
							$loguserInfo['country']=$visitor['country'];
							$loguserInfo['province']=$visitor['province'];
							$loguserInfo['city']=$visitor['city'];
							$loguserInfo['district']=$visitor['district'];
							$loguserInfo['carrier']=$visitor['carrier'];
							session('loguser',$loguserInfo);
							//记录注册日志
							$types=null;
							$types['typefid']=1001101;
							$types['typeid']=100110101;
							$types['title']="手机用户".$phone."成功注册，用户ID为".$usernewId;
							$this->logM->logAdd($types);
							//记录手机认证日志
							$types=null;
							$types['typefid']=1001104;
							$types['typeid']=100110401;
							$types['title']="用户ID为".$usernewId."使用手机号".$phone."进行手机认证成功";
							$this->logM->logAdd($types);
							//获取用户信息
							$userInfo=$this->user->where('uid='.$usernewId)->find();
							if($userInfo){
								//处理头像
								//头像地址补全
								$userInfo['headimg']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$userInfo['headimg'];
								//储存用户信息
								session('userInfo',$userInfo);
							}
							
							//记录登录日志
							$types=null;
							$types['typefid']=1001102;
							$types['typeid']=100110201;
							$types['title']="用户ID为".$usernewId."成功使用手机号".$phone."登录";
							$this->logM->logAdd($types);
							//注销日志用户SESSION执行更新
							session('loguser',null);
							//跳转至注册成功页
							//跳转至会员中心
							$this->redirect('Member/index');
						}
					}
				}
			}
		}else{
			//没有查询,重定向
			$this->redirect('Login/reg');
		}
	}
	//邮箱注册
	public function regEmailUser(){
		if(I('password')){
			//执行注册新增
			$email =$_GET['email']; 
			$pass =I('password',''); 
			$verify =$_GET['verify']; 
			if(empty($email)){
				$this->error('缺少邮箱');
			}
			//处理邮箱的点，SESSION的名不能含有点
			//$emails=str_replace('.', '',$email);
			if(empty($pass)){
				$this->error('缺少密码');
			}
			
			//准备并校验缓存数据是否存在
			$redisEmail = $this->redis->get($email.'#100110103#'.$verify);
			if($redisEmail){
				//$Sessiondata=null;
				if($redisEmail == 1){
					//验证通过检测邮箱是否已注册
					$users = $this->user->where("uemail='".$email."'")->find();
					if($users){
						//该邮箱已注册
						$this->error('该邮箱号已注册');
					}
					//准备数据执行用户注册
					$data=null;
					$data['uemail']=$email;
					$data['upass']=md5($upass);
					$data['nickname']=substr_replace($email,'****',2,4)."@email";
					$data['headimg']='/Public/huiyuan.png';
					$data['ctime']=time();
					$data['ctype']=2;
					$data['isemail']=1;
					$usernewId=$this->user->add($data);
					if($usernewId){
						//注册成功
						//销毁邮箱注册缓存的Redis信息
						$this->redis->del($email.'#100110103#'.$verify);
						//更新用户日志信息
						$loguser=D('log_user');
						//查询当前IP是否有相关用户信息
						$loguseryinfo=$loguser->where("uip='".$_SERVER['REMOTE_ADDR']."' and utype=1")->find();
						if($loguseryinfo){
							//有记录,修改日志用户信息执行绑定用户ID
							$loguser-> where('id='.$loguseryinfo['id'])->setField('uid',$usernewId);
							$loguserInfo=$loguser->where('id='.$loguseryinfo['id'])->find();
						}else{
							//没有记录,执行创建
							$data=null;
							$data['uid']=$usernewId;
							$data['utype']=2;
							$data['uip']=$_SERVER['REMOTE_ADDR'];
							$data['utmie']=time();
							$data['ctime']=time();
							$data['state']=1;
							$loguserNid=$loguser->add($data);
							$loguserInfo=$loguser->where('id='.$loguserNid)->find();
						}
						//获取访问者信息
						$visitor=find_visitor_info();
						$loguserInfo['browseragent']=$visitor['browseragent'];
						$loguserInfo['browserversion']=$visitor['browserversion'];
						$loguserInfo['browserplatform']=$visitor['browserplatform'];
						$loguserInfo['ip']=$visitor['ip'];
						$loguserInfo['country']=$visitor['country'];
						$loguserInfo['province']=$visitor['province'];
						$loguserInfo['city']=$visitor['city'];
						$loguserInfo['district']=$visitor['district'];
						$loguserInfo['carrier']=$visitor['carrier'];
						session('loguser',$loguserInfo);
						//记录注册日志
						$types=null;
						$types['typefid']=1001101;
						$types['typeid']=100110102;
						$types['title']="邮箱用户".$email."成功注册，用户ID为".$usernewId;
						$this->logM->logAdd($types);
						//记录邮箱认证日志
						$types=null;
						$types['typefid']=1001104;
						$types['typeid']=100110402;
						$types['title']="用户ID为".$usernewId."使用邮箱帐号".$email."进行邮箱认证成功";
						$this->logM->logAdd($types);
						//获取用户信息
						$userInfo=$this->user->where('uid='.$usernewId)->find();
						if($userInfo){
							//处理头像
							//头像地址补全
							$userInfo['headimg']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$userInfo['headimg'];
							//储存用户信息
							session('userInfo',$userInfo);
						}
						
						//记录登录日志
						$types=null;
						$types['typefid']=1001102;
						$types['typeid']=100110201;
						$types['title']="用户ID为".$usernewId."成功使用邮箱".$email."登录";
						$this->logM->logAdd($types);
						//跳转至会员中心
						$this->redirect('Member/index');
						//注销日志用户SESSION执行更新
						session('loguser',null);
					}
				}else{
					$this->error('邮箱验证未通过');
				}
			}else{
				$this->error('邮箱非法');
			}
		}else{
			//执行邮箱验证
			$sign = '100110103'; 
			$verify =I('verify',''); 
			$isSession =1; 
			$email =I('email','');
			if(empty($verify)){
				$this->error('缺少验证码');
			}
			if(empty($email)){
				$this->error('缺少邮箱');
			}
			//校验验证码
			//$verifyState=$this->Verifys->check($verify,$sign);
			//准备缓存数据
			$redisEmail = $this->redis->get($email.'#100110103#'.$verify);
			if($redisEmail){
				//执行存储验证
				//处理邮箱的点，SESSION的名不能含有点
				//$emails=str_replace('.', '',$email);
				$this->redis->set($email.'#100110103#'.$verify,'1');
				//执行设置密码页展示（占位）
				$this->display('Login/email');
			}else{
				$this->error('验证码错误');
				//跳转至登录页面
				$this->redirect('Login/index');
				//session($phone.'#'.$sign,null);
			}
		}	
	}
	
	
	public function findPass2(){
		var_dump(C('WEBURL'));
		//var_dump();
		$this->display();
	}
	public function findPass3(){
		//var_dump();
		$this->display();
	}
	public function findPass4(){
		//var_dump();
		$this->display();
	}
	public function findPass5(){
		//var_dump();
		$this->display();
	}
	public function findPass6(){
		//var_dump();
		$this->display();
	}
}