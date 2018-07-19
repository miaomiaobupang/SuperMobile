<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UploadFileModel as UploadFile;

// +----------------------------------------------------------------------
// | 爱能社
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://langyue.org All rights reserved.
// +----------------------------------------------------------------------
// | 课程管理
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class CurriculumController extends LimitController {
	private $curriculum;//课程表
	private $curriculum_type;//课程类型表
	private $user;//用户表
	private $school;//学校表
	private $comment;//评论表
	private $praise;//点赞表

	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		//实例化课程表
		$this->curriculum     = D('curriculum');
		//实例化课程类型表
		$this->curriculum_type     = D('curriculum_type');
		//实例化用户表
		$this->user     = D('user');
		//实例化学校表
		$this->school     = D('school');
		//实例化评论表
		$this->comment     = D('comment');
		//实例化点赞表
		$this->praise     = D('praise');
		
	}
	
	//课程过期检测
	public function due(){
		//获取未过期的课程列表
		$where['state']=1;
		$where['statecode']=array('lt',3);
		$where['etime']=array('neq',0);
		$curriculumList = $this->curriculum->where($where)->select();
		$curriculumListNum=count($curriculumList);
		for($i=0;$i<$curriculumListNum;$i++){
			if($curriculumList[$i]['statecode']==1 and $curriculumList[$i]['stime']<time()){
				//更新该课程为进行状态
				$this->curriculum->where("cid = ".$curriculumList[$i]['cid'])->setField('statecode','2');
			}
			if($curriculumList[$i]['statecode']==2 and $curriculumList[$i]['etime']<time()){
				//更新该课程为过期状态
				$this->curriculum->where("cid = ".$curriculumList[$i]['cid'])->setField('statecode','3');
			}
		}
	}
	
	
	/*
	* 首页内容展示
	* 接收数据格式  'cid'=>内容ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'familyList'=家庭教育类别课程列表(10条)=array(
				每条课程数据=array(
					'cid'=>课程ID,
					'title'=>课程标题,
					'cover'=>课程封面,
					'img'=>课程内容页大图,
					'grade'=>课程积分
				)
			),
			'subjectList'=学科教育类别课程列表(10条)=array(
				每条课程数据=array(
					'cid'=>课程ID,
					'title'=>课程标题,
					'address'=>课程地址,
					'stime'=>课程开始时间,
					'etime'=>课程结束时间,
					'cover'=>课程封面,
					'img'=>课程内容页大图,
					'grade'=>课程评分,
					'praise'=>课程点赞数,
					'share'=>课程分享数,
					'comment'=>课程评论数,
					'statetext'=>课程状态文本,
				)
			)
		} 
	status = {
		1：获取成功；
	}
	*/
	public function index(){
		//检测课程过期
		$this->due();
		//准备家庭教育类别课程列表
		$familyList=$this->curriculum->where('state=1 and class=1')->field('cid,title,cover,img,grade')->order('orders')->limit(10)->select();
		$familyListNum=count($familyList);
		for($i=0;$i<$familyListNum;$i++){
			//补全封面地址
			$familyList[$i]['cover']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$familyList[$i]['cover'];
			$familyList[$i]['img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$familyList[$i]['img'];
		}
		//准备学科教育课程列表
		$subjectList=$this->curriculum->where('state=1 and class=2')->field('cid,title,address,stime,etime,statecode,cover,img,grade,praise,share,comment')->order('stime desc')->select();
		$subjectListNum=count($subjectList);
		for($i=0;$i<$subjectListNum;$i++){
			//补全地址
			$subjectList[$i]['cover']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$subjectList[$i]['cover'];
			$subjectList[$i]['img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$subjectList[$i]['img'];
			//准备时间格式
			$subjectList[$i]['stime']=date("Y/m/d",$subjectList[$i]['stime']);
			$subjectList[$i]['etime']=date("Y/m/d",$subjectList[$i]['etime']);
			//准备状态文本
			if($subjectList[$i]['statecode']==1){
				$subjectList[$i]['statetext']='即将开始';
			}else if($subjectList[$i]['statecode']==2){
				$subjectList[$i]['statetext']='正在进行';
			}else{
				$subjectList[$i]['statetext']='已经过期';
			}
			//去除无用标签
			unset($subjectList[$i]['statecode']);
		}
		$output = array(
			'status' 	=>'1',
			'message'	=>'获取成功',
			//家庭教育类别课程列表
			'familyList'	=>$familyList,  
			//学科教育课程列表
			'subjectList'	=>$subjectList,   
		);
		$this->ajaxReturn($output);
	}
	
	/*
	* 课程详情展示
	* 接收数据格式  'cid'=>课程ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'info'=array(
				'cid'=>课程ID,
				'title'=>课程标题,
				'schoolid'=>所属学校名称,
				'address'=>课程地址,
				'type'=>课程类型,
				'phone'=>课程联系电话,
				'cover'=>课程封面图片,
				'img'=>课程内容页大图,
				'descs'=>课程描述,
				'stime'=>课程开始时间,
				'etime'=>课程结束时间,
				'ctime'=>课程创建时间,
				'grade'=>课程评分,
				'comment'=>课程评论数,
				'praise'=>课程点赞数,
				'share'=>课程分享数,
				'recommend'=>推荐 1推荐 2不推荐,
				'orders'=>排序,
				'statecode'=>课程状态码,
				'state'=>课程状态 1开启 2停用,
				'statetext'=>课程状态文本,
			)
		} 
	status = {
		1：获取课程信息成功；
		2:缺少课程ID;
		3:获取课程信息失败;
	}
	*/
	public function info(){
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少课程ID'
			);
			$this->ajaxReturn($output);
		}
		//获取课程信息
		$info=$this->curriculum ->where('cid='.$cid.' and state=1')->find();
		if($info){
			//获取课程类型
			$info['type']=$this->curriculum_type->getFieldByCid($info['type'],'name');
			//补全封面图地址
			$info['cover']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$info['cover'];
			$info['img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$info['img'];
			//处理时间格式
			$info['stime']=date("Y/m/d",$info['stime']);
			$info['etime']=date("Y/m/d",$info['etime']);
			$info['ctime']=date("Y/m/d",$info['ctime']);
			//准备状态文本
			if($info['statecode']==1){
				$info['statetext']='即将开始';
			}else if($info['statecode']==2){
				$info['statetext']='正在进行';
			}else{
				$info['statetext']='已经过期';
			}
			//去除无用标签
			unset($info['class']);
			$output = array(
					'status' 	=>'1',
					'message'	=>'获取课程信息成功',
					'info'	=>$info
			);
			$this->ajaxReturn($output);
		}else{
			$output = array(
					'status' 	=>'3',
					'message'	=>'获取课程信息失败'
			);
			$this->ajaxReturn($output);
		}
		
	}
	
	/*
	* 课程详情展示(带评论版)
	* 接收数据格式  'cid'=>课程ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'info'=array(
				'cid'=>课程ID,
				'title'=>课程标题,
				'schoolid'=>所属学校名称,
				'address'=>课程地址,
				'type'=>课程类型,
				'phone'=>课程联系电话,
				'cover'=>课程封面图片,
				'img'=>课程内容页大图,
				'descs'=>课程描述,
				'stime'=>课程开始时间,
				'etime'=>课程结束时间,
				'ctime'=>课程创建时间,
				'grade'=>课程评分,
				'comment'=>课程评论数,
				'praise'=>课程点赞数,
				'share'=>课程分享数,
				'recommend'=>推荐 1推荐 2不推荐,
				'orders'=>排序,
				'statecode'=>课程状态码,
				'state'=>课程状态 1开启 2停用,
				'statetext'=>课程状态文本,
			),
			'commentList'=array(
				每条评论数据=array(
					'cid'=>评论ID,
					'fid'=>所属课程ID,
					'uid'=>用户昵称,
					'contment'=>评论内容,
					'ctime'=>评论时间,
					'state'=>状态 1开启 2停用,
				)
			)
		} 
	status = {
		1：获取课程信息成功；
		2:缺少课程ID;
		3:获取课程信息失败;
	}
	*/
	public function infoComment(){
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少课程ID'
			);
			$this->ajaxReturn($output);
		}
		//获取课程信息
		$info=$this->curriculum ->where('cid='.$cid.' and state=1')->find();
		if($info){
			//获取课程类型
			$info['type']=$this->curriculum_type->getFieldByCid($info['type'],'name');
			//补全封面图地址
			$info['cover']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$info['cover'];
			$info['img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$info['img'];
			//处理时间格式
			$info['stime']=date("Y/m/d",$info['stime']);
			$info['etime']=date("Y/m/d",$info['etime']);
			$info['ctime']=date("Y/m/d",$info['ctime']);
			//准备状态文本
			if($info['statecode']==1){
				$info['statetext']='即将开始';
			}else if($info['statecode']==2){
				$info['statetext']='正在进行';
			}else{
				$info['statetext']='已经过期';
			}
			//去除无用标签
			unset($info['class']);
			//获取该课程的评论信息
			$commentList=$this->comment ->where('fid='.$cid.' and state=1 and type=1')->order('ctime desc')->select();
			if($commentList){
				//循环处理数据
				$commentListNum=count($commentList);
				for($i=0;$i<$commentListNum;$i++){
					//获取用户呢称
					$commentList[$i]['uid']=$this->user->getFieldByUid($commentList[$i]['uid'],'nickname');
					//处理时间格式
					$commentList[$i]['ctime']=date("Y年m月d日",$commentList[$i]['ctime']);
				}
			}else{
				$commentList=array();
			}
			$output = array(
					'status' 	=>'1',
					'message'	=>'获取课程信息成功',
					'info'	=>$info,
					'commentList'	=>$commentList
			);
			$this->ajaxReturn($output);
		}else{
			$output = array(
					'status' 	=>'3',
					'message'	=>'获取课程信息失败'
			);
			$this->ajaxReturn($output);
		}
		
	}
	
	/*
	* 课程评论列表
	* 接收数据格式  'cid'=>课程ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'commentList'=array(
				每条评论数据=array(
					'cid'=>评论ID,
					'fid'=>所属课程ID,
					'uid'=>用户昵称,
					'contment'=>评论内容,
					'ctime'=>评论时间,
					'state'=>状态 1开启 2停用,
				)
			)
		} 
	status = {
		1：获取课程评论列表成功；
		2:缺少课程ID;
		3:获取课程评论列表失败（无数据）;
	}
	*/
	public function commentList(){
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少课程ID'
			);
			$this->ajaxReturn($output);
		}
		//获取该课程的评论信息
		$commentList=$this->comment ->where('fid='.$cid.' and state=1 and type=1')->order('ctime desc')->select();
		if($commentList){
			//循环处理数据
			$commentListNum=count($commentList);
			for($i=0;$i<$commentListNum;$i++){
				//获取用户呢称
				$commentList[$i]['uid']=$this->user->getFieldByUid($commentList[$i]['uid'],'nickname');
				//处理时间格式
				$commentList[$i]['ctime']=date("Y年m月d日",$commentList[$i]['ctime']);
			}
			$output = array(
				'status' 	=>'1',
				'message'	=>'获取课程评论列表成功',
				'commentList'	=>$commentList
			);
		}else{
			$output = array(
				'status' 	=>'3',
				'message'	=>'获取课程评论列表失败（无数据）'
			);
		}
		$this->ajaxReturn($output);
	}
	
	/*
	* 课程点赞
	* 接收数据格式  'cid'=>课程ID,'uid'=>用户ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：点赞成功；
		2：缺少课程ID;
		3：缺少用户ID;
		4：该用户已点赞；
		5：点赞失败;
	}
	*/
	public function praise(){
		$cid =I('cid','');     
		$uid =I('uid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少课程ID'
			);
			$this->ajaxReturn($output);
		}
		if(empty($uid)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少用户ID'
			);
			$this->ajaxReturn($output);
		}
		//判断该用户是否对该课程已经点赞
		$praise=$this->praise->where('fid='.$cid.' and uid='.$uid)->find();
		if($praise){
			$output = array(
				'status' 	=>'4',
				'message'	=>'该用户已点赞'
			);
		}else{
			$state=$this->curriculum ->where('cid='.$cid.' and state=1')->setInc('praise');//课程赞数总次数+1
			if($state){
				$data=null;
				$data['fid']=$cid;
				$data['uid']=$uid;
				$data['type']=1;
				$praiseId=$this->praise->add($data);
				if($praiseId){
					$output = array(
						'status' 	=>'1',
						'message'	=>'点赞成功'
					);
				}else{
					$output = array(
						'status' 	=>'5',
						'message'	=>'点赞失败'
					);
				}	
			}else{
				$output = array(
					'status' 	=>'5',
					'message'	=>'点赞失败'
				);
			}
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 课程分享
	* 接收数据格式  'cid'=>课程ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
		status = {
			1：分享成功；
			2：缺少课程ID;
			3：分享失败;
		}
	*/
	public function share(){
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少课程ID'
			);
			$this->ajaxReturn($output);
		}
		$state=$this->curriculum ->where('cid='.$cid.' and state=1')->setInc('share');//课程分享数总次数+1
		if($state){
			$output = array(
				'status' 	=>'1',
				'message'	=>'分享成功'
			);
		}else{
			$output = array(
				'status' 	=>'3',
				'message'	=>'分享失败'
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 课程评论
	* 接收数据格式  'cid'=>课程ID,'uid'=>用户ID,'contment'=>评论内容
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
		status = {
			1：评论成功；
			2：缺少课程ID;
			3：缺少用户ID;
			4：缺少用户评论内容;
			5：评论失败;
			6：评论数自增失败;
		}
	*/
	public function comment(){
		$cid =I('cid',''); 
		$uid =I('uid','');  		
		$contment =I('contment','');  		
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少课程ID'
			);
			$this->ajaxReturn($output);
		}  
		if(empty($uid)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少用户ID'
			);
			$this->ajaxReturn($output);
		}
		if(empty($contment)){
			$output = array(
					'status' 	=>'4',
					'message'	=>'缺少用户评论内容'
			);
			$this->ajaxReturn($output);
		}
		$data=null;
		$data['fid']=$cid;
		$data['uid']=$uid;
		$data['contment']=$contment;
		$data['ctime']=time();
		$data['type']=1;
		$contmentId=$this->comment->add($data);
		if($contmentId){
			//课程评论数自增
			$state=$this->curriculum ->where('cid='.$cid.' and state=1')->setInc('comment');//课程评论数总次数+1
			if($state){
				$output = array(
					'status' 	=>'1',
					'message'	=>'评论成功'
				);
			}else{
				$output = array(
					'status' 	=>'6',
					'message'	=>'评论数自增失败'
				);
			}
			$this->ajaxReturn($output);
		}else{
			$output = array(
					'status' 	=>'5',
					'message'	=>'评论失败'
			);
			$this->ajaxReturn($output);
		}
	}
}