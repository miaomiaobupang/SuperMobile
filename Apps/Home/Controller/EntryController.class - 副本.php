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
class EntryController extends LimitController {
	private $user;//用户表
	private $hope_disease_type;	//实例化疾病类型管理表
	private $hope_entry;	//实例化词条管理表
	private $hope_entry_info;	//实例化词条管理表
	private $hope_disease_department;	//实例化疾病科室表
	private $hope_entry_info_catalog;	//实例化词条目录管理表
	private $hope_entry_type;	//实例化词条类型表

	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		//实例化用户表
		$this->user     = D('user');
		//实例化疾病类型管理表
		$this->hope_disease_type = D('hope_disease_type');
		//实例化词条管理表
		$this->hope_entry = D('hope_entry');
		//实例化疾病科室表
		$this->hope_disease_department = D('hope_disease_department');
		//实例词条主体内容管理表
		$this->hope_entry_info = D('hope_entry_info');
		//实例化词条类型表
		$this->hope_entry_type = D('hope_entry_type');
		//实例化词条目录管理表
		$this->hope_entry_info_catalog = D('hope_entry_info_catalog');
		
		//判断是否登录
		$this->session = $_SESSION['user'];
	}
	
	//疾病列表页
	public function index(){
		//最热疾病词条数据
		$hotDease = $this->hope_entry->where('statecode =1 && state =1')->field('id,cname,view_num')->order('view_num desc')->limit(20)->select();
		$this->assign('hotDease',$hotDease);
		//更多疾病
		$moreDease = $this->hope_disease_type->where('level=1 && state =1')->field('id,name')->select();
		$this->assign('moreDease',$moreDease);
		//默认的头颈部疾病
		$headDisease = $this->hope_disease_type->where('level=2 && fid= 1 && state =1')->field('id,name')->select();
		$this->assign('headDisease',$headDisease);
		//头部广告Banner
		//季节高发病
		//常见疾病
		$this->display('diseaseList');
	}	
	
	//疾病详情页
	public function detail(){
		$entryID = I('id');
		if(empty($entryID)){
			$this->error('词条ID类型不能为空');
		}
		$data = $this->hope_entry->where('id='.$entryID)->find();
		$numAuto = $data['view_num']+1;
		$this->hope_entry->where('id='.$entryID)->setField('view_num',$numAuto);
		//词条类型
		$data['entry_type'] = $this->hope_entry_type->where('id='.$data['entry_type_id'])->getField('name');
		//疾病类型
		$data['disease_type'] = $this->hope_disease_type->where('id='.$data['disease_id'])->getField('name');
		$data['disease_zid'] = $data['disease_id'];
		$data['disease_fid'] = $this->hope_disease_type->where('id='.$data['disease_id'].'&& level =2')->getField('fid');
		$data['ctime'] = date('Y-m-d H:i:s',$data['ctime']);
		//词条主体内容
		$entry_info = $this->hope_entry_info->where('id='.$data['entry_info_id'])->find();
		//摘要
		$data['abstract'] = $entry_info['abstract'];
		//参考资料名称
		$data['dataname'] = $entry_info['dataname'];
		//参考资料来源
		$data['dataurl'] = $entry_info['dataurl'];
		//词条目录内容
		$catalog_info = $this->hope_entry_info_catalog->where('entry_info_id='.$data['entry_info_id'].'&& level=1')->field('id,name,content,level')->select();
		$oneCount = count($catalog_info);
		for($i=0;$i<$oneCount;$i++){
			$twoArr = array();
			$twoArr = $this->hope_entry_info_catalog->where('fid='.$catalog_info[$i]['id'].'&& level=2')->field('id,name,content,level')->select();
			$catalog_info[$i]['two'] = $twoArr;
		}
		$data['entry_content'] = $catalog_info;
		//加载左侧导航菜单缓存
		//生成词条目录
		// $twoCount = count($data['entry_content']);
		// $entryHTML = '';
		// for($i=0;$i<$twoCount;$i++){
			// $entryHTML .= '<div class="catalogBox fl">';
				// $entryHTML .= '<li class="catalogTitle">'.$data['entry_content'][$i]['name'].'</li>';  
				// $threeCount = count($data['entry_content'][$i]['two']);
				// for($m=0;$m<$threeCount;$m++){
					// $entryHTML .= '<li>---<span class="catalogText">'.$data['entry_content'][$i]['two'][$m]['name'].'</span></li>'; 
				// }   
			// $entryHTML .= '</div>';
		// }
		
		
		// echo $entryHTML;die();
		$this->assign("data",$data);
		$this->display('disease');
	}
	
	
	
	
	
}