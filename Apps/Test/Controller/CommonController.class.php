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
// | 公共控制器
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class CommonController extends LimitController {

	private $Verifys;//验证码类
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		
		//实例化验证码类
		$this->Verifys = new \Think\Verify();
	}
	
	
	
	/*
	* 图形验证码生成
	* 接收数据格式
		必传：'sign'=>验证码图形标识码
		选传：'fontSize'=>字体（默认为30）,'length'=>长度（默认为4）,'useNoise'=>杂点开关（默认为关闭）,
	* 返回数据格式 png图形
	*/
	public function verifyCreate($sign,$fontSize = "30",$length ="4",$useNoise = false){
		$this->Verifys->fontSize = $fontSize;
		$this->Verifys->length   = $length;
		$this->Verifys->useNoise = $useNoise;
		$this->Verifys->entry($sign);
	}
	
	
	/*
	* 纯数字短信验证码生成并发送
	* 接收数据格式		'sign'=>验证码标识符
	* 					'phone'=>待发送手机号
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：发送成功；
		2：缺少标识号；
		3：缺少手机号；
	}
	*/
	public function verifySMSCreateSend(){
		$sign =I('sign',''); 
		$phone =I('phone',''); 
		if(empty($sign)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少标识码'
			);
			$this->ajaxReturn($output);
		}
		if(empty($phone)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少手机号'
			);
			$this->ajaxReturn($output);
		}
		$this->Verifys->length   = 4;
		$this->Verifys->codeSet = '0123456789'; 
		$smsCode= $this->Verifys->entryText($sign);
		sendSms($phone,"【非速搜】您的订单验证码是".$smsCode."，1分钟有效，客服热线400-6688-733");
		$output = array(
			'status' 	=>'1',
			'message'	=>'发送成功'
		);
		$data=null;
		$data['phone']=$phone;
		$data['state']=2;
		$data['ctime']=time();
		session($phone.'#'.$sign,$data);
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 验证码校验
	* 接收数据格式		'sign'=>验证码标识符
	* 					'verify'=>验证码
	* 					'isSession'=>成功后是否储存SESSION
	* 					'phone'=>参数isSession依赖
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
	public function verifyCheck(){
		$sign =I('sign',''); 
		$verify =I('verify',''); 
		$isSession =I('isSession','2'); 
		$phone =I('phone',''); 
		if(empty($sign)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少标识号'
			);
			$this->ajaxReturn($output);
		}
		if(empty($verify)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少验证码'
			);
			$this->ajaxReturn($output);
		}
		//判断是否存储SESSION
		if($isSession==1){
			if(empty($phone)){
				$output = array(
						'status' 	=>'4',
						'message'	=>'缺少手机号（参数isSession依赖条件）'
				);
				$this->ajaxReturn($output);
			}
		}
		//校验验证码
		$verifyState=$this->Verifys->check($verify,$sign);
		if($verifyState){
			$output = array(
					'status' 	=>'1',
					'message'	=>'验证码正确'
			);
			if($isSession==1){
				//执行存储验证
				if(session('?'.$phone.'#'.$sign)){
					//存在行为,校验有效期
					$data=null;
					$data=session($phone.'#'.$sign);
					if(time()-180 > $data['ctime']){
						//超出验证码有效期
						$output = array(
							'status' 	=>'6',
							'message'	=>'超出验证码有效期，请重新获取'
						);
						$this->ajaxReturn($output);
					}
					//更新SESSION状态
					$data['state']=1;
					session($phone.'#'.$sign,$data);
				}else{
					$output = array(
						'status' 	=>'5',
						'message'	=>'手机号非法操作'
					);
				}
				$this->ajaxReturn($output);
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
	
	
	/*
	* 邮件验证码(MD5)校验
	* 接收数据格式		'sign'=>验证码标识符
	* 					'verify'=>验证码
	* 					'isSession'=>成功后是否储存SESSION
	* 					'email'=>参数isSession依赖
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
	public function verifyEmailCheck(){
		$sign =I('sign',''); 
		$verify =I('verify',''); 
		$isSession =I('isSession','2'); 
		$email =I('email',''); 
		if(empty($sign)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少标识号'
			);
			$this->ajaxReturn($output);
		}
		if(empty($verify)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少验证码'
			);
			$this->ajaxReturn($output);
		}
		//判断是否存储SESSION
		if($isSession==1){
			if(empty($email)){
				$output = array(
						'status' 	=>'4',
						'message'	=>'缺少手机号（参数isSession依赖条件）'
				);
				$this->ajaxReturn($output);
			}
		}
		//校验验证码
		$verifyState=$this->Verifys->check($verify,$sign);
		if($verifyState){
			$output = array(
					'status' 	=>'1',
					'message'	=>'验证码正确'
			);
			if($isSession==1){
				//执行存储验证
				//处理邮箱的点，SESSION的名不能含有点
				$emails=str_replace('.', '',$email);
				if(session('?'.$emails.'#'.$sign)){
					//存在行为,校验有效期
					$data=null;
					$data=session($emails.'#'.$sign);
					if(time()-1800 > $data['ctime']){
						//超出验证码有效期
						$output = array(
							'status' 	=>'7',
							'message'	=>'超出验证码有效期，请重新获取'
						);
						$this->ajaxReturn($output);
					}
					//更新SESSION状态
					$data['state']=1;
					session($emails.'#'.$sign,$data);
				}else{
					$output = array(
						'status' 	=>'6',
						'message'	=>'手机号非法操作'
					);
				}
				$this->ajaxReturn($output);
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
	
	
	 //执行从redis写入mysql数据库
    public function redisToMysql(){
    	//从缓存读取redisUid的日志数量
		$uid = $this->redis->lsize("redisLogRecord");
		//从缓存中取出数据，并返回$data为二维数组
		for($i=1;$i<=$uid;$i++){
			$data[] = $this->redis->hgetall('log_record:'.$i);
		}
		//将数据写入数据库
		$count = count($data);
		$record= M("log_record");
		$recordlist =  $record->select();
		for($j=0;$j<$count;$j++){
			$rTom=null;
			$rTom['luid'] = $data[$j]['luid'];
			$rTom['typefid'] = $data[$j]['typefid'];
			$rTom['typeid'] = $data[$j]['typeid'];
			$rTom['typecid'] = $data[$j]['typecid'];
			$rTom['title'] = $data[$j]['title'];
			$rTom['browseragent'] = $data[$j]['browseragent'];
			$rTom['browserversion'] = $data[$j]['browserversion'];
			$rTom['browserplatform'] = $data[$j]['browserplatform'];
			$rTom['ip'] = $data[$j]['ip'];
			$rTom['country'] = $data[$j]['country'];
			$rTom['province'] = $data[$j]['province'];
			$rTom['city'] = $data[$j]['city'];
			$rTom['district'] = $data[$j]['district'];
			$rTom['carrier'] = $data[$j]['carrier'];
			$rTom['ctime'] = $data[$j]['ctime'];
			$rTom['cdate'] = $data[$j]['cdate'];
			$rTom['state'] = 1;
			//执行将从redis得到的日志保存至Mysql数据库
			$info = $record->add($rTom);
			dump($rTom);
			dump($info);
		}
		die();
		//判断从redis得到的日志保存至Mysql数据库是否成功
		if($info){
			$this->redirect("Index/index");
		}else{
			$this->error("redis记录日志保存至Mysql数据库过程中出现错误,请管理员查看!");
		} 
    }
}