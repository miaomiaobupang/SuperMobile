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
class MessageController extends LimitController {
	private $user;//用户表
	private $hope_disease_type;	//实例化疾病类型管理表
	private $hope_entry;	//实例化词条管理表
	private $hope_entry_info;	//实例化词条管理表
	private $hope_disease_department;	//实例化疾病科室表
	private $hope_entry_type;	//实例化词条类型表
	private $hope_message;	//实例化资讯类型表
	private $hope_uploads;	//实例化文件类型表
	private $hope_link;	//实例化全局联系表
	private $hope_question;	//实例化问题表
	private $hope_disease_kind;	//实例化疾病类型表

	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		header("Content-type: text/html; charset=utf-8");
		//实例化用户表
		$this->user  = D('user');
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
		//实例化资讯类型表
		$this->hope_message = D('hope_message');
		//实例化文件类型表
		$this->hope_uploads = D('hope_uploads');
		//实例化全局联系表
		$this->hope_link = D('hope_link');
		//实例化问题表
		$this->hope_question = D('hope_question');
		//实例化疾病类型表
		$this->hope_disease_kind = D('hope_disease_kind');
		
		//判断是否登录
		$this->session = $_SESSION['user'];
	}
	
	//疾病列表页
	public function index(){
		//Banner
		$messageArr = array(
						0=>5905,
						1=>5903,
						2=>5902
					);
		$mCount = count($messageArr);	
		for($i=0;$i<$mCount;$i++){
			$fielID = $this->hope_message->where('id='.$messageArr[$i])->getField('file_id');
			$banner[$i]['id'] = $messageArr[$i];
			$banner[$i]['img'] = $this->hope_uploads->where('id='.$fielID)->getField('url');
		}
		$this->assign('banner',$banner);
		//最新信息
		$NewMessage = $this->hope_message->where('type = 1 && sys_type = 1 && state=1 || type=4 && sys_type = 1 && state=1')->limit(0,7)->order('stime desc')->select();
		$this->assign('NewMessage',$NewMessage);
		$this->assign('NewMessageS',$NewMessage);
		//最热信息
		$HotMessage = $this->hope_message->where('state=1 && sys_type = 1')->limit(0,5)->order('view_num desc')->select();
		$this->assign('HotMessage',$HotMessage);
		$this->assign('HotMessageS',$HotMessage);
		//公益活动前两条
		$oneArr = $this->hope_message->where('type=4 && state=1')->limit(0,7)->order('stime desc')->select();
		$oneCount = count($oneArr);
		for($i=0;$i<$oneCount;$i++){
			$oneArr[$i]['img'] = $this->hope_uploads->where('id='.$oneArr[$i]['file_id'])->getField('url');
			$oneArr[$i]['ctime'] = date('Y-m-d H:i',$oneArr[$i]['stime']);
		}
		$this->assign('oneArr',$oneArr);
		$this->assign('oneArrS',$oneArr);
		//最新药物
		$NewPill = $this->hope_entry->where('entry_type_id=4 && sys_type = 1&& state=1')->order('id desc')->limit(0,4)->select();
		$threeCount = count($NewPill);
		for($i=0;$i<$threeCount;$i++){
			$ine_file_id = null;
			$ine_file_id = $this->hope_entry_info->where('id='.$NewPill[$i]['entry_info_id'])->getField('file_id');
			$NewPill[$i]['img'] = $this->hope_uploads->where('id='.$ine_file_id)->getField('url');
		}
		$this->assign('NewPill',$NewPill);
		//最新专家、医院信息
		$newPublic = $this->hope_message->where('type = 6 && sys_type = 1 && state=1 || type=7 && sys_type = 1 && state=1')->order('stime desc')->limit(0,6)->select();
		$threeCount = count($newPublic);
		for($i=0;$i<$threeCount;$i++){
			$newPublic[$i]['img'] = $this->hope_uploads->where('id='.$newPublic[$i]['file_id'])->getField('url');
		}
		$this->assign('newPublic',$newPublic);
		//权威医院
		$proHospital = $this->hope_entry->where('entry_type_id=3&& state=1')->order('view_num desc')->limit(0,3)->select();
		$threeCount = count($proHospital);
		for($i=0;$i<$threeCount;$i++){
			$ine_file_id = null;
			$ine_file_id = $this->hope_entry_info->where('id='.$proHospital[$i]['entry_info_id'])->getField('file_id');
			$proHospital[$i]['rank'] = $this->hope_entry_info->where('id='.$proHospital[$i]['entry_info_id'])->getField('rank_hospital');
			$proHospital[$i]['img'] = $this->hope_uploads->where('id='.$ine_file_id)->getField('url');
		}
		$this->assign('proHospital',$proHospital);
		//权威专家
		$proDoctor = $this->hope_entry->where('entry_type_id=2&& state=1')->order('view_num desc')->limit(0,3)->select();
		$threeCount = count($proDoctor);
		for($i=0;$i<$threeCount;$i++){
			$ine_file_id = null;
			$ine_file_id = $this->hope_entry_info->where('id='.$proDoctor[$i]['entry_info_id'])->getField('file_id');
			$proDoctor[$i]['img'] = $this->hope_uploads->where('id='.$ine_file_id)->getField('url');
			$departmentArr = $this->hope_link->where('sign_id='.$proDoctor[$i]['id'].'&& type=1 && sign_type=2 && state=1')->select();
			$dCount = count($departmentArr);
			for($m=0;$m<$dCount;$m++){
				$proDoctor[$i]['department'] .= $this->hope_disease_department->where('id='.$departmentArr[$m]['department_id'])->getField('cname');
			}
		}
		$this->assign('proDoctor',$proDoctor);
		//最热疾病词条数据
		$hotDease = $this->hope_entry->where('entry_type_id = 1 && sys_type = 1 && statecode =1 && state =1')->field('id,pid,cname,view_num')->order('view_num desc')->limit(25)->select();
		$this->assign('hotDease',$hotDease);
		//最新回答
		$oneNewQuestion = $this->hope_question->where('state=1')->limit(0,4)->order('id desc')->select();
		$this->assign('oneNewQuestion',$oneNewQuestion);
		$this->display('consultation');
	}	
	
	//疾病详情页
	public function detail(){
		$entryID = I('id');
		if(empty($entryID)){
			$this->error('词条ID类型不能为空');
		}
		$data = $this->hope_message->where('id='.$entryID.'&& state = 1')->find();
		// dump($data);die();
		if(!$data){
			$this->error('请求数据异常');
		}
		$numAuto = $data['view_num']+1;
		$this->hope_message->where('id='.$entryID)->setField('view_num',$numAuto);
		//词条类型
		$data['entry_type'] = $this->hope_entry_type->where('id='.$data['entry_type_id'])->getField('name');
		//疾病类型
		$data['disease_type'] = $this->hope_disease_type->where('id='.$data['disease_id'])->getField('name');
		$data['disease_zid'] = $data['disease_id'];
		$data['disease_fid'] = $this->hope_disease_type->where('id='.$data['disease_id'].'&& level =2')->getField('fid');
		$data['ctime'] = date('Y-m-d H:i:s',$data['ctime']);
		$data['img'] = $this->hope_uploads->where('id='.$data['file_id'])->getField('url');
		
		//专家报道(医疗前沿)
		$sql2 = "SELECT * FROM `hope_message`  WHERE `type` = '6' AND `state` = '1' LIMIT 0,4";
		$report = D()->query($sql2);
		$oneCount = count($report);
		for($i=0;$i<$oneCount;$i++){
			$report[$i]['img'] = $this->hope_uploads->where('id='.$report[$i]['file_id'])->getField('url');
		}
		//其他专家
		$expert = $this->hope_entry->where('entry_type_id = 2&& state =1')->field('id,pid,entry_info_id,cname')->limit(0,3)->select();
		$twoCount = count($expert);
		for($m=0;$m<$twoCount;$m++){
			$entry_file_id = $this->hope_entry_info->where('id='.$expert[$m]['entry_info_id'])->getField('file_id');
			$expert[$m]['img'] = $this->hope_uploads->where('id='.$entry_file_id)->getField('url');
		}
		//专家问答
		$question = $this->hope_question->where('answer_num > 0 && state=1')->limit(0,4)->order('id desc')->select();
		
		$this->assign("question",$question);
		$this->assign("report",$report);
		$this->assign("expert",$expert);
		$this->assign("data",$data);
		$this->display('message');
	}
	
	//列表页
	public function messList(){
		$sign_type = I('type',0);
		$disease_id = I('disease_id',0);
		$department_id = I('department_id',0);
		$kind_id = I('kind_id',0);
		if($sign_type != 0){
			$where['sign_type'] = array('eq',$sign_type);
		}
		$where['type'] = 2;
		if($disease_id != 0){
			$where['disease_id'] = array('eq',$disease_id);
		}
		if($department_id != 0){
			$where['department_id'] = array('eq',$department_id);
		}
		if($kind_id != 0){
			$where['kind_id'] = array('eq',$kind_id);
		}
		
		// $where['statecode'] = array('eq',1);
		$where['state'] = array('eq',1);
		import('ORG.Util.Page');// 导入分页类
		$count = $this->hope_link->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$list = $this ->hope_link->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime desc') ->select();
		$show = $Page->show();// 分页显示输出
		$this->assign('show',$show);
		$count = count($list);
		// dump($list);die;
		for($i=0;$i<$count;$i++){
			$newList[$i] = $this->hope_message->where('id='.$list[$i]['sign_id'])->find();
			$newList[$i]['img'] = $this->hope_uploads->where('id='.$newList[$i]['file_id'])->getField('url');
		}
		$hotMessage = $this->hope_message->where('state=1 && statecode')->limit(0,5)->order('view_num DESC')->select();
		
		//疾病筛选条件
		if($sign_type == 1 || $sign_type == 4 || $sign_type == 6){
			$data = $this->hope_disease_type->where('state=1 && level = 1')->select();
			$countOne = count($data);
			for($i=0;$i<$countOne;$i++){
				$info = null;
				$info = $this->hope_disease_type->where('state=1 && level = 2 && fid='.$data[$i]['id'])->select();
				$countTwo = count($info);
				$listHTML = null;
				for($m=0;$m<$countTwo;$m++){
					$status = $this->hope_link->where('disease_id='.$info[$m]['id'])->select();
					// dump($status);
					if($status){
						if($disease_id == $info[$m]['id']){
							$listHTML .= '<a href='.C('WEBURL').'/Message/messList?disease_id='.$info[$m]['id'].'&type='.$sign_type.'><span title="'.$info[$m]['name'].'" class="keywords selectedKey">'.$info[$m]['name'].'</span></a>';
						}else{
							$listHTML .= '<a href='.C('WEBURL').'/Message/messList?disease_id='.$info[$m]['id'].'&type='.$sign_type.'><span title="'.$info[$m]['name'].'" class="keywords">'.$info[$m]['name'].'</span></a>';	
						}
					$data[$i]['m'][$m]['id'] = $info[$m]['id'];
						$data[$i]['m'][$m]['name'] = $info[$m]['name'];
						$data[$i]['listHtml'] = $listHTML;
					}
				}
			}
			$this->assign('data',$data);
		}else if($sign_type == 2){
			$data = $this->hope_disease_department->where('state=1')->select();
			$newData .= '<a href="'.C('WEBURL').'/Message/messList?department_id=0&type='.$sign_type.'"><span title="'.$data[$i]['cname'].'" class="keywords">全部</span></a>';
			$countOne = count($data);
			for($i=0;$i<$countOne;$i++){
				if($department_id == $data[$i]['id']){
					$newData .= '<a href='.C('WEBURL').'/Message/messList?department_id='.$data[$i]['id'].'&type='.$sign_type.'><span title="'.$data[$i]['cname'].'" class="keywords selectedKey">'.$data[$i]['cname'].'</span></a>';
				}else{
					$newData .= '<a href='.C('WEBURL').'/Message/messList?department_id='.$data[$i]['id'].'&type='.$sign_type.'><span title="'.$data[$i]['cname'].'" class="keywords">'.$data[$i]['cname'].'</span></a>';
				}
			}
			$this->assign('data',$newData);
		}else if($sign_type == 7){
			$data = $this->hope_disease_kind->where('state=1')->select();
			$newData .= '<a href="'.C('WEBURL').'/Message/messList?kind_id=0&type='.$sign_type.'"><span title="'.$data[$i]['name'].'" class="keywords">全部</span></a>';
			$countOne = count($data);
			for($i=0;$i<$countOne;$i++){
				if($kind_id == $data[$i]['id']){
					$newData .= '<a href='.C('WEBURL').'/Message/messList?kind_id='.$data[$i]['id'].'&type='.$sign_type.'><span title="'.$data[$i]['name'].'" class="keywords selectedKey">'.$data[$i]['name'].'</span></a>';
				}else{
					$newData .= '<a href='.C('WEBURL').'/Message/messList?kind_id='.$data[$i]['id'].'&type='.$sign_type.'><span title="'.$data[$i]['name'].'" class="keywords">'.$data[$i]['name'].'</span></a>';
				}
			}
			$this->assign('data',$newData);
		}
		$this->assign('hotMessage',$hotMessage);
		$this->assign('list',$newList);
		$this->assign('type',$sign_type);
		$this->display('messlist');
	}
}