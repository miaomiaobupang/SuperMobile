<?php
namespace Home\Controller;
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
class IndexController extends LimitController {
	private $user;//用户表
	private $hope_disease_type;	//实例化疾病类型管理表
	private $hope_entry;	//实例化词条管理表

	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		header("Content-Type:text/html;charset=UTF-8");
		//实例化用户表
		$this->user     = D('user');
		//实例化疾病类型管理表
		$this->hope_disease_type = D('hope_disease_type');
		//实例化词条管理表
		$this->hope_entry = D('hope_entry');
		
		//判断是否登录
		$this->session = $_SESSION['user'];
	}
	public function index(){
		$data = $this->hope_disease_type->where('state=1 && level = 1')->select();
		$countOne = count($data);
		for($i=0;$i<$countOne;$i++){
			$info = null;
			$info = $this->hope_disease_type->where('state=1 && level = 2 && fid='.$data[$i]['id'])->select();
			$countTwo = count($info);
			$listHTML = null;
			for($m=0;$m<$countTwo;$m++){
				$listHTML .= '<a href="'.$info[$m]['id'].'"><span title="'.$info[$m]['name'].'" class="keywords">'.$info[$m]['name'].'</span></a>';
				$data[$i]['m'][$m]['id'] = $info[$m]['id'];
				$data[$i]['m'][$m]['name'] = $info[$m]['name'];
				$data[$i]['listHtml'] = $listHTML;
			}
		}
		// dump($data);die();
		//疾病类型
		$this->assign('data',$data);
		//超级医生
		$doctor = $this->hope_entry->where('entry_type_id=2 && statecode =1 && state=1')->limit('0,8')->select();
		$this->assign('doctor',$doctor);
		$this->display();
	}

	//测试中文转拼音
	function ChineseToPinyins(){
		$result = ChineseToPinyin('中国人');
		dump($result);
	}

}