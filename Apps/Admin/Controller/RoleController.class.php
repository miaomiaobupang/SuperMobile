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
class RoleController extends Controller {
   public function RoleAdd(){
	   //加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","Role/add");
        $this->assign("Js","Role/RoleJs");
        $this->display("Conmons/Frame");
   } 
   public function RoleInsert(){
       $role = M('role');
       $role->create();
       if($role->add()){
           $this->redirect('Role/showRole');
       }else{
           $this->error('添加失败');
       }
   }
   public function showRole(){
       $role = M('role');
       $roleResult = $role->select();
       $this->assign('role',$roleResult);
	   //加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","Role/RoleShow");
        $this->display("Conmons/Frame");
   }
   public function edit($id){
       $role = M('role');
       $result = $role->where('id='.$id)->find();
        $this->assign('role',$result);
		//加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","Role/edit");
        $this->display("Conmons/Frame");
   }
   public function RoleUpdate(){
       $id = I('post.id');
       $data = I('post.');
       unset($data['id']);
       $role = M('role');
       $role->create($data);
       if($role->where('id='.$id)->save()){
           $this->redirect('Role/showRole');
       }else{
           $this->error('修改失败');
       }
   }
   public function del($id){
       $role = M('role');
       if($role->delete($id)){
           $this->redirect('Role/showRole');
       }else{
           $this->error('删除失败');
       }
   }
	public function menuShow($id){
		$menu = M('menu');
		$role = M('role');
		$result = $menu->where('pid=0')->order('num ASC')->select();
		
		$data=$role->where('id='.$id)->getField('menuId');
		$dataArr=explode('&',$data);
		$dataArrCon=count($dataArr);
		for($p=0;$p<$dataArrCon;$p++){
			$maps[$p]=explode('#',$dataArr[$p]);
			$map[$p].=$maps[$p][1].','.$maps[$p][0];
		}
		//二级菜单
		$len=count($result);
		for($i=0;$i<$len;$i++){
			$result[$i]['second']=$menu->where('pid='.$result[$i]['id'])->field('id,pid,name')->order('num ASC')->select();
		}
		//修改默认
		$leng=count($map);
		$modArr = array();
		for($k=0;$k<$leng;$k++){
			$list=explode(',',$map[$k]);
			$len=count($result);
			$listCon=(int)end($list);
			for($j=0;$j<$len;$j++){
				if($result[$j]['id'] == $listCon){
					$result[$j]['idStr'] = $map[$k].',';
					$result[$j]['secondNum']=count($list)-1;
				}
			}
		}
		$this->assign('id',$id);
		$this->assign('len',$len);
		$this->assign('result',$result);
		//加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
		// $this->assign("Js","Role/menuJs");
		$this->assign("Tel","Role/menuShow");
		$this->display("Conmons/Frame");
	}
	public function menuSonShow($id){
		$menu = M("menu");
		$result = $menu->where('pid='.$id)->select();
		echo json_encode($result);
	}
	public function menuAction(){
		$duty = I('duty','');
		
		$id =I('id','');
		$len=count($duty);
		for($i=0;$i<$len;$i++){
			$sub[$i]=strlen($duty[$i]);
			if($sub[$i]>3){
				$duty[$i]=rtrim($duty[$i],',');
				$dutyArr[$i]=explode(',',$duty[$i]);
				$leng[$i]=count($dutyArr[$i]);
				$main[$i]=$dutyArr[$i][$leng[$i]-1];
				for($k=0;$k<$leng[$i]-1;$k++){
					$all[$i].=$dutyArr[$i][$k].',';
				}
				$mailAll[$i].=$main[$i].'#'.rtrim($all[$i],',');
				$mails.=$mailAll[$i].'&';
			}else{
				unset($duty[$i]);
			}
		}
		$mails=rtrim($mails,'&');
	   
		$role = M('role');
		$result['menuId'] = $mails;
		$role->create($result);
		if($role->where('id='.$id)->save()){
			$this->redirect('Role/showRole');
		}else{
			$this->error("更新失败");
		}
	}
	public function controlShow($id){
		$module = M('modules');
		$role = M('role');
		$result = $module->where('pid=0 AND id!=192')->order('id ASC')->select();
		// $result=unset($result['id'],192);
		// dump($result);exit;
		//二级菜单
		$len=count($result);
		for($i=0;$i<$len;$i++){
			$result[$i]['second']=$module->where('pid='.$result[$i]['id'].' AND type=1 AND properties=2 AND state=1')->field('id,pid,name')->order('num ASC')->select();
		}
		
		//修改默认
		$data=$role->where('id='.$id)->getField('postDuty');
		$dataArr=explode('#',$data);
		$leng=count($dataArr);
		$modArr = array();
		for($k=0;$k<$leng;$k++){
			$list=explode(',',$dataArr[$k]);
			$len=count($result);
			$listCon=(int)end($list);
			for($j=0;$j<$len;$j++){
				if($result[$j]['id'] == $listCon){
					$result[$j]['idStr'] = $dataArr[$k].',';
					$result[$j]['secondNum']=count($list)-1;
				}
			}
		}
		$this->assign('id',$id);
		$this->assign('len',$len);
		$this->assign('result',$result);
	   //加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","Role/controShow");
        $this->assign("Js","Role/controJs");
        $this->display("Conmons/Frame");
	}
    public function controSonShow($id){
		$module = M('modules');
		$result = $module->where('pid='.$id)->select();
		echo json_encode($result);
	}
	public function controAction(){
		$data = I('duty','');//dump($data);
		$id =I('id','');
		//开始处理数据
		$len=count($data);
		for($i=0;$i<$len;$i++){
			if($data[$i]){
				$dutyarr[$i]=rtrim($data[$i],',');
				$ksort=asort($dutyarr[$i]);
				$string .=$dutyarr[$i].'#';
			}
		}
		$str=rtrim($string,'#');
		
		$role = M('role');
		$result['postDuty'] = $str;
		$role->create($result);
		if($role->where('id='.$id)->save()){
			$this->redirect('Role/showRole');
		}else{
			$this->error("更新失败");
		}
	}
	//
	public function showUser(){
		$user=M('user');
		$p=I('p','');
		$count = $user->where('role=4 AND state=1 ADN uid!=1')->count();// 查询满足要求的总记录数
		$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		$data =$user->where('role=4 AND state=1 AND uid!=1')->field('uid,uname')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('row',$show);
		$this->assign('p',$p);
		$this->assign('data',$data);
		//加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","Role/showUser");
        $this->assign("Js","Role/showUserjs");
        $this->display("Conmons/Frame");
	}
	//【展示页】配置用户权限
	public function adminRole(){
		$user=M('user');
		$role=M('role');
		$users=M('user_adminrole');
		//准备用户
		$data=$user->where('role=4 AND state=1 AND uid!=1')->field('uid,uname')->select();
		//准备角色
		$map=$role->field('id,name')->select();
		
		//修改默认
		//获取UID
		$uid=I('uid','');
		//获取跳转页码
		$p=I('p','');
		$row=$users->where('uid='.$uid)->getField('rid');
		$this->assign('data',$data);
		$this->assign('map',$map);
		$this->assign('uid',$uid);
		$this->assign('row',$row);
		$this->assign('p',$p);
		//加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Js","Role/controJs");
		$this->assign("Tel","Role/adminRole");
        $this->display("Conmons/Frame");
	}
	//执行配置用户权限
	public function userRole(){
		$user=M('user_adminrole');
		$roles=I('roles','');
		$uid=I('uid','');
		//校验uid
		if(!$uid){
			$this->error("请选择用户！");
		}else{
			$data['uid']=$uid;
		}
		//校验role
		if(!$roles){
			$this->error("请选择角色！");
		}else{
			$data['rid']=$roles;
		}
		
		$map=$user->where('uid='.$uid)->field('id')->find();
		if($map){
			$user->create($data);
			if($user->where('id='.$map['id'])->save()){
				$this->redirect('Role/showUser');
			}else{
				$this->error("更新失败");
			}
		}else{
			$user->create($data);
			if($user->add()){
				$this->redirect('Role/showUser');
			}else{
				$this->error("创建失败");
			}
		}
	}
	//
	public function userMod(){
		$user=M('user');
		$uid=I('uid','');
		if(!$uid){
			$output = array(
				'status' 	=>'2',
				'message'	=>'缺少uid'
			);
			$this->ajaxReturn($output);
		}else{
			$data=$user->where('uid='.$uid)->field('uid,uname,upass,uphone,uemail,nickname,tname,sex')->find();
			$output = array(
				'status' 	=>'1',
				'message'	=>'查询成功',
				'row'		=>$data
			);
			$this->ajaxReturn($output);
		}
	}
	//校验唯一
	public function userVerify(){
		$user=M('user');
		$type=I('type','');
		$uname=I('uname','');
		$uphone=I('uphone','');
		$uemail=I('uemail','');
		$uid=I('uid','');
		if(!$type){
			$output = array(
				'status' 	=>'2',
				'message'	=>'缺少校验类型'
			);
			$this->ajaxReturn($output);
		}
		if($type==1){
			if(!$uname){
				$output = array(
					'status' 	=>'3',
					'message'	=>'缺少用户名'
				);
				$this->ajaxReturn($output);
			}else{
				if($uid){
					$tnameNum=$user->where("uname='".$uname."' AND state=1 AND uid!=".$uid)->count();
				}else{
					$tnameNum=$user->where("uname='".$uname."' AND state=1")->count();
				}
				if($tnameNum){
					$output = array(
						'status' 	=>'4',
						'message'	=>'不可为空或已存在！'
					);
					$this->ajaxReturn($output);
				}else{
					$output = array(
						'status' 	=>'1',
						'message'	=>'该用户名可用！'
					);
					$this->ajaxReturn($output);
				}
			}
		}else if($type==2){
			if(!$uphone){
				$output = array(
					'status' 	=>'3',
					'message'	=>'缺少手机号码'
				);
				$this->ajaxReturn($output);
			}else{
				if($uid){
					$uphoneNum=$user->where("uphone='".$uphone."' AND state=1 AND uid!=".$uid)->count();
				}else{
					$uphoneNum=$user->where("uphone='".$uphone."' AND state=1")->count();
				}
				if($uphoneNum){
					$output = array(
						'status' 	=>'4',
						'message'	=>'不可为空或已存在！'
					);
					$this->ajaxReturn($output);
				}else{
					$output = array(
						'status' 	=>'1',
						'message'	=>'该手机号码可用！'
					);
					$this->ajaxReturn($output);
				}
			}
		}else if($type==3){
			if(!$uemail){
				$output = array(
					'status' 	=>'3',
					'message'	=>'缺少邮箱'
				);
				$this->ajaxReturn($output);
			}else{
				if($uid){
					$uemailNum=$user->where("uemail='".$uemail."' AND state=1 AND uid!=".$uid)->count();
				}else{
					$uemailNum=$user->where("uemail='".$uemail."' AND state=1")->count();
				}
				if($uemailNum){
					$output = array(
						'status' 	=>'4',
						'message'	=>'不可为空或已存在！'
					);
					$this->ajaxReturn($output);
				}else{
					$output = array(
						'status' 	=>'1',
						'message'	=>'该邮箱可用！'
					);
					$this->ajaxReturn($output);
				}
			}
			
		}
	}
}