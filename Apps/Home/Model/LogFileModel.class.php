<?php
namespace Home\Model;
use Think\Model;
// +----------------------------------------------------------------------
// | 非速搜WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://fairso.com All rights reserved.
// +----------------------------------------------------------------------
// | 日志模块
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class LogFileModel{
    static protected $upload;
    protected $loguser;
    public $error;
   public function __construct() {
		$this->loguser=D('log_user');
		$this->logrecord=D('log_record');
		//初始化redis缓存
		$this->redis = S(array());
		
      /*  $dir = dirname(__FILE__);
       $this->upload = new Upload;
       $this->upload->maxSize   =     1048576 ;// 设置附件上传大小    
       $this->upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
       $this->upload->rootPath  = substr($dir,0,-16).'/Public';//'/home/www/html/app.langyue.org/phprksh/Public'
       $this->upload->savePath  = '/Uploads/';
       $this->upload->autoSub = true;
       $this->upload->subName = array('date','Ymd');
       $this->upload->saveName = array('uniqid',''); */
   }
   
   public function logAdd($types) {
		//准备数据包
		$data=null;
		//判断SESSION用户日志记录
		$loguserInfo=session('loguser');
		if($loguserInfo){
			if($loguserInfo['id']){
				$data['luid']=$loguserInfo['id'];
			}
			if($loguserInfo['browseragent']){
				$data['browseragent']=$loguserInfo['browseragent'];
			}
			if($loguserInfo['browserversion']){
				$data['browserversion']=$loguserInfo['browserversion'];
			}
			if($loguserInfo['browserplatform']){
				$data['browserplatform']=$loguserInfo['browserplatform'];
			}
			if($loguserInfo['ip']){
				$data['ip']=$loguserInfo['ip'];
			}
			if($loguserInfo['country']){
				$data['country']=$loguserInfo['country'];
			}
			if($loguserInfo['province']){
				$data['province']=$loguserInfo['province'];
			}
			if($loguserInfo['city']){
				$data['city']=$loguserInfo['city'];
			}
			if($loguserInfo['district']){
				$data['district']=$loguserInfo['district'];
			}
			if($loguserInfo['carrier']){
				$data['carrier']=$loguserInfo['carrier'];
			}
		}
		$data['ctime']=time();
		$data['cdate']=strtotime(date('Y-m-d',time()));
		if($types){
			if($types['typefid']){
				$data['typefid']=$types['typefid'];
			}
			if($types['typeid']){
				$data['typeid']=$types['typeid'];
			}
			if($types['typecid']){
				$data['typecid']=$types['typecid'];
			}
			if($types['title']){
				$data['title']=$types['title'];
			}
			if($types['controller']){
				$data['controller']=$types['controller'];
			}
			if($types['method']){
				$data['method']=$types['method'];
			}
			if($types['cido']){
				$data['cido']=$types['cido'];
			}
			if($types['cidt']){
				$data['cidt']=$types['cidt'];
			}
			if($types['source']){
				$data['source']=$types['source'];
			}
		}
    	//设置缓存自增id
		// $uid = $this->redis->incr("idRecord");
		// //将数据保存至缓存
		// $info = $this->redis->hmset("log_record:".$uid,$data);														
		// //将自增的uid写入缓存
		// $this->redis->rpush("redisLogRecord",$uid);
		//注释内容为Mysql新增
		$logNewid=$this->logrecord->add($data);
		return $logNewid;
		//return $_SERVER;
   }
   
   
   
}