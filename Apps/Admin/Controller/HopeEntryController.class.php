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
class HopeEntryController extends LimitController {
	private $hope_entry_type;	//实例化词条类型表
	private $hope_user;	//实例化用户表
	private $hope_disease_department;	//实例化疾病科室表
	private $hope_disease_type;	//实例化疾病类型管理表
	private $hope_entry_info;	//实例化词条主体内容管理表
	private $hope_entry_info_catalog;	//实例化词条目录管理表
	private $hope_entry;	//实例化词条管理表
	private $hope_uploads;	//实例化文件管理表
	/**
	* 构造方法
	*/
	public function __construct() {
		parent::__construct();
		//实例化词条类型表
		$this->hope_entry_type = D('hope_entry_type');
		//实例化用户表
		$this->hope_user = D('user');
		//实例化疾病科室表
		$this->hope_disease_department = D('hope_disease_department');
		//实例化疾病类型管理表
		$this->hope_disease_type = D('hope_disease_type');
		//实例词条主体内容管理表
		$this->hope_entry_info = D('hope_entry_info');
		//实例化文件管理表
		$this->hope_uploads = D('hope_uploads');
		//实例化词条目录管理表
		$this->hope_entry_info_catalog = D('hope_entry_info_catalog');
		//实例化词条管理表
		$this->hope_entry = D('hope_entry');
		$this->session = $_SESSION['user'];
	}
	
	//词条列表页
	public function entry_list(){
		$userInfo = $this->session;
		$datas['uid'] = $userInfo['uid'];
		$data = $this->hope_entry->select();
		$count = count($data);
		for($i=0;$i<$count;$i++){
			$data[$i]['ctime'] = date('Y-m-d H:i:s',$data[$i]['ctime']);
			if(!$data[$i]['stime']){
				$data[$i]['stime'] = '- - -';
			}else{
				$data[$i]['stime'] = date('Y-m-d H:i:s',$data[$i]['stime']);
			}
			
			$data[$i]['uname'] = $this->hope_user->where('uid='.$data[$i]['uid'])->getField('tname');
			$data[$i]['entry_type'] = $this->hope_entry_type->where('id='.$data[$i]['entry_type_id'])->getField('name');
			$disArr = explode(',',rtrim($data[$i]['disease_id'],','));
			$disCount = count($disArr);
			for($m=0;$m<$disCount;$m++){
				$newDis = $this->hope_disease_type->where('id='.$disArr[$m])->getField('name');
				$data[$i]['desease_type'] .= $newDis.'-';
			}
			$data[$i]['desease_type'] = rtrim($data[$i]['desease_type'],'-');
		}
		$this->assign("info",$data);
		//加载左侧导航菜单缓存
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","HopeEntry/entry_list");
        $this->assign("Js","HopeEntry/entry_list_js");
        $this->display("Conmons/Frame");
	}
	
	//创建词条页
    public function entry_index(){
		//词条类型
		$entry_type = $this->hope_entry_type->select();
		//疾病类型
		$disease_type = $this->hope_disease_type->select();
		//加载左侧导航菜单缓存
		$this->assign("entry_type",$entry_type);
		$this->assign("disease_type",$disease_type);
		$this->assign("entry_type_num",$this->hope_entry_type->count());
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","HopeEntry/entry_index");
        $this->assign("Js","HopeEntry/entry_index_js");
        $this->display("Conmons/Frame");
		
    }
	
	/*
	*执行创建词条
	*/
	public function entry_add(){
		//接收原始数据
		$info = I();
		//接收部分有效数据
		$data['cname'] = trim(I('cname',''));
		$data['ename'] = trim(I('ename',''));
		$datas['entry_type_id'] = I('entry_id','');
		$datas['country_id'] = I('countryid','');
		$datas['file_id'] = I('uploadDivs11','');
		$datas['abstract'] = trim(I('abstract',''));
		$datas['dataname'] = trim(I('dataname',''));
		$datas['dataurl'] = trim(I('dataurl',''));
		$catalogSign = I('catalogName');
		$userInfo = $this->session;
		$datas['uid'] = $userInfo['uid'];
		$datas['content'] = stripslashes($_POST['catalogTwo_entrycontent_0_0']);
		$datas['ctime'] = time();
		//拼装疾病ID
		$disArr = I('diseaseID');
		$disCount = count($disArr);
		for($d=0;$d<$disCount;$d++){
			$datas['disease_id'] .= $disArr[$d].',';
		}
		
		//创建词条主体内容表数据
		if($datas){
			$state = $this->hope_entry_info->add($datas);
			//创建词条主表数据
			$entry['cname'] = $data['cname'];
			$entry['ename'] = $data['ename'];
			$entry['entry_info_id'] = $state;
			$entry['entry_type_id'] = $datas['entry_type_id'];
			$entry['disease_id'] = $datas['disease_id'];
			$entry['uid'] = $userInfo['uid'];
			$entry['ctime'] = time();
			$status = $this->hope_entry->add($entry);
			if($status){
				//将词条信息表绑定词条主表ID
				$this->hope_entry_info->where('id='.$state)->setField('entry_id',$status);
				$this->redirect('HopeEntry/entry_list');
			}else{
				$this->error('数据添加失败');
			}
		}else{
			$this->error('创建词条数据异常');
		}
		
	}
	public function entry_add2(){
		//接收原始数据
		$info = I();
		//接收部分有效数据
		$data['cname'] = trim(I('cname',''));
		$data['ename'] = trim(I('ename',''));
		$datas['entry_type_id'] = I('entry_id','');
		$datas['disease_id'] = I('desease_zid','');
		$datas['abstract'] = trim(I('abstract',''));
		$datas['dataname'] = trim(I('dataname',''));
		$datas['dataurl'] = trim(I('dataurl',''));
		$catalogSign = I('catalogName');
		$userInfo = $this->session;
		$datas['uid'] = $userInfo['uid'];
		$datas['ctime'] = time();
		
		//创建词条主体内容表数据
		if($datas){
			$state = $this->hope_entry_info->add($datas);
			//创建目录表内容
			$oneCount = count($catalogSign);
			$twoArr = array();
			for($a=0;$a<$oneCount;$a++){
				$info['catalogTwo_entrycontent_'.$catalogSign[$a]] = stripslashes($_POST['catalogTwo_entrycontent_'.$catalogSign[$a]]);
				$num = null;
				$num = explode('_',$catalogSign[$a]);
				for($b=0;$b<$num[1]+1;$b++){
					$info['catalogTwo_entrycontent_'.$num[0].'_'.$b] = stripslashes($_POST['catalogTwo_entrycontent_'.$num[0].'_'.$b]);
					$abstract = 'catalogOne_abstract_'.$num[0].'_'.$b;
					$entrycontent = 'catalogTwo_entrycontent_'.$num[0].'_'.$b;
					$twoArr[$a][$b]['catalogOne_abstract'] = $info[$abstract];
					$twoArr[$a][$b]['catalogTwo_entrycontent'] = $info[$entrycontent];
				}
			}
			
			//拼装目录并写入数据
			$twoCount = count($twoArr);
			for($c=0;$c<$twoCount;$c++){
				$threeCount = count($twoArr[$c]);
				for($d=0;$d<$threeCount;$d++){
					if($d == 0){
						$catalog['entry_info_id'] = $state; 
						$catalog['fid'] = 0; 
						$catalog['name'] = $twoArr[$c][$d]['catalogOne_abstract']; 
						$catalog['content'] = stripslashes($twoArr[$c][$d]['catalogTwo_entrycontent']); 
						$catalog['level'] = 1; 
						$catalog['ctime'] = time(); 
						$catalogOneState = $this->hope_entry_info_catalog->add($catalog);
					}else{
						$catalog['entry_info_id'] = $state; 
						$catalog['fid'] = $catalogOneState; 
						$catalog['name'] = $twoArr[$c][$d]['catalogOne_abstract']; 
						$catalog['content'] = stripslashes($twoArr[$c][$d]['catalogTwo_entrycontent']); 
						$catalog['level'] = 2; 
						$catalog['ctime'] = time(); 
						$catalogTwoState = $this->hope_entry_info_catalog->add($catalog);
					}
				}
			}
			
			//创建词条主表数据
			$entry['cname'] = $data['cname'];
			$entry['ename'] = $data['ename'];
			$entry['entry_info_id'] = $state;
			$entry['entry_type_id'] = $datas['entry_type_id'];
			$entry['disease_id'] = $datas['disease_id'];
			$entry['uid'] = $userInfo['uid'];
			$entry['ctime'] = time();
			$status = $this->hope_entry->add($entry);
			if($status){
				//将词条信息表绑定词条主表ID
				$this->hope_entry_info->where('id='.$state)->setField('entry_id',$status);
				$this->redirect('HopeEntry/entry_list');
			}else{
				$this->error('数据添加失败');
			}
		}else{
			$this->error('创建词条数据异常');
		}
		
	}
	
	
	//创建词条类型
	public function entry_type(){
		//加载左侧导航菜单缓存
		$this->assign("LeftNavInfo",session('LeftNav'));
		$info = $this->hope_entry_type->select();
		$count = count($info);
		for($i=0;$i<$count;$i++){
			$info[$i]['ctime'] = date('Y-m-d H:i:s',$info[$i]['ctime']);
			$info[$i]['uname'] = $this->hope_user->where('uid='.$info[$i]['uid'])->getField('tname');
		}
		$this->assign('info',$info);
        $this->assign("Tel","HopeEntry/entry_type");
        $this->assign("Js","HopeEntry/entry_type_js");
        $this->display("Conmons/Frame");
	}
	
	//执行创建词条类型
	public function entry_type_add(){
		$data['name'] = trim(I('name',''));
		$userInfo = $this->session;
		$data['uid'] = $userInfo['uid'];
		$data['ctime'] = time();
		if($data['name']){
			$status = $this->hope_entry_type->where('state=1 && name="'.$data['name'].'"')->find();
			if($status){
				$this->error('您已添加：'.$data['name'].'，请不要添加重复数据');
			}else{
				$state = $this->hope_entry_type->add($data);
				if($state){
					$this->redirect('HopeEntry/entry_type');
				}else{
					$this->error('操作失败');
				}
			}
		}else{
			$this->error('词条类型名称不能为空');
		}
	}
	
	//执行修改词条类型
	public function entry_type_update(){
		$data['name'] = trim(I('name',''));
		$data['id'] = I('entrystyleid','');
		$userInfo = $this->session;
		$data['uid'] = $userInfo['uid'];
		$data['ctime'] = time();
		if($data['name'] && $data['id']){
			$status = $this->hope_entry_type->where('state=1 && name="'.$data['name'].'"')->find();
			if($status){
				$this->error('您已添加：'.$data['name'].'，请不要添加重复数据');
			}else{
				$state = $this->hope_entry_type->where('id='.$data['id'])->save($data);
				if($state){
					$this->redirect('HopeEntry/entry_type');
				}else{
					$this->error('操作失败','/HopeEntry/entry_type');
				}
			}
		}else{
			$this->error('词条类型名称不能为空','/HopeEntry/entry_type');
		}
	}
	
	//疾病科室列表页
	public function disease_department(){
		$info = $this->hope_disease_department->select();
		//加载左侧导航菜单缓存
		$this->assign("LeftNavInfo",session('LeftNav'));
		$count = count($info);
		for($i=0;$i<$count;$i++){
			$info[$i]['ctime'] = date('Y-m-d H:i:s',$info[$i]['ctime']);
			$info[$i]['uname'] = $this->hope_user->where('uid='.$info[$i]['uid'])->getField('tname');
		}
		$this->assign('info',$info);
        $this->assign("Tel","HopeEntry/disease_department");
        $this->assign("Js","HopeEntry/disease_department_js");
        $this->display("Conmons/Frame");
	}
	
	//创建疾病科室
	public function disease_department_add(){
		$data['cname'] = trim(I('cname',''));
		$data['ename'] = trim(I('ename',''));
		$data['remark'] = I('remark','');
		$userInfo = $this->session;
		$data['uid'] = $userInfo['uid'];
		$data['ctime'] = time();
		if($data['cname']){
			$status = $this->hope_disease_department->where('state=1 && name="'.$data['cname'].'"')->find();
			if($status){
				$this->error('您已添加：'.$data['cname'].'，请不要添加重复数据');
			}else{
				$state = $this->hope_disease_department->add($data);
				if($state){
					$this->redirect('HopeEntry/disease_department');
				}else{
					$this->error('操作失败');
				}
			}
		}else{
			$this->error('疾病科室名称不能为空');
		}
	}
	
	//执行修改疾病科室
	public function disease_department_update(){
		$data['cname'] = trim(I('cname',''));
		$data['ename'] = trim(I('ename',''));
		$data['remark'] = I('remark','');
		$data['id'] = I('diseasedepartmentid','');
		$userInfo = $this->session;
		$data['uid'] = $userInfo['uid'];
		$data['ctime'] = time();
		if($data['cname'] && $data['id']){
			$status = $this->hope_disease_department->where('state=1 && name="'.$data['cname'].'"')->find();
			if($status){
				$this->error('您已添加：'.$data['cname'].'，请不要添加重复数据');
			}else{
				$state = $this->hope_disease_department->where('id='.$data['id'])->save($data);
				if($state){
					$this->redirect('HopeEntry/disease_department');
				}else{
					$this->error('操作失败');
				}
			}
		}else{
			$this->error('词条类型名称不能为空');
		}
	}
	
	//疾病类型一级列表页
	public function disease_type(){
		$info = $this->hope_disease_type->where('level=1 && state=1')->select();
		$count = count($info);
		for($i=0;$i<$count;$i++){
			$info[$i]['ctime'] = date('Y-m-d H:i:s',$info[$i]['ctime']);
			$info[$i]['uname'] = $this->hope_user->where('uid='.$info[$i]['uid'])->getField('tname');
			$ztype = $this->hope_disease_type->where('level=2 && state=1 && fid='.$info[$i]['id'])->select();
			$countT = count($ztype);
			for($m=0;$m<$countT;$m++){
				$disease_department = null;
				//查询科室
				$diseaseStr = $ztype[$m]['department_id'];
				$newDisease = rtrim($diseaseStr,',');
				$diseaseArr = explode(',',$newDisease);
				$countD = count($diseaseArr);
				for($n=0;$n<$countD;$n++){
					$disease_department .= $this->hope_disease_department->where('state=1 && id='.$diseaseArr[$n])->getField('cname').'&nbsp;&nbsp;';
					
				}
				
				$info[$i]['twoname'] .= $ztype[$m]['name'].'('.$disease_department.')&nbsp;&nbsp;';
			}
		}
		// dump($info);die();
		$this->assign('info',$info);
		//加载左侧导航菜单缓存
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","HopeEntry/disease_type");
        $this->assign("Js","HopeEntry/disease_type_js");
        $this->display("Conmons/Frame");
	}
	
	//添加疾病父级/子集类型
	/**
	type{
			1=>父级：添加身体部位
			2=>子集：添加疾病
		}
	**/
	public function disease_type_add(){
		$type = I('hiddenType','');
		$data['name'] = trim(I('name',''));
		if($type == 1){
			$data['fid'] = 0;
			$data['level'] = 1;
			$userInfo = $this->session;
			$data['uid'] = $userInfo['uid'];
			$data['department_id'] = 0;
			$data['ctime'] = time();
		}else if($type == 2){
			$data['fid'] = I('fid','');
			$department_id = I('department_id','');
			$countD = count($department_id);
			for($a=0;$a<$countD;$a++){
				if($department_id[$a]){
					$data['department_id'] .= $department_id[$a];
				}
			}
			$data['level'] = 2;
			$userInfo = $this->session;
			$data['uid'] = $userInfo['uid'];
			$data['ctime'] = time();
		}else{
			$this->error('数据格式有误');
		}
		$status = $this->hope_disease_type->where('level='.$data['level'].'&& name="'.$data['name'].'"&& state =1')->find();
		if($status){
			$this->error('您已添加：'.$data['name'].'，请不要添加重复数据');
		}else{
			//执行数据添加
			$state = $this->hope_disease_type->add($data);
			if($state){
				// $this->redirect('HopeEntry/disease_type');
				header('location:'.getenv("HTTP_REFERER")); 
			}else{
				$this->error('疾病类型数据添加失败');
			}
		}
	}
	
	//修改疾病父级/子集类型
	/**
	type{
			1=>父级：添加身体部位
			2=>子集：添加疾病
		}
	**/
	public function disease_type_update(){
		$type = I('hiddenType','');
		$diseaseid = I('diseaseid','');
		$name = trim(I('name'));
		$level = I('hiddenLevel','');
		if($type == 1){
			$status = $this->hope_disease_type->where('level='.$level.'&& name="'.$name.'" && state =1')->find();
			if($status){
				$this->error('已存在：'.$name.'，请不要添加重复数据');
			}else{
				$state = $this->hope_disease_type->where('id='.$diseaseid)->setField('name',$name);
				if($state){
					$this->redirect('HopeEntry/disease_type');
				}else{
					$this->error('疾病类型数据添加失败');
				}
			}
		}else if($type == 2){
			$data['fid'] = I('fid','');
			$department_id = I('department_id','');
			$countD = count($department_id);
			for($a=0;$a<$countD;$a++){
				if($department_id[$a]){
					$data['department_id'] .= $department_id[$a];
				}
			}
			$data['name'] = $name;
			$data['level'] = 2;
			$userInfo = $this->session;
			$data['uid'] = $userInfo['uid'];
			$data['ctime'] = time();
			// dump($data);die();
			$status = $this->hope_disease_type->where('level='.$level.'&& name="'.$name.'" && department_id = "'.$data['department_id'].'"&& state =1')->find();
			if($status){
				$this->error('已存在：'.$name.'，请不要添加重复数据');
			}else{
				$state = $this->hope_disease_type->where('id='.$diseaseid)->save($data);
				if($state){
					// $this->redirect('HopeEntry/disease_type');
					header('location:'.getenv("HTTP_REFERER"));
				}else{
					$this->error('疾病类型数据添加失败');
				}
			}
			
		}else{
			$this->error('疾病类型数据添加失败');
		}
	}
	
	//查看疾病类型子列表页
	public function disease_type_list(){
		$listID = I('id','');
		$info = $this->hope_disease_type->where('level=2 && fid='.$listID.'&& state =1')->select();
		$count = count($info);
		for($i=0;$i<$count;$i++){
			$info[$i]['ctime'] = date('Y-m-d H:i:s',$info[$i]['ctime']);
			$info[$i]['uname'] = $this->hope_user->where('uid='.$info[$i]['uid'])->getField('tname');
			//查询科室
			$disease_department = null;
			//查询科室
			$diseaseStr = $info[$i]['department_id'];
			$newDisease = rtrim($diseaseStr,',');
			$diseaseArr = explode(',',$newDisease);
			$countD = count($diseaseArr);
			for($n=0;$n<$countD;$n++){
				$disease_department .= $this->hope_disease_department->where('state=1 && id='.$diseaseArr[$n])->getField('cname').'&nbsp;&nbsp;';
			}
			$info[$i]['dname'] .= $disease_department.'&nbsp;&nbsp;';
			
		}
		//查询父级名称
		$fname = $this->hope_disease_type->where('level=1 && id='.$listID.'&& state =1')->getField('name');
		//查询所有疾病科室
		$data = $this->hope_disease_department->where('state = 1')->select();
		// dump($info);
		//加载左侧导航菜单缓存
		$this->assign("data",$data);
		$this->assign("fname",$fname);
		$this->assign("fid",$listID);
		$this->assign("info",$info);
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","HopeEntry/disease_type_list");
        $this->assign("Js","HopeEntry/disease_type_list_js");
        $this->display("Conmons/Frame");
	}
	
	/*
	*上下线当前词条	
	*接收数据格式：	'entry_id'=> 词条ID
					'entry_state'=> 词条状态
					'type'=>1  上下线词条
						  =>2  审核词条
					'entry_examine'=>1  审核通过
								   =>2  审核失败
	*返回数据格式
					{
						'status'=>状态信息
						'type'=>查询类型
						'message'=>提示信息
					}
	status = {
		1：更新成功
		2：查询词条失败
		3：缺少词条ID
		4：缺少词条状态
		5：当前词条未审核通过
		6：数据更新失败
		7：数据更新失败
		8：缺少词条审核状态
	}
	*/
	public function entry_examine(){
		$type = I('type','');
		$entry_id = I('entry_id','');
		$entry_state = I('entry_state','');
		$entry_examine = I('entry_examine','');
		if(empty($entry_id)){
			$output = array(
				'status' 	=>'3',
				'message'	=>'缺少词条ID'
			);
			$this->ajaxReturn($output);
		}
		if($type == 1){
			if(empty($entry_state)){
				$output = array(
					'status' 	=>'4',
					'message'	=>'缺少词条状态'
				);
				$this->ajaxReturn($output);
			}
			$state = $this->hope_entry->where('id='.$entry_id.'&& statecode =1')->find();
			if($state){
				if($entry_state == 1){
					$status = $this->hope_entry->where('id='.$entry_id.'&& statecode =1')->setField('state',2);
				}else if($entry_state == 2){
					$status = $this->hope_entry->where('id='.$entry_id.'&& statecode =1')->setField('state',1);
				}
				$realState = $this->hope_entry->where('id='.$entry_id.'&& statecode =1')->getField('state');
				if($status){
					$output = array(
						'status' 	=>'1',
						'data' => $realState,
						'message'	=>'数据更新成功'
					);
					$this->ajaxReturn($output);
				}else{
					$output = array(
						'status' 	=>'6',
						'message'	=>'数据更新失败'
					);
					$this->ajaxReturn($output);
				}
			}else{
				$output = array(
					'status' 	=>'5',
					'message'	=>'当前词条未审核通过'
				);
				$this->ajaxReturn($output);
			}
		}else if($type == 2){
			if(empty($entry_examine)){
				$output = array(
					'status' 	=>'8',
					'message'	=>'缺少词条审核状态'
				);
				$this->ajaxReturn($output);
			}
			$this->hope_entry->where('id='.$entry_id)->setField('stime',time());
			$status = $this->hope_entry->where('id='.$entry_id)->setField('statecode',$entry_examine);
			if($status){
				$output = array(
					'status' 	=>'1',
					'message'	=>'数据更新成功'
				);
				$this->ajaxReturn($output);
			}else{
				$output = array(
					'status' 	=>'7',
					'message'	=>'数据更新失败'
				);
				$this->ajaxReturn($output);
			}
		}else{
			$output = array(
				'status' 	=>'2',
				'message'	=>'查询词条失败'
			);
			$this->ajaxReturn($output);
		}
	}
	
	//查看词条详情
	public function entry_detail(){
		$entry_id = I('id','');
		if(empty($entry_id)){
			$this->error('词条ID类型不能为空');
		}
		$data = $this->hope_entry->where('id='.$entry_id)->find();
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
		//图片
		$data['img'] = $this->hope_uploads->where('id='.$entry_info['file_id'])->getField('url');
		$data['file_id'] = $entry_info['file_id'];
		//词条目录内容
		$data['entry_content'] = $entry_info['content'];
		$data['entry_info_id'] = $entry_info['id'];
		// dump($data);die();
		// echo $data['entry_content'];
		// die();
		//加载左侧导航菜单缓存
		// dump($data);
		//词条类型
		$entry_type = $this->hope_entry_type->select();
		$this->assign("entry_type",$entry_type);
		//疾病类型
		$disArr = explode(',',rtrim($data['disease_id'],','));
		$data['disease_type_id'] = $this->hope_disease_type->where('id='.$disArr[0])->getField('fid');
		
		//拼装选中的二级疾病信息
		$twoArr = $this->hope_disease_type->where('fid='.$data['disease_type_id'])->select();
		$twoCount = count($twoArr);
		$threeCount = count($disArr);
		$twoHTNL = '';
		for($m=0;$m<$twoCount;$m++){
			$num = 0;
			$nun = 0;
			for($n=0;$n<$threeCount;$n++){
				if($twoArr[$m]['id'] == $disArr[$n]){
					$nun = $nun+1;
				}else{
					$num = $num+1;
				}
				if($n == $threeCount-1){
					if($nun == 1){
						$twoHTNL .= '<div class="publicSpan" style="background:red;color:white;" data-state="1" data-id="'.$twoArr[$m]['id'].'">'.$twoArr[$m]['name'].'</div><input name="diseaseID[]" value="'.$twoArr[$m]['id'].'" type="hidden">';	
					}else{
						$twoHTNL .= '<div class="publicSpan" style="background:white;color:black;" data-state="2" data-id="'.$twoArr[$m]['id'].'">'.$twoArr[$m]['name'].'</div>';
					}
				}
			}
		}
		$twoHTNL .= '<div style="clear:both"></div>';
		$this->assign("twoHTNL",$twoHTNL);
		
		$disease_type= $this->hope_disease_type->where('state=1')->select();
		$this->assign("disease_type",$disease_type);
		
		//加载当前数据
		$this->assign("data",$data);
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","HopeEntry/entry_detail");
        $this->assign("Js","HopeEntry/entry_detail_js");
        $this->display("Conmons/Frame");
	}
	
	//执行修改词条接口
	public function entry_edit(){
		$info = I();
		//接收部分有效数据
		$data['cname'] = trim(I('cname',''));
		$data['ename'] = trim(I('ename',''));
		$datas['entry_type_id'] = I('entry_id','');
		$datas['country_id'] = I('countryid','');
		$datas['file_id'] = I('uploadDivs11','');
		$datas['abstract'] = trim(I('abstract',''));
		$datas['dataname'] = trim(I('dataname',''));
		$datas['dataurl'] = trim(I('dataurl',''));
		$catalogSign = I('catalogName');
		$userInfo = $this->session;
		$datas['uid'] = $userInfo['uid'];
		$datas['content'] = stripslashes($_POST['catalogTwo_entrycontent_0_0']);
		$datas['ctime'] = time();
		$entry_id = I('entry_ids');
		$entry_info_id = I('entry_info_id');
		//拼装疾病ID
		$disArr = I('diseaseID');
		$disCount = count($disArr);
		for($d=0;$d<$disCount;$d++){
			$datas['disease_id'] .= $disArr[$d].',';
		}
		
		$datas['statecode'] = 2;
		if($datas){
			$state = $this->hope_entry_info->where('id='.$entry_info_id)->save($datas);
			if($state){
				$data['entry_type_id'] = $datas['entry_type_id'];
				$data['disease_id'] = $datas['disease_id'];
				$data['statecode'] = 2;
				$status = $this->hope_entry->where('id='.$entry_id)->save($data);
				
				$this->redirect('HopeEntry/entry_list');
				
			}else{
				$this->error('数据更新失败1');
			}
		}
		
	}
	
	//本词条相关推荐
	public function entry_recommend(){
		$id = I('id');
		$entryInfo = $this->hope_entry->where('id='.$id)->field('id,cname')->find();
		//相关医生
		
		$this->assign("info",$entryInfo);
		//加载左侧导航菜单缓存
		$this->assign("LeftNavInfo",session('LeftNav'));
		$this->assign("Tel","HopeEntry/entry_recommend");
        $this->assign("Js","HopeEntry/entry_recommend_js");
        $this->display("Conmons/Frame");
	}
	
	//一键审核成功并上线
	public function quicklyOnline(){
		$entry_id = I('entry_id');
		//接收修改文件来源路径
		$fromUrl = trim(I('fromUrl',''));
		$fromUrl = str_replace(".html","",$fromUrl);
		$this->hope_entry->where('id='.$entry_id)->setField('statecode',1);
		$this->hope_entry->where('id='.$entry_id)->setField('state',1);
		$linkState = $this->hope_link->where('sign_id='.$entry_id.'&& type=1')->select();
		$sc = count($linkState);
		//更新关联表信息
		if($sc){
			for($s=0;$s<$sc;$s++){
				$state = $this->hope_link->where('id='.$linkState[$s]['id'])->setField('state',1);
			}
		}
		if($state){
			$this->redirect($fromUrl);
		}else{
			$this->error('一键上线失败');
		}
	}
	
	
	
	
	
	
}