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
class LogController extends LimitController {
    //日志信息内容显示
    public function index(){
    	$log = M();
    	$result = $log->query("select l.id,u.numbere,u.coding,l.uname,l.enternum,l.time,l.ip,l.port,l.type,l.state,m.name,l.matter from log l,user u,modules m where l.uid=u.uid and l.moduleid=m.id");
    	//循环替换时间戳
    	$count = count($result);
    	 for($i=0;$i<$count;$i++){
    	 	date_default_timezone_set('PRC');
    	 	$result[$i]['time'] =date("Y-m-d H:i:s",$result[$i]['time']);
    	 }
    	 $this->assign('list',$result);
		//加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",$cache->LeftNav); 
        $this->assign("Tel","Log/index");
        $this->assign("Js","Log/pagingjs");
		$this->display("Conmons/Frame");
    }
    //执行日志删除
   public function del($id){
   		$log = M('log');
   		if($log->delete($id)){
   			$this->success('删除成功',U('Log/index'));
   		}else{
   			$this->error('删除失败',U('Log/index'));
   		}
   }
}