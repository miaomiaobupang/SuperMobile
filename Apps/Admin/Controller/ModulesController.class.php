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
class ModulesController extends LimitController {
    public function index(){
        $this->show();
    }
    //显示模块列表
    public function show(){
        $modules = M('Modules');		
		//判断模块性质
		if($_GET['id']){		//为标准模块
			$where['pid'] = $_GET['id'];
			$where['properties'] = 2;
			$list = $modules->where($where)->order('num')->select();
			$this->assign("tit","权限");
			$this->assign("properties","2");
		}else{		//为权限属性
			$where['pid'] = 0;
			$where['properties'] = 1;
			$list = $modules->where($where)->select();		
			if($list){
				//处理相关数据
				for($i=0;$i<count($list);$i++){
					$pid = $list[$i]['id'];
					$where['pid'] = $pid;
					$where['properties'] = 2;
					$arr = $modules->where($where)->order('num')->select();
					
					if($arr){
						//处理数据
						for($j=0;$j<count($arr);$j++){
							$list[$i]['child'].= $arr[$j]['name'].",";
						}
					}
				}
			}
			$this->assign("tit","模块");
			$this->assign("properties","1");
		}		
        $this->assign('list',$list);
		//加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","Modules/show");
        $this->assign("Js2","Modules/pagingjs");
		$this->display("Conmons/Frame");
    }
    
    //显示添加页
    public function add(){
		$modules = M('Modules');
        if($_GET['id']){            //若获取id则为添加权限
			$this->assign("tit","权限");
			$this->assign("properties","2");
			$this->assign("pid",$_GET['id']);
            $this->assign("vol",M('Modules')->find($id));
        }else{                      //若获取不到id则为顶级模块添加
			$this->assign("tit","模块");
			$this->assign("properties","1");
            $this->assign("vol","1");
        }
       //获取顶级菜单列表
	   $where['pid'] = 0;
	   $where['properties'] = 1;   
	   $pids=$modules->where($where)->select();
	   $this->assign("pidsMenu",$pids);
	   //加载JS文件
	   $this->assign("Js","Modules/addjs");
	   //加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
	   $this->assign("Tel","Modules/add");
	   $this->display("Conmons/Frame");
    }
    //执行添加
    public function insert(){
        $modules = M('Modules');
		//公共部分
		$data['type'] = $_POST['type'];
		$data['properties'] = $_POST['properties'];
		$data['state'] = $_POST['state'];
		$data['name'] = $_POST['name'];
		$data['describe'] = $_POST['describe'];		
		//判断模块性质
		if($_POST['properties'] == 1){		//标准模块
			$data['Controller'] = $_POST['Controller'];			
		}else{		//权限属性
			$data['pid'] = $_POST['pid'];
			$pid = $_POST['pid']; //获取模块ID
			$data['Controller'] =$modules->getFieldById($_POST['pid'],'Controller');
			$data['Method'] = $_POST['Method'];
		}
        if($modules->add($data)){            //判断是否添加成功
            $this->success('新增成功',U("Modules/show"));
        }else{                          //若添加失败则返回报错
            $this->error('新增失败，请返回重试');
        }
    }
    //显示修改页
   public function edit($id=0){
		$modules = M('Modules');
		//获取模块修改信息
		$result=$modules->find($id);
		//判断模块性质
		if($result['properties'] ==1){		//为标准模块
			$this->assign("tit","模块");
		}else{		//为权限属性
			$this->assign("tit","权限");
		}
       $this->assign("vo",$result);
	   //加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","Modules/edit");
		$this->display("Conmons/Frame");
   }
   //执行修改
   public function update(){
       $id = $_POST['id'];
       $modules = M('Modules');
       $modules->create();
       if($modules->where('id='.$id)->save()){        //判断是否修改成功
			$this->success("修改成功",U("Modules/show"));
		}else{                      //若不成功则返回失败
			$this->error("修改失败！");
		}
   }
   //执行删除操作
   public function del($id=0){
       $modules = M('Modules');
       if($modules->delete($id)){   //判断删除是否成功
			$this->success("删除成功",U("Modules/show"));
       }else{                       //若不成功则返回失败
			$this->error("删除失败！");
	   } 
   }
   //模块选择API
   public function ModuleAPI(){
	   //加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
	   $this->assign("Tel","Modules/ModuleAPI");
		//加载内容页相关JS模板文件
		$this->assign("Js","Modules/ModuleAPIjs");
		$this->display("Conmons/Frame");
   }
   //模块数据输出
   public function ModuleDataEcho(){
	   $vo = M('modules') -> where('properties = 1') ->select();
	   echo json_encode($vo);
   }
   //通过模块ID输出方法信息
   public function ModuleIDMethodEcho($id){
	   $vo = M('modules') -> where('properties = 2 AND pid ='.$id) ->select();
	   echo json_encode($vo);
   }
}