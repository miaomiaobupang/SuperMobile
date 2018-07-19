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
class LoginController extends Controller {
    public function index(){
        $this->display("index");
    }
    public function check(){
    	$username = trim($_POST['username']);
    	$password = md5(trim($_POST['password']));
    	$user = M('user');
    	$userResult = $user->query("select * from user where uname='".$username."' and upass='".$password."'");
    	if($userResult){
            session('uid',$userResult[0]['uid']);
			session('user',$userResult[0]);
            echo 1;
			// dump($userInfo);exit;
    	}else{
    		echo 2;
    	}
    }
    public function loginOut(){
         //缓存初始化
	   	$cache = S(array());
        $_SESSION = array();
	    if(isset($_COOKIE[session_name()])){
	        @setCookie(session_name(),'',time()-100,'/');
	    }
        S('postDuty',null); 
		//清除菜单
		session('LeftNav',null);
		//清除权限
		session('postDuty',null); 
		session_destroy();
    	$this->redirect("Index/index");
    }
}