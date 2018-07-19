<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UploadFileModel as UploadFile;
use Home\Model\LogFileModel as LogFile;
// +----------------------------------------------------------------------
// | 非速搜WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://fairso.com All rights reserved.
// +----------------------------------------------------------------------
// | 用户
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class UserController extends LimitController {
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
	}
	
	/*
	* 【展示页】注册页展示
	* 接收数据格式  无
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：用户密码找回链接申请成功；
		2：缺少登录名；
		3：该用户不存在；
		4：该用户电子邮箱缺失；
		5：用户校验码生成失败；
	}
	*/
	public function registerUserShow(){
		//var_dump();
		$url='http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php/Common/verifyCreate/sign/100110101';
		echo "<img src='".$url."' height='100px'>" ;
	}
	
	
	/*
	* 【功能接口】用户唯一校验
	* 接收数据格式  'value'=>待校验值,'type'=>校验类型（1用户名 2手机号 3邮箱 4用户名和手机号 5用户名和邮箱 6手机号和邮箱 7用户名，手机号和邮箱）
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：用户存在；
		2：缺少待校验值；
		3：缺少校验类型；
		4：校验类型非法；
		5：用户不存在；
	}
	*/
	public function registerUserOnly(){
		//var_dump();
		$value =I('value','');     
		if(empty($value)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少待校验值'
			);
			$this->ajaxReturn($output);
		}
		$type =I('type','');     
		if(empty($type)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少校验类型'
			);
			$this->ajaxReturn($output);
		}
		if($type < 1 or $type > 7){
			$output = array(
					'status' 	=>'4',
					'message'	=>'校验类型非法'
			);
			$this->ajaxReturn($output);
		}
		if($type==1){
			$where['uname']=$value;
		}else if($type==2){
			$where['uphone']=$value;
		}else if($type==3){
			$where['uemail']=$value;
		}else if($type==4){
			$where['uname']=$value;
			$where['uphone']=$value;
			$where['_logic'] = 'or';
		}else if($type==5){
			$where['uname']=$value;
			$where['uemail']=$value;
			$where['_logic'] = 'or';
		}else if($type==6){
			$where['uphone']=$value;
			$where['uemail']=$value;
			$where['_logic'] = 'or';
		}else if($type==7){
			$where['uname']=$value;
			$where['uphone']=$value;
			$where['uemail']=$value;
			$where['_logic'] = 'or';
		}
		$userlist =$this->user->where($where)->find();
		if($userlist){
			$output = array(
					'status' 	=>'1',
					'message'	=>'用户存在'
			);
		}else{
			$output = array(
					'status' 	=>'5',
					'message'	=>'用户不存在'
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 【功能接口】手机用户注册并直接登录
	* 接收数据格式  'phone'=>注册手机号,'pass'=>注册密码
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：注册成功并登录成功；
		2：缺少手机号；
		3：缺少密码；
		4：手机号非法；
		5：超出验证码有效期，请重新获取；
		6：手机号验证未通过；
		7：该手机号已注册；
	}
	*/
	public function registerPhone(){
		$phone =I('phone',''); 
		$pass =I('pass',''); 
		if(empty($phone)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少手机号'
			);
			$this->ajaxReturn($output);
		}
		if(empty($pass)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少密码'
			);
			$this->ajaxReturn($output);
		}
		if(session('?'.$phone.'#100110102')){
			$Sessiondata=null;
			$Sessiondata=session($phone.'#100110102');
			if(time()-180 > $Sessiondata['ctime']){
				//超出验证码有效期
				$output = array(
					'status' 	=>'5',
					'message'	=>'超出验证码有效期，请重新获取'
				);
				$this->ajaxReturn($output);
			}
			if($Sessiondata['state']==1){
				//验证通过检测手机是否已注册
				$users = $this->user->where("uphone='".$phone."'")->find();
				if($users){
					//该手机号已注册
					$output = array(
						'status' 	=>'7',
						'message'	=>'该手机号已注册'
					);
					$this->ajaxReturn($output);
				}
				//准备数据执行用户注册
				$data=null;
				$data['uphone']=$phone;
				$data['upass']=md5($upass);
				$data['nickname']=$phone."@phone";
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
					//该手机号已注册
					$output = array(
						'status' 	=>'1',
						'message'	=>'注册成功并登录成功'
					);
					$this->ajaxReturn($output);
					//注销日志用户SESSION执行更新
					session('loguser',null);
				}
			}else{
				$output = array(
					'status' 	=>'6',
					'message'	=>'手机号验证未通过'
				);
				$this->ajaxReturn($output);
			}
		}else{
			$output = array(
				'status' 	=>'4',
				'message'	=>'手机号非法'
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	* 【功能接口】 发送邮箱注册验证邮件
	* 接收数据格式  'email'=>注册邮箱
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：注册成功并登录成功；
		2：缺少手机号；
		3：缺少密码；
		4：手机号非法；
		5：超出验证码有效期，请重新获取；
		6：手机号验证未通过；
		7：该手机号已注册；
	}
	*/
	public function registerEamilSend(){
		$email =I('email',''); 
		if(empty($email)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少注册邮箱'
			);
			$this->ajaxReturn($output);
		}
		//验证通过检测手机是否已注册
		$users = $this->user->where("uemail='".$email."'")->find();
		if($users){
			//该邮箱已注册
			$output = array(
				'status' 	=>'3',
				'message'	=>'该邮箱已注册'
			);
			$this->ajaxReturn($output);
		}
		//准备校验码
		$emailCode= $this->Verifys->entryMD5Text('100110103');
		sendMail($email,'欢迎注册非速搜展会网','请点击该链接完成注册'.$emailCode);
		$output = array(
			'status' 	=>'1',
			'message'	=>'发送成功'
		);
		$data=null;
		$data['email']=$email;
		$data['state']=2;
		$data['ctime']=time();
		//处理邮箱的点，SESSION的名不能含有点
		$emails=str_replace('.', '',$email);
		session($emails.'#100110103',$data);
		$this->ajaxReturn($output);
	}

	
	
	/*
	* 【展示页】邮件验证码(MD5)校验，并显示设置密码页
	* 1.校验验证码接收数据格式		'email'=>邮箱
	* 								'verify'=>验证码
	* 2.设置密码					'email'=>邮箱
	* 								'password'=>密码
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：验证码正确；
		2：缺少标识号；
		3：缺少验证码；
		4：验证码错误；
	}
	*/
	public function registerEamilCheck(){
		if(I('password')){
			//执行注册新增
			$email =I('email',''); 
			$pass =I('password',''); 
			if(empty($email)){
				$this->error('缺少邮箱');
			}
			//处理邮箱的点，SESSION的名不能含有点
			$emails=str_replace('.', '',$email);
			if(empty($pass)){
				$this->error('缺少密码');
			}
			//校验用户邮箱注册SESSION
			if(session('?'.$emails.'#100110103')){
				$Sessiondata=null;
				$Sessiondata=session($emails.'#100110103');
				if(time()-1800 > $Sessiondata['ctime']){
					$this->error('超出验证码有效期，请重新获取');
				}
				if($Sessiondata['state']==1){
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
					$data['nickname']=$email."@email";
					$data['headimg']='/Public/huiyuan.png';
					$data['ctime']=time();
					$data['ctype']=1;
					$data['isemail']=1;
					$usernewId=$this->user->add($data);
					if($usernewId){
						//注册成功
						//销毁邮箱注册SESSION信息
						session($emails.'#100110103',null);
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
						//该邮箱已注册
						$output = array(
							'status' 	=>'1',
							'message'	=>'注册成功并登录成功'
						);
						$this->ajaxReturn($output);
						//注销日志用户SESSION执行更新
						session('loguser',null);
					}
				}else{
					$output = array(
						'status' 	=>'6',
						'message'	=>'手机号验证未通过'
					);
					$this->ajaxReturn($output);
				}
			}else{
				$output = array(
					'status' 	=>'4',
					'message'	=>'手机号非法'
				);
				$this->ajaxReturn($output);
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
			$verifyState=$this->Verifys->check($verify,$sign);
			if($verifyState){
				$output = array(
						'status' 	=>'1',
						'message'	=>'验证码正确'
				);
				//执行存储验证
				//处理邮箱的点，SESSION的名不能含有点
				$emails=str_replace('.', '',$email);
				if(session('?'.$emails.'#'.$sign)){
					//存在行为,校验有效期
					$data=null;
					$data=session($emails.'#'.$sign);
					if(time()-1800 > $data['ctime']){
						//超出验证码有效期
						$this->error('超出验证码有效期，请重新获取');
					}
					//更新SESSION状态
					$data['state']=1;
					session($emails.'#'.$sign,$data);
					//执行设置密码页展示（占位）
				}else{
					$this->error('邮箱非法操作');
				}
			}else{
				$this->error('验证码错误');
				//session($phone.'#'.$sign,null);
			}
		}	
	}
	
	
	/*
	 * 【功能接口】用户登录
	 * 接收数据格式  'name'=>用户名/手机号/邮箱,'password'=>登录密码
	 * 返回数据格式
		 {
			 'status'=>状态,
			 'message'=>提示信息,
			 'info'=array(
				 'uid'=>用户ID,
				 'uname'=>用户名,
				 'uphone'=>用户手机号,
				 'uemail'=>用户邮箱,
				 'nickname'=>用户昵称,
				 'headimg'=>用户头像,
				 'address'=>地址,
				 'sex'=>性别（1男 2女 3保密）,
				 'birthday'=>出生年月日,
				 'birthdaymonthday'=>出生月日,
				 'score'=>用户积分,
				 'user_ispush'=>是否接受推送（1接受 2不接受）
				 'ctime'=>创建时间
				 'ctype'=>性别 1男 2女
				 'isphone'=>手机认证（1认证通过 2认证未通过）,
				 'isemail'=>邮箱认证（1认证通过 2认证未通过）,
				 'isname'=>实名认证（1认证通过 2认证未通过 3审核中）,
				 'role'=>角色（1游客 2普通会员 3商家用户 4后台管理员）,
				 'state'=>状态 1正常 2停用,
				)	 
		 }  
		status = {
			1：登陆成功；
			2：缺少登录名（用户名或手机号或邮箱）；
			3：缺少登录密码；
			4：用户不存在；
			5：用户密码错误；
		}
	 */
	public function login(){
		//用户名（手机号/邮箱）
		$name        = I('name','');
		//密码
		$password     = I('password','');
		//判断用户名为空
		if(empty($name)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少登录名（用户名或手机号或邮箱）'
			);
			$this->ajaxReturn($output);
		}
		//判断密码为空
		if(empty($password)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少登录密码'
			);
			$this->ajaxReturn($output);
		}
		$password=md5($password);
		//查询是否有该用户
		$userInfo=$this->user->where("(uname='".$name."' or uphone='".$name."' or uemail='".$name."') and state=1")->find();
		if($userInfo){
			//校验密码
			if($password==$userInfo['upass']){
				//在线登录状态
				//$this->user_device-> where('uid='.$userInfo['uid'])->setField('user_status','1');
				//销毁密码
				unset($userInfo['upass']);
				//头像地址补全
				$userInfo['headimg']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$userInfo['headimg'];
				//获取用户今天签到状态
				//$today=strtotime(date('Y-m-d',time()));
				//判断是否签到
				//$signState=$this->sign_record->where('today='.$today.' and uid='.$uid.' and state=1')->find();
				//if($signState){
					//已签到
				//	$userInfo['sign']=1;
				//}else{
					//未签到
				//	$userInfo['sign']=2;
				//}
				//判断用户角色状态，获取商家ID（占位）
				/* if($userInfo['role']==2){
					//查询校长信息
					$userInfo['school']=$this->school_auth->where('uid='.$userInfo['uid'].' and statecode=1 and state=1')->field('fid')->find();
					if($userInfo['school']){
						//查询学校是否存在
						$schoolState=$this->school->where('cid='.$userInfo['school']['fid'].' and state=1')->find();
						if($schoolState){
							$userInfo['school']=$userInfo['school']['fid'];
						}else{
							//获取失败，重置为普通用户
							$userInfo['role']="1";
							$this->user-> where('uid='.$userInfo['uid'])->setField('role','1');
							$userInfo['school']="0";
						}
					}else{
						//获取失败，重置为普通用户
						$userInfo['role']="1";
						$this->user-> where('uid='.$userInfo['uid'])->setField('role','1');
						$userInfo['school']="0";
					}
				}else{
					$userInfo['school']="0";
				} */
				//处理NULL
				foreach($userInfo as $k=>$v){
				   if($userInfo[$k]==null){
					   $userInfo[$k]="";
				   }
				}
				//储存用户信息
				session('userInfo',$userInfo);
				//记录登录日志
				$types=null;
				$types['typefid']=1001102;
				$types['typeid']=100110201;
				$types['title']="用户ID为".$userInfo['uid']."成功登录";
				$this->logM->logAdd($types);
				$output = array(
					'status' 	=>'1',
					'message'	=>'登陆成功',
					'info'		=>$userInfo
				);
			}else{
				$output = array(
					'status' 	=>'5',
					'message'	=>'用户密码错误'
				);		
			}
			$this->ajaxReturn($output);
		}else{
			$output = array(
					'status' 	=>'4',
					'message'	=>'用户不存在'
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	 *  用户退出
	 *	接收数据格式 无
	 *	返回数据格式 
		{
			'status'=>状态,
			'message'=>提示信息
		}  
		status = {
			1：退出成功；
			2：用户暂未登录；
		}
	***/
	public function logout(){
		if(!session('userInfo')){
			$output = array(
					'status' 	=>'2',
					'message'	=>'用户暂未登录'
			);
			$this->ajaxReturn($output);
		}
		$userInfo=session('userInfo');
		//用户ID
		//修改登陆状态
		//$userState=$this->user_device-> where('uid='.$uid)->setField('user_status','2');
		session('userInfo',null);
		//记录退出日志
		$types=null;
		$types['typefid']=1001106;
		$types['typeid']=100110601;
		$types['title']="用户ID为".$userInfo['uid']."成功退出";
		$this->logM->logAdd($types);
		$output = array(
			'status' 	=>'1',
			'message'	=>"退出成功"
		);
		$this->ajaxReturn($output);
	}
	
	
	/*
	 * 【功能接口】获取当前用户SESSION（登录）信息
	 * 接收数据格式  无
	 * 返回数据格式
		 {
			 'status'=>状态,
			 'message'=>提示信息,
			 'info'=array(
				 'uid'=>用户ID,
				 'uname'=>用户名,
				 'uphone'=>用户手机号,
				 'uemail'=>用户邮箱,
				 'nickname'=>用户昵称,
				 'headimg'=>用户头像,
				 'address'=>地址,
				 'sex'=>性别（1男 2女 3保密）,
				 'birthday'=>出生年月日,
				 'birthdaymonthday'=>出生月日,
				 'score'=>用户积分,
				 'user_ispush'=>是否接受推送（1接受 2不接受）
				 'ctime'=>创建时间
				 'ctype'=>性别 1男 2女
				 'isphone'=>手机认证（1认证通过 2认证未通过）,
				 'isemail'=>邮箱认证（1认证通过 2认证未通过）,
				 'isname'=>实名认证（1认证通过 2认证未通过 3审核中）,
				 'role'=>角色（1游客 2普通会员 3商家用户 4后台管理员）,
				 'state'=>状态 1正常 2停用,
				)	 
		 }  
		status = {
			1：获取成功；
			2：用户暂未登录；
		}
	 */
	public function getUserSessionInfo(){
		if(!session('userInfo')){
			$output = array(
					'status' 	=>'2',
					'message'	=>'用户暂未登录'
			);
			$this->ajaxReturn($output);
		}
		$userInfo=session('userInfo');
		if($userInfo){
			$output = array(
					'status' 	=>'1',
					'message'	=>'获取成功',
					'info'	=>$userInfo
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	 *  绑定用户名
	 *	接收数据格式 'name'=>待绑定用户名,
	 *	返回数据格式 
		{
			'status'=>状态,
			'message'=>提示信息
		}  
		status = {
			1：用户名绑定成功；
			2：缺少待绑定用户名；
			3：用户暂未登录；
			4：用户名重复；
			5：用户名绑定失败；
		}
	*/
	public function bindUname(){
		//用户名（手机号/邮箱）
		$name        = I('name','');
		//判断用户名为空
		if(empty($name)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少待绑定用户名'
			);
			$this->ajaxReturn($output);
		}
		if(!session('userInfo')){
			$output = array(
					'status' 	=>'3',
					'message'	=>'用户暂未登录'
			);
			$this->ajaxReturn($output);
		}
		$userInfo=session('userInfo');
		//校验用户名是否重复
		$users = $this->user->where("uname='".$name."'")->find();
		if($users){
			$output = array(
					'status' 	=>'4',
					'message'	=>'用户名重复'
			);
			$this->ajaxReturn($output);
		}
		//绑定用户名
		$state=$this->user-> where('uid='.$userInfo['uid'])->setField('uname',$name);
		//用户ID
		if($state){
			$output = array(
				'status' 	=>'1',
				'message'	=>'用户名绑定成功'
			);
			//记录退出日志
			$types=null;
			$types['typefid']=1001107;
			$types['typeid']=100110701;
			$types['title']="用户ID为".$userInfo['uid']."成功绑定用户名".$name;
			$this->logM->logAdd($types);
		}else{
			$output = array(
				'status' 	=>'4',
				'message'	=>'用户名绑定失败'
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 【功能接口】 忘记密码发送验证短信
	* 接收数据格式  'phone'=>手机号码
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：发送成功；
		2：缺少手机号；
		3：该用户不存在；
	}
	*/
	public function findPassPhoneSend(){
		$sign ='100110301'; 
		$phone =I('phone',''); 
		if(empty($phone)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少手机号'
			);
			$this->ajaxReturn($output);
		}
		//验证通过检测手机是否已注册
		$users = $this->user->where("uphone='".$phone."'")->find();
		if(!$users){
			//该邮箱已注册
			$output = array(
				'status' 	=>'3',
				'message'	=>'该用户不存在'
			);
			$this->ajaxReturn($output);
		}
		$this->Verifys->length   = 6;
		$this->Verifys->codeSet = '0123456789'; 
		$smsCode= $this->Verifys->entryText($sign);
		sendSms($phone,"您请求的验证码是：".$smsCode."，请输入后进行验证。如非本人操作，请及时修改密码以防被盗。谢谢！服务热线：400-6688-733 【非速搜】");
		$output = array(
			'status' 	=>'1',
			'message'	=>'发送成功'
		);
		$data=null;
		$data['phone']=$phone;
		$data['state']=2;
		$data['ctime']=time();
		session($phone.'#'.$sign,$data);
		//记录手机密码找回请求日志
		$types=null;
		$types['typefid']=1001103;
		$types['typeid']=100110301;
		$types['title']="请求使用手机号码".$phone."找回用户ID为".$users['uid']."的密码";
		$this->logM->logAdd($types);
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 【功能接口】 忘记密码发送验证邮件
	* 接收数据格式  'email'=>注册邮箱
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：发送成功；
		2：缺少邮箱账户；
		3：该用户不存在；
	}
	*/
	public function findPassEamilSend(){
		$email =I('email',''); 
		if(empty($email)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少邮箱账户'
			);
			$this->ajaxReturn($output);
		}
		//验证通过检测手机是否已注册
		$users = $this->user->where("uemail='".$email."'")->find();
		if(!$users){
			//该邮箱已注册
			$output = array(
				'status' 	=>'3',
				'message'	=>'该用户不存在'
			);
			$this->ajaxReturn($output);
		}
		//准备校验码
		$emailCode= $this->Verifys->entryMD5Text('100110302');
		sendMail($email,'非速搜展会网会员密码找回','请正在进行密码找回操作，请点击该链接完成密码找回'.$emailCode);
		$output = array(
			'status' 	=>'1',
			'message'	=>'发送成功'
		);
		$data=null;
		$data['email']=$email;
		$data['state']=2;
		$data['ctime']=time();
		//处理邮箱的点，SESSION的名不能含有点
		$emails=str_replace('.', '',$email);
		session($emails.'#100110302',$data);
		//记录手机密码找回请求日志
		$types=null;
		$types['typefid']=1001103;
		$types['typeid']=100110302;
		$types['title']="请求使用邮箱".$email."找回用户ID为".$users['uid']."的密码";
		$this->logM->logAdd($types);
		$this->ajaxReturn($output);
	}
	
	
	/*
	 *  忘记密码找回验证
	 *	接收数据格式 'type'=>找回类型（1手机 2邮箱）,'key'=>手机号或邮箱账户,'verify'=>验证码,'pass'=>密码
	 *	返回数据格式 
		{
			'status'=>状态,
			'message'=>提示信息
		}  
		status = {
			1：验证码正确；
			1：密码重置成功；
			2：找回类型（1手机 2邮箱）；
			3：缺少手机号或邮箱；
			4：用户名重复；
			5：验证码错误；
			6：手机号或邮箱账号非法操作；
			7：超出验证码有效期，请重新获取；
			8：手机号或邮箱验证未通过；
			9：用户不存在；
			10：密码重置失败；
		}
	*/
	public function findPass(){
		$type        = I('type','');
		$key        = I('key','');
		if(empty($type)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'找回类型（1手机 2邮箱）'
			);
			$this->ajaxReturn($output);
		}
		if(empty($key)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少手机号或邮箱'
			);
			$this->ajaxReturn($output);
		}
		if($type == 1){
			//验证码标识号
			$sign ='100110301';	
			$signs ='100110303';	
			//超时时间
			$time = 180;
			$keys = $key;
		}else if($type == 2){
			//验证码标识号
			$sign ='100110302';
			$signs ='100110304';
			//超时时间
			$time = 1800;
			$keys = $key;
			$key = str_replace('.', '',$key);
		}
		if(I('pass')){
			$pass = I('pass','');
			//校验验证码SESSION，并完成密码重置
			//校验用户邮箱注册SESSION
			if(session('?'.$key.'#'.$sign)){
				$Sessiondata=null;
				$Sessiondata=session($key.'#'.$sign);
				if(time() - $time > $Sessiondata['ctime']){
					$output = array(
						'status' 	=>'7',
						'message'	=>'超出验证码有效期，请重新获取'
					);
					$this->ajaxReturn($output);
				}
				if($Sessiondata['state']==1){
					//校验用户是否存在
					$users = $this->user->where("uphone='".$key."' or uemail='".$keys."'")->find();
					if(!$users){
						//用户不存在
						$output = array(
							'status' 	=>'9',
							'message'	=>'用户不存在'
						);
						$this->ajaxReturn($output);
					}
					//执行密码修改
					$state=$this->user->where('uid='.$users['uid'])->setField('upass',md5($pass));
					if($state){
						$output = array(
							'status' 	=>'1',
							'message'	=>'密码重置成功'
						);
						//重置成功后删除密码重置SESSION
						session($key.'#'.$sign,null);
						//记录密码找回成功日志
						$types=null;
						$types['typefid']=1001103;
						if($type == 1){
							$types['typeid']= 100110303;
							$types['title']="使用手机".$key."找回用户ID为".$users['uid']."的密码成功";
						}else if($type == 2){
							$types['typeid']= 100110304;
							$types['title']="使用邮箱".$keys."找回用户ID为".$users['uid']."的密码成功";
						}
						$this->logM->logAdd($types);
					}else{
						$output = array(
							'status' 	=>'10',
							'message'	=>'密码重置失败'
						);
					}
					$this->ajaxReturn($output);
				}else{
					$output = array(
						'status' 	=>'8',
						'message'	=>'手机号或邮箱验证未通过'
					);
					$this->ajaxReturn($output);
				}
			}else{
				$output = array(
					'status' 	=>'6',
					'message'	=>'手机号或邮箱账号非法操作'
				);
				$this->ajaxReturn($output);
			}	
			
		}else{
			$verify        = I('verify','');
			if(empty($verify)){
				$output = array(
						'status' 	=>'3',
						'message'	=>'缺少验证码'
				);
				$this->ajaxReturn($output);
			}
			//校验验证码
			$verifyState=$this->Verifys->check($verify,$sign);
			if($verifyState){
				$output = array(
						'status' 	=>'1',
						'message'	=>'验证码正确'
				);
				if(session('?'.$key.'#'.$sign)){
					//存在行为,校验有效期
					$data=null;
					$data=session($key.'#'.$sign);
					if(time()- $time > $data['ctime']){
						//超出验证码有效期
						$output = array(
							'status' 	=>'7',
							'message'	=>'超出验证码有效期，请重新获取'
						);
						$this->ajaxReturn($output);
					}
					//更新SESSION状态
					$data['state']=1;
					session($key.'#'.$sign,$data);
				}else{
					$output = array(
						'status' 	=>'6',
						'message'	=>'手机号或邮箱账号非法操作'
					);
				}
				$this->ajaxReturn($output);
			}else{
				$output = array(
						'status' 	=>'5',
						'message'	=>'验证码错误'
				);
				//session($phone.'#'.$sign,null);
				$this->ajaxReturn($output);
			}	
		}
	}
}