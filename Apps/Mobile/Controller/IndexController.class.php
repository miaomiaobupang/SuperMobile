<?php
namespace Mobile\Controller;
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
class IndexController extends Controller{
	private $hope_disease_type;

	/**
	 * 构造方法
	 */
	public function __construct() {
		$this->hope_disease_type = D('hope_disease_type');
		parent::__construct();
		header("Content-Type:text/html;charset=UTF-8");
	}
	public function index(){
		$this->display();
	}
	
	//专家频道页
	public function expertIndex(){
		$this->display();
	}
	
	//专家列表页
	public function expertList(){
		$this->display();
	}
	
	//专家详情页
	public function expertDetail(){
		$this->display();
	}
	
	//医院频道页
	public function hospitalIndex(){
		$this->display();
	}
	
	//医院列表页
	public function hospitalList(){
		$this->display();
	}
	//医院列表页
	public function hospitalDetail(){
		$this->display();
	}
	//疾病频道页
	public function diseaseIndex(){
		$this->display();
	}
	//问答频道页
	public function questionIndex(){
		$this->display();
	}
	//问答列表页
	public function questionList(){
		$diseaseArr = $this->hope_disease_type->where('level=1')->field('id,name')->select();
		
		$dc = count($diseaseArr);
		for($d=0;$d<$dc;$d++){
			$diseaseArr[$d]['text'] = $diseaseArr[$d]['name'];
			$diseaseArr[$d]['children'] = $this->hope_disease_type->where('fid='.$diseaseArr[$d]['id'])->field('id,name')->select();
			$cc = count($diseaseArr[$d]['children']);
			for($c=0;$c<$cc;$c++){
				if($c == 0){
					$diseaseArr[$d]['children'][0]['id'] = $diseaseArr[$d]['id'];
					$diseaseArr[$d]['children'][0]['text'] = $diseaseArr[$d]['name'];
				}else{
					$diseaseArr[$d]['children'][$c]['text'] = $diseaseArr[$d]['children'][$c]['name'];
				}
				unset($diseaseArr[$d]['children'][$c]['name']);
			}
			unset($diseaseArr[$d]['name']);
		}
		$disonJSON = json_encode($diseaseArr);
		$this->assign('diseaseArr',$disonJSON);
		// $diseaseArr = $this->hope_disease_type->where('level=1')->select();
		// dump($diseaseArr);
		$this->display();
	}
	//问答详情页
	public function questionDetails(){
		$this->display();
	}
	//前沿资讯
	public function newMessages(){
		$this->display();
	}
	
}