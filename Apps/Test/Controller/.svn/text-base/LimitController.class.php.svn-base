<?php
namespace Home\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | 爱能社
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://langyue.org All rights reserved.
// +----------------------------------------------------------------------
// | 公共层
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class LimitController extends Controller {
	
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