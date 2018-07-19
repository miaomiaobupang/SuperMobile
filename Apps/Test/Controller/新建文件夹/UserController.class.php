<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UploadFileModel as UploadFile;

// +----------------------------------------------------------------------
// | 爱能社
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://langyue.org All rights reserved.
// +----------------------------------------------------------------------
// | 用户
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class UserController extends LimitController {
	private $user;//用户表
	private $user_device;//用户设备表
	private $sign_record;//签到记录表
	private $school_auth;//学校认证表
	private $school;//学校表
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		//实例化用户表
		$this->user     = D('user');
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
		
	}
	
	
	/*
	* 用户注册
	* 接收数据格式  'phone'=>手机号,'email'=>邮箱,'password'=>密码,'device_type'=>设备类型1(ios)或者2(android),'user_token'=>用户TOKEN，device_number=>设备号
	* 返回数据格式
		{
			 'status'=>状态,
			 'message'=>提示信息,
			 'info'=array(
				 'uid'=>用户ID,
				 'web_token'=>用户授权码,
				 'web_type'=>用户类型 1QQ 2新浪微博 3微信,
				 'nickname'=>用户昵称,
				 'phone'=>手机号码,
				 'email'=>电子邮箱,
				 'password'=>密码
				 'head_img'=>用户头像
				 'address'=>常住地
				 'sex'=>性别 1男 2女
				 'birthday'=>生日,
				 'birthdaymonthday'=>生日月日,
				 'score'=>积分,
				 'money'=>金额,
				 'user_ispush'=>是否接受推送 1接受 2不接受,
				 'role'=>角色 1普通用户 2校长,
				 'state'=>状态 1正常 2停用,
				 'sign'=>今天签到状态 1已签到 2未签到
				 'school'=>学校ID 注：0位空
				)	 
		 } 
	status = {
		0：注册失败；
		1：注册成功；
		2：缺少手机号；
		3：缺少电子邮箱；
		4：缺少密码；
		5：缺少设备类型；
		6：缺少用户token；
		7：缺少用户设备号；
		9：该手机号已注册；
		10：该电子邮箱已注册；
	}
	*/
	public function register(){
		$phone        = I('phone','');
		$email        = I('email','');
		$password     = I('password','');
		$device_type  = I('device_type','');
		$user_token   = I('user_token','');	
		$device_number= I('device_number','');
		//判断手机号为空
		if(empty($phone)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少手机号'
			);
			$this->ajaxReturn($output);
		}
		//判断电子邮箱为空
		if(empty($email)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少电子邮箱'
			);
			$this->ajaxReturn($output);
		}
		//判断密码为空
		if(empty($password)){
			$output = array(
					'status' 	=>'4',
					'message'	=>'缺少密码'
			);
			$this->ajaxReturn($output);
		}
		//判断设备类型为空
		if(empty($device_type)){
			$output = array(
					'status' 	=>'5',
					'message'	=>'缺少设备类型'
			);
			$this->ajaxReturn($output);
		}
		//判断用户token为空
		if(empty($user_token)){
			$output = array(
					'status' 	=>'6',
					'message'	=>'缺少用户token'
			);
			$this->ajaxReturn($output);
		}
		//判断用户设备号为空
		if(empty($device_number)){
			$output = array(
					'status' 	=>'7',
					'message'	=>'缺少用户设备号'
			);
			$this->ajaxReturn($output);
		}
		//判断该手机号是否注册
		$phoneuser = $this->user->where('phone='.$phone)->find();
		if(!empty($phoneuser)){
			$output = array(
					'status' 	=>'9',
					'message'	=>'该手机号码已注册！'
			);
			$this->ajaxReturn($output);
		}
		//判断该电子邮箱是否注册
		$emailuser = $this->user->where('email='.$email)->find();
		if(!empty($emailuser)){
			$output = array(
					'status' 	=>'10',
					'message'	=>'该邮箱已注册！'
			);
			$this->ajaxReturn($output);
		}
		//会员电话
		$data['phone'] = $phone;
		//会员邮箱
		$data['email'] = $email;
		//会员密码
		$data['password'] = $password;
		//默认会员头像
		$data['head_img'] = '/Public/huiyuan.png';
		//默认会员昵称
		$data['nickname'] = '匿名用户';
		$uidNew = $this->user->data($data)->add();
		if($uidNew){
			//会员设备类型
			$datas['uid'] = $uidNew;
			$datas['device_type'] = $device_type;
			//会员token码
			$datas['user_token'] = $user_token;
			//会员设备号
			$datas['device_number'] = $device_number;
			$uidDeviceNew=$this->user_device->data($datas)->add();
			if($uidDeviceNew){
				//注册成功；
				//获取用户信息
				$userInfo=$this->user->where("uid=".$uidNew)->find();
				//销毁密码
				unset($userInfo['password']);
				//头像地址补全
				$userInfo['head_img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$userInfo['head_img'];
				//获取用户今天签到状态
				$today=strtotime(date('Y-m-d',time()));
				//判断是否签到
				$signState=$this->sign_record->where('today='.$today.' and uid='.$uid.' and state=1')->find();
				if($signState){
					//已签到
					$userInfo['sign']=1;
				}else{
					//未签到
					$userInfo['sign']=2;
				}
				//判断校长状态，获取学校ID
				if($userInfo['role']==2){
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
				}
				//处理NULL
				foreach($userInfo as $k=>$v){
				   if($userInfo[$k]==null){
					   $userInfo[$k]="";
				   }
				}
				$output = array(
					'status' 	=>'1',
					'message'	=>'注册成功',
					'info'	=>$userInfo,                   
				);
			}else{
				//注册失败；
				$output = array(
					'status' 	=>'0',
					'message'	=>'注册失败',
					'uid'	=>'0'
				);
			}
		}else{
			//注册失败；
			$output = array(
				'status' 	=>'0',
				'message'	=>'注册失败',
				'uid'	=>'0'
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	 * 用户登录
	 * 接收数据格式  'name'=>手机号/邮箱,'password'=>登录密码(md5)
	 * 返回数据格式
		 {
			 'status'=>状态,
			 'message'=>提示信息,
			 'info'=array(
				 'uid'=>用户ID,
				 'web_token'=>用户授权码,
				 'web_type'=>用户类型 1QQ 2新浪微博 3微信,
				 'nickname'=>用户昵称,
				 'phone'=>手机号码,
				 'email'=>电子邮箱,
				 'password'=>密码
				 'head_img'=>用户头像
				 'address'=>常住地
				 'sex'=>性别 1男 2女
				 'birthday'=>生日,
				 'birthdaymonthday'=>生日月日,
				 'score'=>积分,
				 'money'=>金额,
				 'user_ispush'=>是否接受推送 1接受 2不接受,
				 'role'=>角色 1普通用户 2校长,
				 'state'=>状态 1正常 2停用,
				 'sign'=>今天签到状态 1已签到 2未签到
				 'school'=>学校ID 注：0位空
				)	 
		 }  
		status = {
			1：登陆成功；
			2：缺少登录名（手机号或邮箱）；
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
					'message'	=>'缺少登录名（手机号或邮箱）'
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
		//查询是否有该用户
		$userInfo=$this->user->where("(phone='".$name."' or email='".$name."') and state=1")->find();
		if($userInfo){
			//校验密码
			if($password==$userInfo['password']){
				$this->user_device-> where('uid='.$userInfo['uid'])->setField('user_status','1');
				//销毁密码
				unset($userInfo['password']);
				//头像地址补全
				$userInfo['head_img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$userInfo['head_img'];
				//获取用户今天签到状态
				$today=strtotime(date('Y-m-d',time()));
				//判断是否签到
				$signState=$this->sign_record->where('today='.$today.' and uid='.$uid.' and state=1')->find();
				if($signState){
					//已签到
					$userInfo['sign']=1;
				}else{
					//未签到
					$userInfo['sign']=2;
				}
				//判断校长状态，获取学校ID
				if($userInfo['role']==2){
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
				}
				//处理NULL
				foreach($userInfo as $k=>$v){
				   if($userInfo[$k]==null){
					   $userInfo[$k]="";
				   }
				}
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
	 *	接收数据格式'uid'=>用户编号
	 *	返回数据格式 
		{
			'status'=>状态,
			'message'=>提示信息
		}  
		status = {
			0：退出失败；
			1：退出成功；
			2：缺少用户ID；
			3：该用户不存在；
		}
	***/
	public function logout(){
		//用户ID
		$uid   = I('uid','');
		//判断用户ID为空
		if(empty($uid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少用户ID'
			);
			$this->ajaxReturn($output);
		}
		//查询用户是否存在
		$userInfo=$this->user->where("uid=".$uid)->find();
		if($userInfo){
			//修改登陆状态
			$userState=$this->user_device-> where('uid='.$uid)->setField('user_status','2');
			$output = array(
				'status' 	=>'1',
				'message'	=>"退出成功"
			);
			$this->ajaxReturn($output);
		}else{
			$output = array(
				'status' 	=>'3',
				'message'	=>'该用户不存在'
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	* QQ，新浪微博，微信等第三方登陆
	* 接收数据格式'web_type'=>用户第三方类型 1QQ 2新浪微博 3微信,'web_token'=>用户第三方token,'device_type'=>设备类型1(ios)或者2(android),'user_token'=>用户TOKEN，device_number=>设备号
	* 返回数据格式
		 {
		'status'=>状态,
		'message'=>提示信息,
		'info'=array(
				'uid'=>用户ID,
				'web_token'=>用户授权码,
				'web_type'=>用户类型 1QQ 2新浪微博 3微信,
				'nickname'=>用户昵称,
				'phone'=>手机号码,
				'email'=>电子邮箱,
				'password'=>密码
				'head_img'=>用户头像
				'address'=>常住地
				'sex'=>性别 1男 2女
				'birthday'=>生日,
				'birthdaymonthday'=>生日月日,
				'score'=>积分,
				'money'=>金额,
				'user_ispush'=>是否接受推送 1接受 2不接受,
				'role'=>角色 1普通用户 2校长,
				'state'=>状态 1正常 2停用,
		}  
	 
		status = {
			1：登陆成功；
			2：缺少用户第三方类型；
			3：缺少用户第三方token；
			5：缺少用户设备类型1(ios)或者2(android)；
			6：缺少用户TOKEN；
			7：缺少用户设备号；
			8：注册失败；
			9：该用户被禁用；
		}
	*/
	public function externalLogin(){
		//用户第三方类型
		$web_type = I('web_type','');
		//用户第三方token
		$web_token = I('web_token','');
		//设备类型1(ios)或者2(android)
		$device_type = I('device_type','');
		//用户TOKEN
		$user_token = I('user_token','');
		//用户设备号
		$device_number = I('device_number','');
		//判断用户第三方类型为空
		if(empty($web_type)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少用户第三方类型'
			);
			$this->ajaxReturn($output);
		}
		//判断用户第三方token为空
		if(empty($web_token)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少用户第三方token'
			);
			$this->ajaxReturn($output);
		}
		//判断用户设备类型1(ios)或者2(android)为空
		if(empty($device_type)){
			$output = array(
					'status' 	=>'5',
					'message'	=>'缺少用户设备类型1(ios)或者2(android)'
			);
			$this->ajaxReturn($output);
		}
		//判断用户TOKEN为空
		if(empty($user_token)){
			$output = array(
					'status' 	=>'6',
					'message'	=>'缺少用户TOKEN'
			);
			$this->ajaxReturn($output);
		}
		//判断用户设备号为空
		if(empty($device_number)){
			$output = array(
					'status' 	=>'7',
					'message'	=>'缺少用户设备号'
			);
			$this->ajaxReturn($output);
		}
		//查询该用户是否注册过
		$userInfo=$this->user->where("web_type=".$web_type." and web_token='".$web_token."'")->find();
		if(!$userInfo){
			//没有注册，执行注册该用户
			//用户第三方类型
			$data['web_type'] = $web_type;
			//用户第三方token
			$data['web_token'] = $web_token;
			//默认会员头像
			$data['head_img'] = '/Public/huiyuan.png';
			//默认会员昵称
			$data['nickname'] = '匿名用户';
			$uidNew = $this->user->data($data)->add();
			if($uidNew){
				//会员设备类型
				$datas['uid'] = $uidNew;
				$datas['device_type'] = $device_type;
				//会员token码
				$datas['user_token'] = $user_token;
				//会员设备号
				$datas['device_number'] = $device_number;
				$uidDeviceNew=$this->user_device->data($datas)->add();
				if($uidDeviceNew){
					//注册成功；
					//查询用户信息
					$userInfo=$this->user->where("uid=".$uidNew)->find();
					//销毁密码
					unset($userInfo['password']);
					//头像地址补全
					$userInfo['head_img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$userInfo['head_img'];
					//处理NULL
					foreach($userInfo as $k=>$v){
					   if($userInfo[$k]==null){
						   $userInfo[$k]="";
					   }
					}
				}else{
					//注册失败；
					$output = array(
						'status' 	=>'8',
						'message'	=>'注册失败',
					);
					$this->ajaxReturn($output);
				}
			}else{
				//注册失败；
				$output = array(
					'status' 	=>'8',
					'message'	=>'注册失败',
				);
				$this->ajaxReturn($output);
			}	
		}
		//查询该用户是否被禁用
		if($userInfo['state']==2){
			$output = array(
				'status' 	=>'9',
				'message'	=>'该用户被禁用'
			);
			$this->ajaxReturn($output);
		}else{
			$this->user_device-> where('uid='.$userInfo['uid'])->setField('user_status','1');
			$output = array(
				'status' 	=>'1',
				'message'	=>'登陆成功',
				'info'		=>$userInfo
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	* 检测手机号是否已注册
	* 接收数据格式'phone'=>用户手机号
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		}  
	status = {
		1：该手机号没有注册
		2：缺少用户手机号
		3：该手机号已注册
	}
	*/
	public function checkPhone(){
		//用户手机号
		$phone = I('phone','');
		//判断用户手机号为空
		if(empty($phone)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少用户手机号'
			);
			$this->ajaxReturn($output);
		}
		//查询用户信息
		$userInfo=$this->user->where("phone=".$phone)->find();
		if($userInfo){
			$output = array(
				'status' 	=>'3',
				'message'	=>'该手机号已注册'
			);
			$this->ajaxReturn($output);
		}else{
			$output = array(
				'status' 	=>'1',
				'message'	=>'该手机号没有注册'
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	* 更改用户密码
	* 接收数据格式 'uid'=>用户ID,'password'=>用户新密码(md5)，'passwordY'=>用户当前登录密码(md5)
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		}  
	status = {
		1：用户密码更改成功；
		2：缺少用户ID；
		3：缺少用户原有登陆密码；
		4：缺少用户新登陆密码；
		5：缺少用户新登陆密码和原有密码一致；
		6：该用户不存在；
		7：该用户原有密码错误；
		8：用户密码更改失败；
	}
	*/
	public function changeUserPwd(){
		//用户ID
		$uid = I('uid','');
		//用户老密码
		$passwordY = I('passwordY','');
		//用户新密码
		$password = I('password','');
		//判断用户ID为空
		if(empty($uid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少用户ID'
			);
			$this->ajaxReturn($output);
		}
		//判断用户老密码为空
		if(empty($passwordY)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少用户原有登陆密码'
			);
			$this->ajaxReturn($output);
		}
		//判断用户新密码为空
		if(empty($password)){
			$output = array(
					'status' 	=>'4',
					'message'	=>'缺少用户新登陆密码'
			);
			$this->ajaxReturn($output);
		}
		//判断新密码和老密码是否一致
		if($password==$passwordY){
			$output = array(
					'status' 	=>'5',
					'message'	=>'缺少用户新登陆密码和原有密码一致'
			);
			$this->ajaxReturn($output);
		}
		//判断该用户是否存在
		$userInfo=$this->user->where('uid='.$uid)->find();
		if($userInfo){
			//判断该用户原有登陆密码是否正确
			if($passwordY==$userInfo['password']){
				$newpass=$this->user-> where('uid='.$uid)->setField('password',$password);
				if($newpass){
					$output = array(
						'status' 	=>'1',
						'message'	=>'用户密码更改成功'
					);
				}else{
					$output = array(
						'status' 	=>'8',
						'message'	=>'用户密码更改失败'
					);
				}
				$this->ajaxReturn($output);
			}else{
				$output = array(
					'status' 	=>'7',
					'message'	=>'该用户原有密码错误'
				);
				$this->ajaxReturn($output);
			}
		}else{
			$output = array(
					'status' 	=>'6',
					'message'	=>'该用户不存在'
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	* 获取用户信息
	* 接收数据格式 'uid'=>用户ID
	* 返回数据格式
		{
		'status'=>状态,
		'message'=>提示信息,
		'info'=array(
				'uid'=>用户ID,
				'web_token'=>用户授权码,
				'web_type'=>用户类型 1QQ 2新浪微博 3微信,
				'nickname'=>用户昵称,
				'phone'=>手机号码,
				'email'=>电子邮箱,
				'password'=>密码
				'head_img'=>用户头像
				'address'=>常住地
				'sex'=>性别 1男 2女
				'birthday'=>生日,
				'birthdaymonthday'=>生日月日,
				'score'=>积分,
				'money'=>金额,
				'user_ispush'=>是否接受推送 1接受 2不接受,
				'role'=>角色 1普通用户 2校长,
				'state'=>状态 1正常 2停用
				'sign'=>今天签到状态 1已签到 2未签到
				'school'=>学校ID 注：0位空
			)
		} 
	status = {
		1：用户信息获取成功；
		2：缺少用户ID；
		3：该用户不存在；
	}
	*/
	public function getUserInfo(){
		//用户ID
		$uid = I('uid','');
		//判断用户ID为空
		if(empty($uid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少用户ID'
			);
			$this->ajaxReturn($output);
		}
		//获取该用户信息
		$userInfo=$this->user->where('uid='.$uid)->find();
		//头像地址补全
		$userInfo['head_img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$userInfo['head_img'];
		//获取用户今天签到状态
		$today=strtotime(date('Y-m-d',time()));
		//判断是否签到
		$signState=$this->sign_record->where('today='.$today.' and uid='.$uid.' and state=1')->find();
		if($signState){
			//已签到
			$userInfo['sign']=1;
		}else{
			//未签到
			$userInfo['sign']=2;
		}
		//判断校长状态，获取学校ID
		if($userInfo['role']==2){
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
		}
		//处理NULL
		foreach($userInfo as $k=>$v){
		   if($userInfo[$k]==null){
			   $userInfo[$k]="";
		   }
		}
		if($userInfo){
			$output = array(
				'status' 	=>'1',
				'message'	=>'用户信息获取成功',
				'info'		=>$userInfo
			);
			$this->ajaxReturn($output);
		}else{
			$output = array(
					'status' 	=>'3',
					'message'	=>'该用户不存在'
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	* 更改用户信息-单项修改
	* 接收数据格式'uid'=>用户ID,'key'=>字段名,'value'=>值
		key可以为：
			字段名				注释
			web_token			用户授权码
			web_type			用户类型 1QQ 2新浪微博 3微信
			nickname			用户昵称
			phone				手机号码
			email				电子邮箱
			password			用户密码
			head_img			用户头像
			address				常住地
			sex					性别 1男 2女
			birthday			生日
			birthdaymonthday	生日月日
			score				积分
			money				金额
			user_ispush			是否接受推送 1接受 2不接受
			role				角色 1普通用户 2校长
			state				状态 1正常 2停用
		value为各字段对应的合法的值
	* 	返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		}  
		status = {
			1：修改成功；
			2：缺少用户ID；
			3：缺少字段名；
			4：缺少值；
			5：该用户不存在；
			6：手机号已存在；
			7：电子邮箱已存在；
		}
	*/
	public function updateInfo(){
		//用户ID
		$uid = I('uid','');
		//字段名
		$key = I('key','');
		//值
		$value = I('value','');
		//判断用户ID为空
		if(empty($uid)){
			$output = array(
				'status' 	=>'2',
				'message'	=>'缺少用户ID'
			);
			$this->ajaxReturn($output);
		}
		//判断字段名为空
		if(empty($key)){
			$output = array(
				'status' 	=>'3',
				'message'	=>'缺少字段名'
			);
			$this->ajaxReturn($output);
		}
		//判断值为空
		if(empty($value)){
			$output = array(
				'status' 	=>'4',
				'message'	=>'缺少值'
			);
			$this->ajaxReturn($output);
		}
		//判断用户是否存在
		$userInfo=null;
		$userInfo=$this->user->where("uid=".$uid)->find();
		if(!$userInfo){
			$output = array(
				'status' 	=>'5',
				'message'	=>'该用户不存在'
			);
			$this->ajaxReturn($output);
		}
		//判断手机号重复
		if($key=="phone"){
			$userInfo=null;
			$userInfo=$this->user->where("phone=".$value)->find();
			if($userInfo){
				$output = array(
				'status' 	=>'6',
				'message'	=>'手机号已存在'
				);
				$this->ajaxReturn($output);
			}
		}
		//判断电子邮箱重复
		if($key=="email"){
			$userInfo=null;
			$userInfo=$this->user->where("email=".$value)->find();
			if($userInfo){
				$output = array(
				'status' 	=>'7',
				'message'	=>'电子邮箱已存在'
				);
				$this->ajaxReturn($output);
			}
		}
		//处理修改
		$this->user-> where('uid='.$uid)->setField($key,$value);
			$output = array(
			'status' 	=>'1',
			'message'	=>'修改成功'
			);
			$this->ajaxReturn($output);
	}
	
	
	/*
	* 修改用户头像
	* 接收数据格式 'uid'=>用户ID,'uploadfile'=>form表单file标签name
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		}  
	status = {
		1：修改成功；
		2：缺少用户编号；
		3：上传图片出错；
		4：修改失败；
	}
	*/
	public function updateHeader() {
		$uid =I('uid','');     
		if(empty($uid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少用户编号'
			);
			$this->ajaxReturn($output);
		}
		$myUpload = new UploadFile();
		$imgpath = $myUpload->upload_head();//图片路径
			if(!$imgpath){
				$output = array(
				'status' 	=>'3',
				'message'	=>'上传图片出错'.$myUpload->error,
				'head_img'	=>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.$imgpath
			);
				$this->ajaxReturn($output);
			}
		$data = array('head_img'=>$imgpath);
		$where=array('uid'=>$uid);
		$lastid = $this->user->where($where)->data($data)->save();
		if($lastid){
			$output = array(
					'status' 	=>'1',
					'message'	=>'修改成功',
					'head_img'	=>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.$imgpath
			);
		}else{
			$output = array(
					'status' 	=>'4',
					'message'	=>'修改失败',
					'head_img'	=>''
			);
		}
		$this->ajaxReturn($output);
	}
	/*
	* 忘记密码
	* 接收数据格式  'name'=>用户手机号码或电子邮箱
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
	public function findPwd(){
		$name =I('name','');     
		if(empty($name)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少登录名'
			);
			$this->ajaxReturn($output);
		}
		//查找用户信息
		$userInfo=$this->user->where("phone='".$name."' or email='".$name."'")->find();
		if(!$userInfo){
			$output = array(
					'status' 	=>'3',
					'message'	=>'该用户不存在'
			);
			$this->ajaxReturn($output);
		}
		//判断该用户邮箱是否存在
		if(empty($userInfo['email'])){
			$output = array(
				'status' 	=>'4',
				'message'	=>'该用户电子邮箱缺失'
			);
			$this->ajaxReturn($output);
		}
		//生成找回密码的校验码
		$verify=array("1","2","3","4","5","6","7","8","9","0","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
		$userVerify=null;
		for($i=0;$i<16;$i++){
			$userVerify.=$verify[rand(0,35)];
		}
		//进行md5加密
		$userVerify=md5($userVerify);
		//储存用户校验码
		$data=null;
		//查询用户密码找回表中已有用户信息
		$findUserPass=$this->userpass_find->where("uid=".$userInfo['uid'])->find();
		$data['verify']=$userVerify;
		if($findUserPass){
			//如果能查到进行修改
			$verifyId=$this->userpass_find->where('uid='.$userInfo['uid'])->save($data); 
		}else{
			//如果不能查到进行新增
			$data['uid']=$userInfo['uid'];
			$verifyId=$this->userpass_find->add($data);
		}
		if(!$verifyId){
			//判断该用户校验码是否储存成功
			$output = array(
				'status' 	=>'5',
				'message'	=>'用户校验码生成失败'
			);
			$this->ajaxReturn($output);
		}
		//准备发送用户密码重置连接
		$sendUrl='http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/User/userPassReset/uid/'.$userInfo['uid'].'/verify/'.$userVerify;
		$htmlContent="请点击链接（或复制链接至浏览器的地址栏）进行密码重置<a href='".$sendUrl."' title='爱能社用户密码重置'>".$sendUrl."</a>";
		sendMail($userInfo['email'],"爱能社密码重置",$htmlContent);
		$output = array(
			'status' 	=>'1',
			'message'	=>'用户密码找回链接申请成功'
		);
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 用户密码重置
	* 接收数据格式  'name'=>用户手机号码或电子邮箱,'email'=>用户邮箱
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {-4=用户不存在,-3=用户邮箱不正确,-2=缺少用户邮箱,-1=缺少用户手机号,1=发送成功,0=发送失败}
	*/
	public function userPassReset(){
		$uid =I('uid','');     
		$verify =I('verify','');     
		if(empty($uid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少用户ID'
			);
			$this->ajaxReturn($output);
		}
		if(empty($verify)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少用户校验码'
			);
			$this->ajaxReturn($output);
		}
		//校验用户和校验码
		$userVerify=$this->userpass_find->where("uid=".$uid." and verify='".$verify."'")->find();
		if(!$userVerify){
			$output = array(
					'status' 	=>'4',
					'message'	=>'用户校验码非法'
			);
			$this->ajaxReturn($output);
		}
		if(I('userpass')){
			$state=$this->user-> where('id=5')->setField('password',md5(I('userpass')));
			if($state){
				$output = array(
					'status' 	=>'1',
					'message'	=>'用户密码重置成功'
				);
				$this->ajaxReturn($output);
			}else{
				$output = array(
					'status' 	=>'5',
					'message'	=>'用户密码重置失败'
				);
				$this->ajaxReturn($output);
			}
		}else{
			$this->display();
		}
	}
}