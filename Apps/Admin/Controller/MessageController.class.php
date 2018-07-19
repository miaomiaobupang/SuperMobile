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
class MessageController extends LimitController {
	private $hope_message;	//实例化资讯管理表
	private $hope_entry_type;	//实例化词条类型管理表
	private $hope_user;	//实例化词条类型管理表
	private $hope_uploads;	//实例化文件管理表
	private $hope_disease_department;	//实例化疾病科室表
	private $hope_disease_type;	//实例化疾病类型管理表
	private $hope_link;	//实例化疾病信息关联管理表
	private $hope_disease_kind;	//实例化疾病种类管理表
	private $user_adminrole;	//实例化用户组管理表
	/**
	* 构造方法
	*/
	public function __construct() {
		parent::__construct();
		//实例化资讯管理表
		$this->hope_message = D('hope_message');
		//实例化词条类型管理表
		$this->hope_entry_type = D('hope_entry_type');
		$this->hope_user = D('user');
		//实例化文件管理表
		$this->hope_uploads = D('hope_uploads');
		//实例化疾病科室表
		$this->hope_disease_department = D('hope_disease_department');
		//实例化疾病类型管理表
		$this->hope_disease_type = D('hope_disease_type');
		//实例化用户组管理表
		$this->user_adminrole = D('user_adminrole');
		//实例化疾病种类管理表
		$this->hope_disease_kind = D('hope_disease_kind');
		//实例化疾病信息关联管理表
		$this->hope_link = D('hope_link');
		$this->session = $_SESSION['user'];
	}
	
	//加载资讯展示页面
    public function message(){
		$entry_type = $this->hope_entry_type->where('state=1')->field('id,name')->select();
		$disease_department = $this->hope_disease_department->where('state=1')->select();
		//按照目标拼音首拼进行分组
		$sql = "SELECT * FROM `hope_disease_type` WHERE `level`='2' && `state`='1' GROUP BY `pid`";
		$disease_type = D()->query($sql);
		$disease_kind = $this->hope_disease_kind->where('state=1')->select();
		//加载左侧导航菜单缓存
		$this->assign("disease_department",$disease_department);
		$this->assign("disease_kind",$disease_kind);
		$this->assign("disease_type",$disease_type);
		$this->assign("entry_type",$entry_type);
		//获取当前页面URL
		$fromUrl = $_SERVER["QUERY_STRING"];
		$fromUrl = str_replace(".html","",$fromUrl);
		$this->assign("fromUrl",$fromUrl);
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","Message/message");
        $this->assign("Js","Message/message_js");
        $this->display("Conmons/Frame");
    }
	
	//执行资讯添加数据
	public function message_add(){
		$entry_id = trim(I('sign_type',''));
		if($entry_id == 1 || $entry_id == 4 || $entry_id == 6){
			$special_id_arr['country_id'] = I('country_id','');
			$special_id_arr['disease_id'] = I('disease_id','');
			$disCount = count($special_id_arr['disease_id']);
		}else if($entry_id == 2){
			$special_id_arr['country_id'] = I('country_id','');
			$special_id_arr['department_id'] = I('department_id','');
			$disCount = count($special_id_arr['department_id']);
		}else if($entry_id == 7){
			$special_id_arr['country_id'] = I('country_id','');
			$special_id_arr['kind_id'] = I('kind_id','');
			$disCount = count($special_id_arr['kind_id']);
		}
		//接收部分有效数据
		$data['type'] = $entry_id;
		$data['cname'] = trim(I('cname',''));
		$data['ename'] = trim(I('ename',''));
		$data['abstract'] = trim(I('abstract',''));
		$data['keywords'] = I('keywords','');
		$data['content'] = stripslashes($_POST['catalogContent']);
		
		//接收文件来源路径
		$fromUrl = trim(I('fromUrl',''));
		$fromUrl = str_replace(".html","",$fromUrl);
		
		$subject = strip_tags($data['content']);//去除html标签
		$pattern = '/\s/';//去除空白
		$content = preg_replace($pattern,'',$subject);  
		if(empty($data['abstract'])){
			$data['abstract'] = mb_substr($content,0,60,'utf-8');//截取80个汉字
		}
		$userInfo = $this->session;
		$data['uid'] = $userInfo['uid'];
		$data['sid'] = $userInfo['uid'];
		$data['ctime'] = time();
		//如果未上传封面图，则封面图默认取文章中第一个图
		if(I('uploadDivs11','')){
			$data['file_id'] = I('uploadDivs11','');
		}else{
			//取文章中第一个图片
			preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$data['content'],$match); 
			//将图片保存至文件表
			$file['url'] = $match[2];
			$file['uptime'] = time();
			$data['file_id'] = $this->hope_uploads->add($file);	  
		}
		//去除超链接
		$data['content'] = ereg_replace("<a [^>]*>|<\/a>","",$data['content']);
		$state = $this->hope_message->add($data);
		if($state){
			//创建关联信息表数据
			//准备信息关联表数据
			$link['sign_id'] = $state;
			$link['sign_type'] = $entry_id;
			$link['type'] = 2;
			$link['ctime'] = time();
			for($d=0;$d<$disCount;$d++){
				if($entry_id == 1 || $entry_id == 4 || $entry_id == 6){
					$link['disease_id'] = $special_id_arr['disease_id'][$d];
					$link['department_id'] = 0;
					$link['country_id'] = $special_id_arr['country_id'];
					$link['kind_id'] = $this->hope_disease_type->where('id='.$special_id_arr['disease_id'][$d])->getField('kind_id');
					$link['body_id'] = $this->hope_disease_type->where('id='.$special_id_arr['disease_id'][$d])->getField('fid');
				}else if($entry_id == 2){
					$link['body_id'] = 0;
					$link['disease_id'] = 0;
					$link['country_id'] = $special_id_arr['country_id'];
					$link['kind_id'] = 0;
					$link['department_id'] = $special_id_arr['department_id'][$d];
				}else if($entry_id == 7){
					$link['body_id'] = 0;
					$link['disease_id'] = 0;
					$link['country_id'] = $special_id_arr['country_id'];
					$link['kind_id'] = $special_id_arr['kind_id'][$d];
					$link['department_id'] = 0;
				}
				$statecode = $this->hope_link->add($link);
			}
			if($statecode){
				$this->redirect('Message/message_list');
			}else{
				$this->error('数据添加失败2');
			}
		}else{
			$this->error('数据添加失败1');
		}
	}
	
	//资讯列表页
	public function message_list(){
		$uSession = $this->session;
		//查询所有后台用户(部分管理员)
		if($uSession['uid'] == 3 || $uSession['uid'] == 1){
			if(I('user','')){
				$where['sid'] = I('user','');
			}
			if(I('type') && I('type') != 0){
				$where['type'] = I('type',1);
			}
			if(I('edit') == 3){
			}else if(I('edit') == 1){
				$where['edit_num'] = array('gt',0);
			}else if(I('edit') == 2){
				$where['edit_num'] = array('eq',0);
			}
			$statecode = I('statecode');
			if($statecode == 4){
			}else if(!empty($statecode)){
				$where['statecode'] = array('eq',$statecode);
			}
			$statestatus = I('statestatus');
			if($statestatus == 3){
			}else if(!empty($statestatus)){
				$where['state'] = array('eq',$statestatus);
			}
			$fromstate = I('fromstate');
			if($fromstate == 4){
			}else if(!empty($fromstate)){
				$where['sys_type'] = array('eq',$fromstate);
			}
			$uidArr = $this->user_adminrole->where('rid=8 && state=1 || rid=7 && state=1 || rid=6 && state=1')->select();
			$uCount = count($uidArr);
			for($i=0;$i<$uCount;$i++){
				$uidArr[$i]['uname'] = $this->hope_user->where('uid='.$uidArr[$i]['uid'])->getField('tname');
			}
			$this->assign('role',1);
		}else{
			$where['sid'] = $uSession['uid'];
			if(I('type') && I('type') != 0){
				$where['type'] = I('type',1);
			}
			$where['sys_type'] = 1;
			if(I('edit') == 3){
			}else if(I('edit') == 1){
				$where['edit_num'] = array('gt',0);
			}else if(I('edit') == 2){
				$where['edit_num'] = array('eq',0);
			}
			$statecode = I('statecode');
			if($statecode == 4){
			}else if(!empty($statecode)){
				$where['statecode'] = array('eq',$statecode);
			}
			$statestatus = I('statestatus');
			if($statestatus == 3){
			}else if(!empty($statestatus)){
				$where['state'] = array('eq',$statestatus);
			}
			$fromstate = I('fromstate');
			if($fromstate == 4){
			}else if(!empty($fromstate)){
				$where['sys_type'] = array('eq',$fromstate);
			}
			$this->assign('role',2);
		}
		$cname = I('cname');
		if($cname){
			$where = null;
			$where['cname'] = array('like','%'.$cname.'%');
		}
		
		
		// dump(I());
		import('ORG.Util.Page');// 导入分页类
		$count = $this->hope_message->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$info = $this->hope_message->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		$show = $Page->show();// 分页显示输出
		$this->assign('show',$show);
		//获取当前页面URL
		$fromUrl = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$fromUrl = str_replace(".html","",$fromUrl);
		// dump($fromUrl);
		$countOne = count($info);
		for($i=0;$i<$countOne;$i++){
			if($info[$i]['type'] == 1){
				$info[$i]['message_type'] = '医疗前沿';
			}else if($info[$i]['type'] == 2){
				$info[$i]['message_type'] = '最新案例';
			}else if($info[$i]['type'] == 3){
				$info[$i]['message_type'] = '公益';
			}else if($info[$i]['type'] == 4){
				$info[$i]['message_type'] = '海外医疗';
			}else if($info[$i]['type'] == 5){
				$info[$i]['message_type'] = '世界大师中国行';
			}else if($info[$i]['type'] == 6){
				$info[$i]['message_type'] = '专家资讯';
			}else if($info[$i]['type'] == 7){
				$info[$i]['message_type'] = '医院新闻';
			}
			if($info[$i]['etime']){
				$info[$i]['etime'] = date('Y-m-d H:i:s',$info[$i]['etime']);
			}else{
				$info[$i]['etime'] = '--';
			}
			
			if($info[$i]['stime']){
				$info[$i]['stime'] = date('Y-m-d H:i:s',$info[$i]['stime']);
			}else{
				$info[$i]['stime'] = '--';
			}
			//词条类型名称
			$info[$i]['entry_type'] = $this->hope_entry_type->where('id='.$info[$i]['entry_type_id'])->getField('name');
			$info[$i]['uname'] = $this->hope_user->where('uid='.$info[$i]['sid'])->getField('tname');
		}
		//加载左侧导航菜单缓存
		$this->assign("count",$count);
		$this->assign("fromUrl",$fromUrl);
		$this->assign("uSession",$uSession);
		$this->assign("info",$info);
		$this->assign("cname",$cname);
		$where['type'] = I('type','0');
		$where['uid'] = I('user','0');
		$where['edit_num'] = I('edit','3');
		$where['statecode'] = I('statecode','4');
		$where['fromstate'] = I('fromstate','4');
		$where['statestatus'] = I('statestatus','3');
		$this->assign("statecode",$where['statecode']);
		$this->assign("statestatus",$where['statestatus']);
		$this->assign("fromstate",$where['fromstate']);
		$this->assign("type",$where['type']);
		$this->assign("edit",$where['edit_num']);
		$this->assign("user",$where['uid']);
		$this->assign("uidArr",$uidArr);
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","Message/message_list");
        $this->assign("Js","Message/message_list_js");
        $this->display("Conmons/Frame");
	}
	
	//资讯详情页
	public function message_detail(){
		$entry_type = $this->hope_entry_type->where('state=1')->field('id,name')->select();
		$disease_department = $this->hope_disease_department->where('state=1')->select();
		//按照目标拼音首拼进行分组
		$sql = "SELECT * FROM `hope_disease_type` WHERE `level`='2' && `state`='1' GROUP BY `pid`";
		$disease_type = D()->query($sql);
		$disease_kind = $this->hope_disease_kind->where('state=1')->select();
		$messageID = I('id','');
		$info = $this->hope_message->where('id='.$messageID)->find();
		//图片
		$info['img'] = $this->hope_uploads->where('id='.$info['file_id'])->getField('url');
		//获取来源
		$info['fromUrl'] = $_SERVER['HTTP_REFERER'];
		$fromUrl = str_replace(".html","",$info['fromUrl']);
		//获取关联表数据
		$fiveArr = $this->hope_link->where('sign_id='.$messageID.'&& type=2 && state=1')->select();
		$fiveC = count($fiveArr);
		$sixArr = null;
		for($i=0;$i<$fiveC;$i++){
			if($info['type'] == 1 || $info['type'] == 4 || $info['type'] == 6){
				$sixArr['country_id'] = $fiveArr[$i]['country_id'];
				$sixArr['diseaseArr'][] = $fiveArr[$i]['disease_id'];
			}else if($info['type'] == 2){
				$sixArr['country_id'] = $fiveArr[$i]['country_id'];
				$sixArr['departmentArr'][] = $fiveArr[$i]['department_id'];
			}else if($info['type'] == 7){
				$sixArr['country_id'] = $fiveArr[$i]['country_id'];
				$sixArr['kindArr'][] = $fiveArr[$i]['kind_id'];
			}
			$sixArr['sign_id'] = $fiveArr[$i]['sign_id'];
			$sixArr['sign_type'] = $info['type'];
		}
		$this->assign("linkArr",$sixArr);
		if($info['type'] == 1 || $info['type'] == 4 || $info['type'] == 6){
			// dump($sixArr);
			//疾病部位、疾病
			//按照目标拼音首拼进行分组
			$twoArr = D()->query($sql);
			$sixC = count($sixArr['diseaseArr']);
			$twoC = count($twoArr);
			$sixHTML = '';
			for($i=0;$i<$twoC;$i++){
				$n = 0;
				$m = 0;
				if($sixC){
					if($twoArr[$i]['level'] == 2){
						$inputHidden = null;
						for($s=0;$s<$sixC;$s++){
							$pid = $this->hope_disease_type->where('id='.$sixArr['diseaseArr'][$s])->getField('pid');
							if($twoArr[$i]['pid'] == $pid){
								$n = $n + 1;
								$inputHidden .= '<input type="hidden" name="disease_id[]" value="'.$sixArr['diseaseArr'][$s].'" class="tempDisease-'.$pid.'">';
							}else{
								$m = $m + 1;
							}
							if($n+$m == $sixC){
								if($n == 1){
									$sixHTML .= '<div class="publicSpanOne" data-type="3" style="background:green;color:white;" data-mid="'.$messageID.'" data-state="2" data-clic="1" data-pid="'.$twoArr[$i]['pid'].'">'.$twoArr[$i]['pid'].$inputHidden.'</div>';
								}else{
									$sixHTML .= '<div class="publicSpanOne" data-type="3" data-mid="'.$messageID.'" data-state="1" data-clic="1" data-pid="'.$twoArr[$i]['pid'].'">'.$twoArr[$i]['pid'].'</div>';
								}
							}
						}
					}
				}else{
					$sixHTML .= '<div class="publicSpanOne" data-type="3" data-mid="'.$messageID.'" data-state="1" data-clic="1"  data-pid="'.$twoArr[$i]['pid'].'">'.$twoArr[$i]['pid'].'</div>';
				}
			}
			$sixHTML .= '<div style="clear:both"></div>';
			$this->assign("diseaseArrs",$sixHTML);
		}else if($info['type'] == 2){
			//所属科室
			$threeArr = $this->hope_disease_department->where('state=1')->select();
			$threeC = count($threeArr);
			$sixC = count($sixArr['departmentArr']);
			$sixHTML = '';
			
			for($i=0;$i<$threeC;$i++){
				$n = 0;
				$m = 0;
				if($sixC){
					for($s=0;$s<$sixC;$s++){
						if($threeArr[$i]['id'] == $sixArr['departmentArr'][$s]){
							$n = $n + 1;
						}else{
							$m = $m + 1;
						}
						if($n+$m == $sixC){
							if($n == 1){
								$sixHTML .= '<div class="publicSpan" data-type="4" style="background:red;color:white;" data-state="1" data-id="'.$threeArr[$i]['id'].'">'.$threeArr[$i]['cname'].'</div>';
								$sixHTML .= '<input name="department_id[]" value="'.$threeArr[$i]['id'].'" type="hidden">';
							}else{
								$sixHTML .= '<div class="publicSpan" data-type="4" data-state="2" data-id="'.$threeArr[$i]['id'].'">'.$threeArr[$i]['cname'].'</div>';
							}
						}
					}
				}else{
					$sixHTML .= '<div class="publicSpan" data-type="4" data-state="2" data-id="'.$threeArr[$i]['id'].'">'.$threeArr[$i]['cname'].'</div>';
				}
			}
			$sixHTML .= '<div style="clear:both"></div>';
			$this->assign("departmentArr",$sixHTML);
		}else if($info['type'] == 7){
			//所属种类
			$fourArr = $this->hope_disease_kind->where('state=1')->select();
			$sixC = count($sixArr['kindArr']);
			$twoC = count($fourArr);
			$sixHTML = '';
			for($i=0;$i<$twoC;$i++){
				$n = 0;
				$m = 0;
				if($sixC){
					for($s=0;$s<$sixC;$s++){
						if($fourArr[$i]['id'] == $sixArr['kindArr'][$s]){
							$n = $n + 1;
						}else{
							$m = $m + 1;
						}
						if($n+$m == $sixC){
							if($n == 1){
								$sixHTML .= '<div class="publicSpan" data-type="5" style="background:red;color:white;" data-state="1" data-id="'.$fourArr[$i]['id'].'">'.$fourArr[$i]['name'].'</div>';
								$sixHTML .= '<input name="kind_id[]" value="'.$fourArr[$i]['id'].'" type="hidden">';
							}else{
								$sixHTML .= '<div class="publicSpan" data-type="5" data-state="2" data-id="'.$fourArr[$i]['id'].'">'.$fourArr[$i]['name'].'</div>';
							}
						}
					}
				}else{
					$sixHTML .= '<div class="publicSpan" data-type="5" data-state="2" data-id="'.$fourArr[$i]['id'].'">'.$fourArr[$i]['name'].'</div>';
				}
				
			}
			$sixHTML .= '<div style="clear:both"></div>';
			$this->assign("kindArr",$sixHTML);
		}
		//加载左侧导航菜单缓存
		$this->assign("disease_kind",$disease_kind);
		$this->assign("messageID",$messageID);
		$this->assign("entry_type",$entry_type);
		$this->assign("disease_department",$disease_department);
		$this->assign("disease_type",$disease_type);
		$this->assign("info",$info);
		// dump($entry_type);
		// dump($info);die();
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","Message/message_detail");
        $this->assign("Js","Message/message_detail_js");
        $this->display("Conmons/Frame");
	}
	
	//执行修改当前资讯
	public function message_update(){
		//资讯类型ID
		$entry_id = trim(I('sign_type',''));
		if($entry_id == 1 || $entry_id == 4 || $entry_id == 6){
			$special_id_arr['country_id'] = I('country_id','');
			$special_id_arr['disease_id'] = I('disease_id','');
			$disCount = count($special_id_arr['disease_id']);
		}else if($entry_id == 2){
			$special_id_arr['country_id'] = I('country_id','');
			$special_id_arr['department_id'] = I('department_id','');
			$disCount = count($special_id_arr['department_id']);
		}else if($entry_id == 7){
			$special_id_arr['country_id'] = I('country_id','');
			$special_id_arr['kind_id'] = I('kind_id','');
			$disCount = count($special_id_arr['kind_id']);
		}
		
		//资讯文章ID
		$messageID = I('id','');
		//接收部分有效数据
		$data['cname'] = trim(I('cname',''));
		$data['ename'] = trim(I('ename',''));
		$data['type'] = $entry_id;
		$data['keywords'] = I('keywords','');
		$data['abstract'] = trim(I('abstract',''));
		$data['content'] = stripslashes($_POST['catalogContent']);
		
		$subject = strip_tags($data['content']);//去除html标签
		$pattern = '/\s/';//去除空白
		$content = preg_replace($pattern,'',$subject);  
		if(empty($data['abstract'])){
			$data['abstract'] = mb_substr($content,0,60,'utf-8');//截取80个汉字
		}
		//获取当前用户信息
		$userInfo = $this->session;
		$data['sid'] = $userInfo['uid'];
		$data['etime'] = time();
		//如果未上传封面图，则封面图默认取文章中第一个图
		if(I('uploadDivs11','')){
			//查询当前文件路径是否真是存在
			$fileid = I('uploadDivs11','');
			$url = $this->hope_uploads->where('id='.$fileid)->getField('url');
			if($url){
				$data['file_id'] = $fileid;
			}else{
				//取文章中第一个图片
				preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$data['content'],$match); 
				//将图片保存至文件表
				$file['url'] = $match[2];
				$file['uptime'] = time();
				$data['file_id'] = $this->hope_uploads->add($file);	
			}
		}else{
			//取文章中第一个图片
			preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$data['content'],$match); 
			//将图片保存至文件表
			$file['url'] = $match[2];
			$file['uptime'] = time();
			$data['file_id'] = $this->hope_uploads->add($file);	  
		}
		$data['statecode'] =2;
		//去除超链接
		$data['content'] = ereg_replace("<a [^>]*>|<\/a>","",$data['content']);
		//接收文件来源路径
		$fromUrl = trim(I('fromUrl',''));
		$fromUrl = str_replace(".html","",$fromUrl);
		//执行修改当前资讯
		$this->hope_message->where('id='.$messageID)->save($data);
		//修改次数自动加1
		$this->hope_message->where('id='.$messageID)->setInc('edit_num');
		//删除原有关联表信息
		$id_arr = $this->hope_link->where('sign_id='.$messageID)->select();
		if($id_arr){
			$idC = count($id_arr);
			for($i=0;$i<$idC;$i++){
				$state = $this->hope_link->where('id='.$id_arr[$i]['id'])->setField('state',2);
			}
		}
		//创建关联信息表数据
		//准备信息关联表数据
		$link['sign_id'] = $messageID;
		$link['sign_type'] = $entry_id;
		$link['type'] = 2;
		$link['ctime'] = time();
		for($d=0;$d<$disCount;$d++){
			if($entry_id == 1 || $entry_id == 4 || $entry_id == 6){
				$link['disease_id'] = $special_id_arr['disease_id'][$d];
				$link['department_id'] = 0;
				$link['country_id'] = $special_id_arr['country_id'];
				$link['kind_id'] = $this->hope_disease_type->where('id='.$special_id_arr['disease_id'][$d])->getField('kind_id');
				$link['body_id'] = $this->hope_disease_type->where('id='.$special_id_arr['disease_id'][$d])->getField('fid');
			}else if($entry_id == 2){
				$link['body_id'] = 0;
				$link['disease_id'] = 0;
				$link['country_id'] = $special_id_arr['country_id'];
				$link['kind_id'] = 0;
				$link['department_id'] = $special_id_arr['department_id'][$d];
			}else if($entry_id == 7){
				$link['body_id'] = 0;
				$link['disease_id'] = 0;
				$link['country_id'] = $special_id_arr['country_id'];
				$link['kind_id'] = $special_id_arr['kind_id'][$d];
				$link['department_id'] = 0;
			}
			$statecode = $this->hope_link->add($link);
		}
		if($statecode){
			$this->redirect($fromUrl);
		}else{
			$this->error('数据更新失败');
		}
	}
	
	//资讯状态更新
	public function message_examine(){
		$id = I('id');
		$state = I('state');
		$type = I('type');
		if(empty($id)){
			$output = array(
				'status' 	=>'2',
				'message'	=>'更新对象不存在'
			);
			$this->ajaxReturn($output);
		}
		if(empty($state)){
			$output = array(
				'status' 	=>'3',
				'message'	=>'更新对象状态码不存在'
			);
			$this->ajaxReturn($output);
		}
		if($type == 1){
			$status = $this->hope_message->where('id='.$id)->setField('statecode',$state);
			$status = $this->hope_message->where('id='.$id)->setField('stime',time());
		}else if($type == 2){
			$status = $this->hope_message->where('id='.$id)->setField('state',$state);
		}
		
		if($status){
			$output = array(
				'status' 	=>'1',
				'message'	=>'更新成功'
			);
			$this->ajaxReturn($output);
		}else{
			$output = array(
				'status' 	=>'4',
				'message'	=>'更新失败'
			);
			$this->ajaxReturn($output);
		}
	}
	
	//资讯任务分配管理
	public function messageTaskFunc(){
		import('ORG.Util.Page');// 导入分页类
		//获取来源
		$fromUrl = str_replace(".html","",$_SERVER['HTTP_REFERER']);
		$uSession = $this->session;
		//查询所有后台用户(部分管理员)
		if($uSession['uid'] == 3 || $uSession['uid'] == 1){
			//接收用户UID
			$sid = I('sid',0);
			$statecode = I('statecode',0);
			if($sid){
				$where['sid'] = array('eq',$sid);
			}else if($sid == 0){
				$where['sid'] = array('eq',0);
			}
			if($statecode){
				$where['statecode'] = array('eq',$statecode);
			}
			$this->assign('role',1);
		}else{
			$where['sid'] = $uSession['uid'];
			$this->assign('role',2);
		}
		
		$count = $this->hope_message->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Think\PageBootstrap($count,100);// 实例化分页类 传入总记录数和每页显示的记录数
		$message = $this->hope_message->where($where)->field('id,sid,cname,ctime,etime,statecode')->order('etime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$show = $Page->show();// 分页显示输出
		$this->assign('show',$show);
		$mcount = count($message);
		for($i=0;$i<$mcount;$i++){
			if($message[$i]['etime']){
				$message[$i]['etime'] = date('m-d H:i',$message[$i]['etime']);
			}
			$message[$i]['uname'] = $this->hope_user->where('uid='.$message[$i]['sid'])->getField('tname');
			if($message[$i]['statecode'] == 1){
				$message[$i]['statecode'] = '审核通过';
			}else if($message[$i]['statecode'] == 2){
				$message[$i]['statecode'] = '审核中';
			}else if($message[$i]['statecode'] == 3){
				$message[$i]['statecode'] = '审核失败';
			}
		}
		// dump($message);die();
		//遍历所有用户
		$uidArr = $this->user_adminrole->where('rid=8 && state=1 || rid=7 && state=1 || rid=6 && state=1')->select();
		$uCount = count($uidArr);
		for($i=0;$i<$uCount;$i++){
			$uidArr[$i]['uname'] = $this->hope_user->where('uid='.$uidArr[$i]['uid'])->getField('tname');
		}
		$this->assign('fromUrl',$fromUrl);
		$this->assign('count',$count);
		$this->assign('messcount',$mcount);
		$this->assign('uidArr',$uidArr);
		$this->assign('uidArrs',$uidArr);
		$this->assign('sid',$sid);
		$this->assign('statecode',$statecode);
		$this->assign('message',$message);
		$this->assign("LeftNavInfo",session('LeftNav'));
        $this->assign("Tel","Message/message_task");
        $this->assign("Js","Message/message_task_js");
        $this->display("Conmons/Frame");
	}
	
	public function message_detribute(){
		$uid = I('newUid','');
		$fromUrl = I('fromUrl','');
		if(empty($uid)){
			$this->error('请选择一个要转移数据的用户');
		}
		$midArr = I('mess','');
		if(empty($midArr)){
			$this->error('请选择至少一条要转移的数据');
		}
		// dump(I());die;
		$count = count($midArr);
		for($i=0;$i<$count;$i++){
			$state = $this->hope_message->where('id='.$midArr[$i])->setField('sid',$uid);
		}
		if($state){
			$this->redirect($fromUrl);
		}
	}
	
	//一键审核成功并上线
	public function quicklyOnline(){
		$message_id = I('message_id');
		//接收修改文件来源路径
		$fromUrl = trim(I('fromUrl',''));
		$fromUrl = str_replace(".html","",$fromUrl);
		dump(I());die;
		$this->hope_message->where('id='.$message_id)->setField('statecode',1);
		$this->hope_message->where('id='.$message_id)->setField('state',1);
		$linkState = $this->hope_link->where('sign_id='.$message_id.'&& type=2')->select();
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





















