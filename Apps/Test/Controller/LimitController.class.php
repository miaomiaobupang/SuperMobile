<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\LogFileModel as LogFile;
// +----------------------------------------------------------------------
// | 非速搜WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://fairso.com All rights reserved.
// +----------------------------------------------------------------------
// | 公共层
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class LimitController extends Controller {
	//首次运行
	public function _initialize(){
		if(!session('loguser')){
			//日志用户SESSION不存在
			$whereLogU=null;
			$loguser=D('log_user');
			$log_record=D('log_record');
			$userinfo=session('userInfo');
			//查询是否有用户登录信息
			if($userinfo){
				//判断用户是否已登录
				$whereLogU['uid']=$userinfo['uid'];
				$whereLogU['utype']=$userinfo['role'];
				$whereLogU['uip']=$_SERVER['REMOTE_ADDR'];
				$loguserInfo=$loguser->where($whereLogU)->find();
				if(!$loguserInfo){
					//查询当前IP是否有相关用户信息
					$loguseryinfo=$loguser->where("uip='".$_SERVER['REMOTE_ADDR']."' and utype=1")->find();
					if($loguseryinfo){
						//有记录,修改日志用户信息执行绑定用户ID
						$loguser-> where('id='.$loguseryinfo['id'])->setField('uid',$userinfo['uid']);
						$loguserInfo=$loguser->where('id='.$loguseryinfo['id'])->find();
					}else{
						//没有记录,执行创建
						$data=null;
						$data['uid']=$userinfo['uid'];
						$data['utype']=$userinfo['role'];
						$data['uip']=$_SERVER['REMOTE_ADDR'];
						$data['utmie']=time();
						$data['ctime']=time();
						$data['state']=1;
						$loguserNid=$loguser->add($data);
						$loguserInfo=$loguser->where('id='.$loguserNid)->find();
					}
				}
			}else{
				$whereLogU['utype']=1;
				$whereLogU['uip']=$_SERVER['REMOTE_ADDR'];
				$loguserInfo=$loguser->where($whereLogU)->find();
				if(!$loguserInfo){
					//没有记录,执行创建
					$data=null;
					$data['utype']=1;
					$data['uip']=$_SERVER['REMOTE_ADDR'];
					$data['utmie']=time();
					$data['ctime']=time();
					$data['state']=1;
					$loguserNid=$loguser->add($data);
					$loguserInfo=$loguser->where('id='.$loguserNid)->find();
				}
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
			//var_dump(session('loguser'));
		}else{
			$logusers=session('loguser');
			if(session('userInfo')){
				//用户登录时校验用户登录信息和日志用户信息的用户ID是否一致
				$userinfo=session('userInfo');
				if($userinfo['uid']!=$logusers['uid']){
					$loguser=D('log_user');
					//查询数据库是否有当前数据库的用户记录
					$loguserInfo=$loguser->where("uip='".$_SERVER['REMOTE_ADDR']."' and utype=2 and uid=".$userinfo['uid'])->find();
					if(!$loguserInfo){
						//查询当前IP是否有相关用户信息
						$loguseryinfo=$loguser->where("uip='".$_SERVER['REMOTE_ADDR']."' and utype=1")->find();
						if($loguseryinfo){
							//有记录,修改日志用户信息执行绑定用户ID
							$loguser-> where('id='.$loguseryinfo['id'])->setField('uid',$userinfo['uid']);
							$loguserInfo=$loguser->where('id='.$loguseryinfo['id'])->find();
						}else{
							//没有记录,执行创建
							$data=null;
							$data['uid']=$userinfo['uid'];
							$data['utype']=$userinfo['role'];
							$data['uip']=$_SERVER['REMOTE_ADDR'];
							$data['utmie']=time();
							$data['ctime']=time();
							$data['state']=1;
							$loguserNid=$loguser->add($data);
							$loguserInfo=$loguser->where('id='.$loguserNid)->find();
						}
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
				}
			}else{
				//校验当前用户日志SESSION的uid是否为空
				if($logusers['uid']){
					$loguser=D('log_user');
					//查询当前IP是否有相关用户信息
					$loguseryinfo=$loguser->where("uip='".$_SERVER['REMOTE_ADDR']."' and utype=1")->find();
					if($loguseryinfo){
						$loguserInfo=$loguseryinfo;
					}else{
						//没有记录,执行创建
						$data=null;
						$data['utype']=1;
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
				}
			}
		}	
	}
	/**
	*返回 提示信息
	*/
	
	protected function ReturnMessage($no,$mes,$key=false,$arr= array()) {
		$out = array('status'=>'','message'=>'');
		$out['status']  = $no;
		$out['message'] = $mes;
		if($key) {
		   $out[$key] = $arr;
		}
		
		$this->ajaxReturn($out);
	
	} 
}