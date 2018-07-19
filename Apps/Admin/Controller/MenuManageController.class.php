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
class MenuManageController extends LimitController {
	private $hope_log_function;	//实例化词条管理表
	/**
	* 构造方法
	*/
	public function __construct() {
		parent::__construct();
		//实例化词条类型表
		$this->hope_log_function = D('hope_log_function');
		$this->session = $_SESSION['user'];
	}
	
    //部门管理首页显示
    public function index(){
        $this->redirect('/MenuManage/show');
    }
    //菜单（二级）展示
   public function show(){
		$menu = M('menu');
		$list = $menu->order('path')->select();
		//对数据进行处理
		for($i=0;$i<count($list);$i++){
			if($list[$i]['pid'] == 0){
				//判断是否有子菜单
				$result = $menu -> where('pid ='.$list[$i]['id']) ->select();
				if($result){
					//有子菜单
					$list[$i]['pidState'] = 1;
				}else{
					//没有子菜单
					$list[$i]['pidState'] = 0;
				}			
			}else{
				//没有子菜单
				$list[$i]['pidState'] = 0;
				$list[$i]['class'] = "child child".$list[$i]['pid'];
			}
			//输出层级符
			$m = substr_count($list[$i]['path'],',')-1;
			$list[$i]['nbsp'] = str_repeat("&nbsp",$m*4);
		}
		//获取顶级菜单列表
		$pidsMenu = M('menu') ->where('pid = 0') ->select();
		$this->assign('pidsMenu',$pidsMenu);
		$this->assign('list',$list);
		//加载左侧导航菜单缓存
		//缓存初始化
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","MenuManage/show");
		//加载内容页相关JS模板文件
		$this->assign("Js","MenuManage/showjs");
		$this->display("Conmons/Frame");
   }
   
   //前台页面菜单管理
   public function prevMenu(){
	    $data = $this->hope_log_function->where('state=1 && level=1')->select();
		$countOne = count($data);
		for($i=0;$i<$countOne;$i++){
			$data[$i]['two'] = $this->hope_log_function->where('state=1 && level =2 && fid='.$data[$i]['id'])->select();
			// dump($data[$i]['two']);
			$countTwo = count($data[$i]['two']);
			for($m=0;$m<$countTwo;$m++){
				$data[$i]['two'][$m]['three'] = $this->hope_log_function->where('state=1 && level =3 && fid='.$data[$i]['two'][$m]['id'])->select();
			}
			
		}
		$this->assign('data',$data);
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","MenuManage/prevMenu");
		//加载内容页相关JS模板文件
		$this->assign("Js","MenuManage/prevMenujs");
		$this->display("Conmons/Frame");
   }
   
   //执行添加菜单数据
   public function insertTwo(){
		$type = I('type','');
		$userInfo = $this->session;
		$data['uid'] = $userInfo['uid'];
		$data['ctime'] = time();
		if($type == 1){
			$data['name'] = I('mnameone','');
			$data['pathname'] = I('pnameone','');
			$data['level'] = 1;
			$data['is_menu'] = 2;
		}else if($type == 2){
			$data['fid'] = I('modelid','');
			$data['name'] = I('mnametwo','');
			$data['pathname'] = I('pnametwo','');
			$data['level'] = 2;
			$data['is_menu'] = 2;
		}elseif($type == 3){
			$data['fid'] = I('funcid','');
			$data['name'] = I('mnamethree','');
			$data['pathname'] = I('pnamethree','');
			$data['level'] = 3;
			$data['is_menu'] = I('ismenu','');
		}else{
		  $this->error('添加失败,传输数据异常');
		}
		$state = $this->hope_log_function->add($data);
		if($state){
			$this->redirect('MenuManage/prevMenu');
		}else{
		  $this->error('添加失败');
		}
   }
   
   //加载添加广告的页面
   public function prevAdvert(){
	    $data = $this->hope_log_function->where('state=1 && level=1')->select();
		$countOne = count($data);
		for($i=0;$i<$countOne;$i++){
			$data[$i]['two'] = $this->hope_log_function->where('state=1 && level =2 && fid='.$data[$i]['id'])->select();
			// dump($data[$i]['two']);
			$countTwo = count($data[$i]['two']);
			for($m=0;$m<$countTwo;$m++){
				$data[$i]['two'][$m]['three'] = $this->hope_log_function->where('state=1 && level =3 && fid='.$data[$i]['two'][$m]['id'])->select();
				//获取作为首页、列表页等公共页面
				$indexData[$m] = $this->hope_log_function->where('state=1 && level =3 && is_menu=1 && fid='.$data[$i]['two'][$m]['id'])->select();
			}
			
		}
		$countThree = count($indexData);
		$indexInfo = null;
		$indexInfo .= '<select style="color:black;">';
		$indexInfo .= '<option>--请选择菜单--</option>';
		for($n=0;$n<$countThree;$n++){
				$countFour = count($indexData[$n]);
				for($o=0;$o<$countFour;$o++){
					$indexInfo .= '<option data-id='.$indexData[$n][$o]['id'].'>'.$indexData[$n][$o]['name'].'</option>';
				}
		}
		$indexInfo .= '</select>';
		$this->assign('indexData',$indexInfo);
		$this->assign('data',$data);
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","MenuManage/prevAdvert");
		//加载内容页相关JS模板文件
		$this->assign("Js","MenuManage/prevAdvertjs");
		$this->display("Conmons/Frame");
   }
   
   //菜单数据json输出（单条请指定ID）
   public function MenuDataEcho(){
	   if($_GET['id']){		//查询指定菜单数据
		   $vo = M('menu') -> where('id ='.$_GET['id']) -> find();
	   }else{				//查询全部菜单数据
		   $vo = M('menu') -> select();
	   }
	   echo json_encode($vo);
   }
   //菜单数据新增
   public function insert(){
	   //公共部分
	   $data['model'] = $_POST['model'];
	   $data['name'] = $_POST['name'];
	   $data['state'] = $_POST['state'];
	   $data['num'] = $_POST['num'];
	   $data['ico'] = $_POST['ico'];
	   //判断菜单模式
	   if($_POST['model'] == 2){	//二级菜单
		   $data['type'] = $_POST['type'];
		   $data['pid'] = $_POST['pid'];
		   //获取上级菜单路径
		   $data['path'] = M('menu') ->getFieldById($_POST['pid'],"path");
		   $data['adress'] = $_POST['adress'];
		   //判断菜单链接类型
		   if($data['type'] ==1){
			   $data['mid'] = $_POST['mid'];
		   }
	   }else{	//顶级菜单
		   $data['pid'] = 0;
		   $data['path'] = "0";
	   }
		//执行添加菜单数据
		$result = M('menu')->add($data); // 写入数据到数据库
		if($result){
			$menuid = $result;
			$path = $data['path'].",".$menuid;
			if(M('menu')-> where('id='.$menuid)->setField('path',$path)){
				$this->success('添加成功',U('MenuManage/show'));
			}else{
				$this->error('添加失败',U('MenuManage/show'));
			}
		}
   }
   //菜单修改
   public function MenuDataSubmit($type,$id){
	   $Menu = M('menu');
	   if($type ==1){	//顶级菜单修改
		   $data['name'] = $_POST['name'];
		   $data['num'] = $_POST['num'];
		   $data['state'] = $_POST['state'];
		   $data['ico'] = $_POST['ico'];
		   if( $Menu->where('id='.$id)->save($data)){
			   $this->redirect('MenuManage/show');
		   }else{
			   $this->error('修改失败',U('MenuManage/show'));
		   }
	   }else{	//二级菜单修改
		   $data['name'] = $_POST['name'];
		   $data['num'] = $_POST['num'];
		   $data['state'] = $_POST['state'];
		   $data['ico'] = $_POST['ico'];
		   $data['mid'] = $_POST['mid'];
		   $data['adress'] = $_POST['adress'];
		   if( $Menu->where('id='.$id)->save($data)){
			   $this->redirect('MenuManage/show');
		   }else{
			   $this->error('修改失败',U('MenuManage/show'));
		   }
	   }
   }
	//菜单删除
   public function MenuDataDel(){
        $Menu = M('menu');
        if($Menu->delete($_GET['id'])){
            $this->redirect('MenuManage/show');
        }else{
            $this->error('删除失败',U('MenuManage/show'),1);
        }
    }
}