<?php
namespace Admin\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | 超级医生WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://hopenoah.com All rights reserved.
// +----------------------------------------------------------------------
// | 词条控制器
// +----------------------------------------------------------------------
// | Author: Davin <yulong@hopenoah.com>
// +----------------------------------------------------------------------
class LimitController extends Controller {
    //首次运行
	public function _initialize(){
		//首次运行
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
		
		
		
		
		//缓存初始化
	   	$cache = S(array());
		//判断用户是否登陆
		if(session('uid')){
			//获取日志用户ID
			$logUserId=session('uid');
			$userName = M("user")->where('uid='.$logUserId)->getField('uname');
			session('logUserName',$userName);
			//超级管理员用户组
			$SUPERIDS=explode(',',C('SUPERID'));
			//判断菜单缓存数据
			//unset($cache->LeftNav);
			if(session('LeftNav')==""){
				$Menu =  M("menu") ;
				
				if(!in_array(session('uid'),$SUPERIDS,true)){
					//不是超级管理员
					$risInfo=D('user_adminrole')->getFieldByUid(session('uid'),'rid');
					if(!$risInfo){
						$this->error("登录信息非法");
					}
					$menuRole=D('role')->getFieldById($risInfo,'menuId');
					if(!$menuRole){
						$this->error("登录信息非法");
					}
					$MenuZ=explode('&',$menuRole);
					if($MenuZ){
						$MenuZCount=count($MenuZ);
						$MenuN=null;
						$MenuNC=null;
						for($i=0;$i<$MenuZCount;$i++){
							$MenuL=null;
							$MenuL=explode('#',$MenuZ[$i]);
							$MenuN.=$MenuL[0].',';
							$MenuNC[$MenuL[0]]=$MenuL[1];
						}
					}
					$MenuN=rtrim($MenuN,',');
					
					//整合菜单数据
					if($MenuN and $MenuNC){
						$menuWhere['id']=array('in',$MenuN);
						$menuWhere['model']=1;
						$MenuZN=$Menu->where($menuWhere)->order('num')->select();
						//梳理顶级菜单
						if($MenuZN){
							$LeftMenuInfo=null;
							$MenuZNNum=count($MenuZN);
							for($i=0;$i<$MenuZNNum;$i++){
								$MenuNCL=null;
								$MenuNCLNum=null;
								$menucWhere=null;
								//加载顶级菜单导航样式
								$LeftMenuInfo .="<li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='".$MenuZN[$i]['ico']."'></i> ".$MenuZN[$i]['name']." <b class='icon-plus dropdown-plus'></b></a><ul class='dropdown-menu'>";
									//加载二级菜单
									if($MenuNC[$MenuZN[$i]['id']]){
										$menucWhere['id']=array('in',$MenuNC[$MenuZN[$i]['id']]);
										$menucWhere['model']=2;
										$MenuNCL=$Menu->where($menucWhere)->order('num')->select();
										if($MenuNCL){
											$MenuNCLNum=count($MenuNCL);
											for($j=0;$j<$MenuNCLNum;$j++){
												//加载二级菜单导航信息
												//strpos($str,"http://")
												$LeftMenuInfo .="<li><a href='".C('ADMINWEBURL')."/".$MenuNCL[$j]['adress']."'><i class='".$MenuNCL[$j]['ico']."'></i> ".$MenuNCL[$j]['name']."</a></li>";
											}
										}
									}
									
								$LeftMenuInfo .="</ul></li>";
							}
						}
					}else{
						$this->error("登录信息非法");
					}
				}else{
					//超级管理员拥有全部菜单
					$menuWhere['id']=array('lt',68);
					$menuWhere['model']=1;
					$MenuZN=$Menu->where($menuWhere)->order('num')->select();
					//梳理顶级菜单
					if($MenuZN){
						$LeftMenuInfo=null;
						$MenuZNNum=count($MenuZN);
						for($i=0;$i<$MenuZNNum;$i++){
							$MenuNCL=null;
							$MenuNCLNum=null;
							$menucWhere=null;
							//加载顶级菜单导航样式
							$LeftMenuInfo .="<li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='".$MenuZN[$i]['ico']."'></i> ".$MenuZN[$i]['name']." <b class='icon-plus dropdown-plus'></b></a><ul class='dropdown-menu'>";
								//加载二级菜单
								if($MenuZN[$i]['id']){
									$menucWhere['pid']=$MenuZN[$i]['id'];
									$menucWhere['model']=2;
									$MenuNCL=$Menu->where($menucWhere)->order('num')->select();
									if($MenuNCL){
										$MenuNCLNum=count($MenuNCL);
										for($j=0;$j<$MenuNCLNum;$j++){
											//加载二级菜单导航信息
											//strpos($str,"http://")
											$LeftMenuInfo .="<li><a href='".C('ADMINWEBURL')."/".$MenuNCL[$j]['adress']."'><i class='".$MenuNCL[$j]['ico']."'></i> ".$MenuNCL[$j]['name']."</a></li>";
										}
									}
								}
								
							$LeftMenuInfo .="</ul></li>";
						}
					}
				}
				
				// dump(session('uid'));
					// session('LeftNav',$LeftMenuInfo);
				if($LeftMenuInfo){
					session('LeftNav',$LeftMenuInfo);
				}else{
					$this->error("登录信息非法");
				}
			}
			//判断用户权限
			if(!in_array(session('uid'),$SUPERIDS,true)){
				//判断用户权限缓存是否存在
				$postDutys=session('postDuty');
				if($postDutys==null){
				
				//if(empty(session('postDuty'))){
					//当前用户不是超级管理员执行权限校验
					if(!$risInfo){
						$risInfo=D('user_adminrole')->getFieldByUid(session('uid'),'rid');
					}
					if(!$risInfo){
						$this->error("登录信息非法");
					}
					//获取权限组
					$menuRoleP=D('role')->getFieldById($risInfo,'postDuty');
					$menuRoleP=str_replace('#',',',$menuRoleP);
					if(!$menuRoleP){
						$this->error("登录信息非法");
					}
					$postdutyWhere['id']=array('in',$menuRoleP);
					$postdutyWhere['properties']=2;
					$postdutyZ=D('modules')->where($postdutyWhere)->field('Controller,Method')->select();
					if($postdutyZ){
						$postdutyZNum=count($postdutyZ);
						for($i=0;$i<$postdutyZNum;$i++){
							if(strpos($postdutyZ[$i]['Method'],',')){
								$methodc=null;
								$methodcNum=null;
								$methodc=explode(',',$postdutyZ[$i]['Method']);
								$methodcNum=count($methodc);
								for($j=0;$j<$methodcNum;$j++){
									$postdutyZN[]=$postdutyZ[$i]['Controller'].'/'.$methodc[$j];
								}
							}else{
								$postdutyZN[]=$postdutyZ[$i]['Controller'].'/'.$postdutyZ[$i]['Method'];
							}
						}
					}
					if($postdutyZN){
						//$cache->postDuty=$postdutyZN;
						session('postDuty',$postdutyZN);
						$postDutys=session('postDuty');
					}else{
						$this->error("登录信息非法");
					}
				}
				
				
				//判断用户权限
				if(CONTROLLER_NAME and ACTION_NAME){
					//$userRole=CONTROLLER_NAME.'/'.ACTION_NAME;
					//控制器全小写
					$userRole=strtolower(CONTROLLER_NAME).'/'.ACTION_NAME;
					//控制器首字母大写
					$userRoles=ucfirst(strtolower(CONTROLLER_NAME)).'/'.ACTION_NAME;
					$userRoley=CONTROLLER_NAME.'/'.ACTION_NAME;
					if(in_array($userRole,$postDutys,true) or in_array($userRoles,$postDutys,true) or in_array($userRoley,$postDutys,true)){
						//如果存在则进入
						return true;
					}else{
						$this->error('对不起，您没有相应的权限，如需使用请联系管理员',U('Index/index'));
					}
				}else{
					$this->error("访问信息非法");
				}
			}
		}else{
			$this->redirect('Login/index');
		}
		
	}
}