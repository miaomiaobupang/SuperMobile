<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UploadFileModel as UploadFile;

// +----------------------------------------------------------------------
// | 爱能社
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://langyue.org All rights reserved.
// +----------------------------------------------------------------------
// | 家庭教育（少年）管理
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class JuvenileController extends LimitController {
	private $juvenile;//少年表
	private $juvenile_article;//少年文章表
	private $juvenile_resource;//少年资源表
	private $juvenile_stage;//少年阶段表
	private $home_content;//首页文章表
	private $user;//用户表
	private $school;//学校表
	private $praise;//点赞表
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		//实例化少年表
		$this->juvenile     = D('juvenile');
		//实例化少年文章表
		$this->juvenile_article     = D('juvenile_article');
		//实例化少年资源表
		$this->juvenile_resource     = D('juvenile_resource');
		//实例化课程少年阶段表
		$this->juvenile_stage     = D('juvenile_stage');
		//实例化首页文章表
		$this->home_content     = D('home_content');
		//实例化用户表
		$this->user     = D('user');
		//实例化学校表
		$this->school     = D('school');
		//实例化点赞表
		$this->praise     = D('praise');
	}

	
	/*
	* 爱能幸福家庭俱乐部内容展示
	* 接收数据格式  无
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'familyList'=爱能幸福家庭俱乐部列表=array(
				每条文章数据=array(
					'cid'=>文章ID,
					'title'=>文章标题,
					'cover'=>文章封面,
					'ctime'=>文章时间
				)
			)
		} 
	status = {
		1：获取成功；
		2：获取失败；
	}
	*/
	public function familyindex(){
		//准备爱能幸福家庭教育俱乐部文章列表
		$familyList=$this->home_content->where('fid=5 and state=1')->field('cid,title,cover,ctime')->order('ctime desc')->select();
		if($familyList){
			$familyListNum=count($familyList);
			//准备今天早上时间
			$todays=strtotime(date('Y-m-d',time()));
			//准备昨天早上时间
			$todayz=$todays-86400;
			for($i=0;$i<$familyListNum;$i++){
				//准备时间
				if($familyList[$i]['ctime']<$todayz){
					$familyList[$i]['ctime']=date('Y-m-d',$familyList[$i]['ctime']);
				}else{
					if($familyList[$i]['ctime']<$todays){
						$familyList[$i]['ctime']="昨天";
					}else{
						$familyList[$i]['ctime']="今天";
					}
				}
				//补全封面地址
				$familyList[$i]['cover']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$familyList[$i]['cover'];
			}
			$output = array(
				'status' 	=>'1',
				'message'	=>'获取成功',
				//家庭教育类别课程列表
				'familyList'	=>$familyList,  
			);
		}else{
			$output = array(
				'status' 	=>'2',
				'message'	=>'获取失败' 
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 演讲培养计划内容展示
	* 接收数据格式  无
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'instructor'=少年教官列表=array(
				每条教官数据=array(
					'cid'=>教官ID,
					'name'=>教官姓名,
					'area'=>教官籍贯,
					'motto'=>爱能语录,
					'avatar'=>教官头像,
					'praise'=>教官赞数,
					'recommend'=>推荐 1推荐 2不推荐,
					'stage'=>教官阶段,
				)
			),
			'speech'=演讲家列表=array(
				每条教官数据=array(
					'cid'=>演讲家ID,
					'name'=>演讲家姓名,
					'area'=>演讲家籍贯,
					'motto'=>爱能语录,
					'avatar'=>演讲家头像,
					'praise'=>演讲家赞数,
					'recommend'=>推荐 1推荐 2不推荐,
					'stage'=>演讲家阶段,
				)
			),
		} 
		status = {
			1：获取成功；
			2：获取失败；
		}
	*/
	public function speechindex(){
		//准备少年列表
		$juvenileList=$this->juvenile->where('state=1')->select();
		if($juvenileList){
			//循环处理数据并分组
			$juvenileListNum=count($juvenileList);
			for($i=0;$i<$juvenileListNum;$i++){
				//补全头像地址
				$juvenileList[$i]['avatar']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$juvenileList[$i]['avatar'];
				//获取阶段类别
				$juvenileList[$i]['stage']=$juvenileList[$i]['stagetext'];
				$juvenileList[$i]['stage'].=$this->juvenile_stage->getFieldByCid($juvenileList[$i]['stageclass'],'name');
				//去除无用元素
				unset($juvenileList[$i]['state']);
				unset($juvenileList[$i]['stageclass']);
				unset($juvenileList[$i]['stagetext']);
				//分组
				if($juvenileList[$i]['type']==1){
					//去除无用元素
					unset($juvenileList[$i]['type']);
					$instructor[]=$juvenileList[$i];
				}else{
					//去除无用元素
					unset($juvenileList[$i]['type']);
					$speech[]=$juvenileList[$i];
				}
			}
			$output = array(
				'status' 	=>'1',
				'message'	=>'获取成功',
				//教官列表
				'instructor'	=>$instructor,
				//演讲家列表
				'speech'	=>$speech
			);
		}else{
			$output = array(
				'status' 	=>'2',
				'message'	=>'获取失败' 
			);
		}
		$this->ajaxReturn($output);		
	}
	
	
	/*
	* 少年教官/演讲家成长蜕变列表展示
	* 接收数据格式  'cid'=>少年教官/演讲家ID,'page'=>页数（默认为1）,'size'=>每页条数（默认为10条）
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'growUpList'=成长蜕变列表=array(
				每条文章数据=array(
					'cid'=>文章ID,
					'title'=>文章标题,
					'cover'=>文章封面,
				)
			)
		} 
	status = {
		1：获取成功；
		2：缺少少年教官/演讲家ID；
		3：获取数据失败（无数据）；
	}
	*/
	public function growUp(){
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少少年教官/演讲家ID'
			);
			$this->ajaxReturn($output);
		}
		//准备分页
		$page   = intval(I('page')) ? intval(I('page')) : 1;
	    $size   =  intval(I('size')) ? intval(I('size')) : 10;
		$growUpList=$this->juvenile_article->where('fid='.$cid.' and state=1')->field('cid,title,cover')->order('ctime desc')->page($page,$size)->select();
		if($growUpList){
			$growUpListNum=count($growUpList);
			for($i=0;$i<$growUpListNum;$i++){
				//补全封面地址
				$growUpList[$i]['cover']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$growUpList[$i]['cover'];
			}
			$output = array(
					'status' 	=>'1',
					'message'	=>'获取数据成功',
					'growUpList'	=>$growUpList
			);
		}else{
			$output = array(
					'status' 	=>'3',
					'message'	=>'获取数据失败（无数据）'
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 少年教官/演讲家成长蜕变文章详情页展示
	* 接收数据格式  'cid'=>成长蜕变ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'info'=成长蜕变列表=array(
				'cid'=>文章ID,
				'title'=>文章标题,
				'content'=>文章内容,
				'ctime'=>创建时间,
			)
		} 
	status = {
		1：获取成功；
		2：缺少成长蜕变ID；
		3：获取失败；
	}
	*/
	public function growUpShow(){
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少成长蜕变ID'
			);
			$this->ajaxReturn($output);
		}
		$articleInfo=$this->juvenile_article->where('cid='.$cid.' and state=1')->field('cid,title,content,ctime')->find();
		if($articleInfo){
			$articleInfo['ctime']=date('Y-m-d H:i',$articleInfo['ctime']);
			$articleInfo['content']=$this->MacthContent($articleInfo['content']);
			$output = array(
					'status' 	=>'1',
					'message'	=>'获取成功',
					'info'	=>$articleInfo
			);
		}else{
			$output = array(
					'status' 	=>'3',
					'message'	=>'获取失败'
			);			
		}
		$this->ajaxReturn($output);
	}
	/*
	* 少年教官/演讲家个人相册列表展示
	* 接收数据格式  'cid'=>少年教官/演讲家ID,'page'=>页数（默认为1）,'size'=>每页条数（默认为10条）
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'resource'=相册列表=array(
				每条照片数据=array(
					'cid'=>照片ID,
					'url'=>照片地址,
				)
			)
		} 
	status = {
		1：获取成功；
		2：缺少少年教官/演讲家ID；
		3：获取数据失败（无数据）；
	}
	*/
	public function photo(){
		
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少少年教官/演讲家ID'
			);
			$this->ajaxReturn($output);
		}
		//准备分页
		$page   = intval(I('page')) ? intval(I('page')) : 1;
	    $size   =  intval(I('size')) ? intval(I('size')) : 10;
		//准备相册列表信息
		$resource = $this->juvenile_resource->where('fid='.$cid.' and type=1 and state=1')->field('cid,url')->order('ctime desc')->page($page,$size)->select();
		if($resource){
			$resourceNum=count($resource);
			for($i=0;$i<$resourceNum;$i++){
				//补全照片地址
				$resource[$i]['url']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$resource[$i]['url'];
			}
			$output = array(
					'status' 	=>'1',
					'message'	=>'获取数据成功',
					'resource'	=>$resource
			);
		}else{
			$output = array(
					'status' 	=>'3',
					'message'	=>'获取数据失败（无数据）'
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 少年教官/演讲家视频列表展示
	* 接收数据格式  'cid'=>少年教官/演讲家ID,'page'=>页数（默认为1）,'size'=>每页条数（默认为10条）
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'resource'=视频列表=array(
				每条视频数据=array(
					'cid'=>视频ID,
					'url'=>视频地址,
					'imgurl'=>视频封面地址,
					'title'=>视频标题,
				)
			)
		} 
	status = {
		1：获取成功；
		2：缺少少年教官/演讲家ID；
		3：获取数据失败（无数据）；
	}
	*/
	public function video(){
		
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少少年教官/演讲家ID'
			);
			$this->ajaxReturn($output);
		}
		//准备分页
		$page   = intval(I('page')) ? intval(I('page')) : 1;
	    $size   =  intval(I('size')) ? intval(I('size')) : 10;
		//准备视频列表信息
		$resource = $this->juvenile_resource->where('fid='.$cid.' and type=2 and state=1')->field('cid,url,imgurl,title')->order('ctime desc')->page($page,$size)->select();
		if($resource){
			$resourceNum=count($resource);
			for($i=0;$i<$resourceNum;$i++){
				//补全视频截图地址
				$resource[$i]['imgurl']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$resource[$i]['imgurl'];
			}
			$output = array(
					'status' 	=>'1',
					'message'	=>'获取数据成功',
					'resource'	=>$resource
			);
		}else{
			$output = array(
					'status' 	=>'3',
					'message'	=>'获取数据失败（无数据）'
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 少年点赞
	* 接收数据格式  'cid'=>所属少年ID,'uid'=>用户ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：点赞成功；
		2：缺少少年ID;
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
					'message'	=>'缺少少年ID'
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
		//判断该用户是否对该少年已经点赞
		$praise=$this->praise->where('fid='.$cid.' and uid='.$uid.'type=2')->find();
		if($praise){
			$output = array(
				'status' 	=>'4',
				'message'	=>'该用户已点赞'
			);
		}else{
			$state=$this->juvenile ->where('cid='.$cid.' and state=1')->setInc('praise');//少年赞数总次数+1
			if($state){
				$data=null;
				$data['fid']=$cid;
				$data['uid']=$uid;
				$data['type']=2;
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
	
	
	protected function MacthContent($content) {
       preg_match_all('/src="(.*[jpeg|jpg|png|gif])"/iU',$content,$images);
       if(!empty($images[1]))
	   {
	    foreach($images[1] as $key=>$v)
	    {
		 $images[1][$key] = str_replace('phpheyi/phpheyi','phpheyi',$this->formatImg($v));
		 
	    }
	    unset($images[0]);
	   }
	   $images[1] = array_unique($images[1]);
		
	   $content = preg_split('/<img[^>]*>/iU',$content); //匹配图片
		
		foreach($content as $key=>$v)
		{
			 
			 
			$str = $this->Format($v);
			 if($str){
				  $data[] =  array('text'=>$str);
			 }
		   
			if(isset($images[1][$key]))
			{
				$data[] = array('image'=>$images[1][$key]);
			}
		}
		
		foreach($data as $key=>$v) {
			if(isset($data[$key]['image']) && isset($data[$key+1]['text'])) {
			   $data[$key+1]['text'] = ltrim($data[$key+1]['text'],"\n");
			}
		
		}
        
        return $data;
    }
	
	/**
	*内部格式化函数
	*
	*/
	
	private function Format($content) {
	
	   
	  
	    $arr = array(
		   "\r\n",
		   "\n",
		   "\r\n\r\n",
		   "\n       ",
		   " ",
		   " \n        ",
		   "\n       \n       ",
		   "\n        ",
		   "\n        \n        ",
		   "\n        \n       ",
		   "\n         ",
		   "\n       ",

		   
		);
        if(preg_match('/[^\x7f-\xff\x{4e00}-\x{9fa5}]+/u',$content)){
		  
		   $content = str_replace(array("\r\n"),"\n",$content); 
           $content = ltrim($content,"\n");		
           $content = str_replace("<p>
    <span style=\"font-family:微软雅黑, Microsoft YaHei;font-size:12px\"><br/></span>
</p>","",$content); 
           $content = str_replace("<p></p>","",$content); 	
           
          
           $content = ltrim($content,"\r\n");
           $content = ltrim($content,"\n");		   
		   $content = preg_replace("/<p[^>]*>/iU","\n       ",$content);
		   $content = strip_tags($content);
		 
		   //$content = str_replace(array("\r\n"),"\n",$content);
		    //$content = ltrim($content); ↵         ↵         ↵   
           $br = array('&nbsp;',
		            "       \n       \n        ",
					"\n       \n        ",
					"\n       \n       ",
					"\n        \n       ",
					"\n       ‍‍\n       ‍‍  ",
					
					);	//"\n         \n         \n        ‍‍‍",
           $br2 = array("",
		             "\n       ",
					 "\n       ",
					 "\n       ",
					 "\n       ",
					 
					 "\n       ");	//"\n        ",			‍‍‍
			$content = str_replace($br,$br2,$content);
			
		    $content = "\n        ".trim($content);
		   if(in_array($content,$arr)) return null;
		  $contents = explode("\n",$content);
		   foreach($contents as $k=>$v) {
		     if($contents[$k] !='') $contents[$k] = "\n       ".ltrim($contents[$k]);
		   }
		   $content = implode('',$contents);
		  //$content = str_replace(array("      ",'        ','         ',"       "),"       ",$content);
		   return $content;
	   } else {
	       return null;
	   }
	   
	   
	}
	
	/**
	*格式化图片地址【栏目内容图片传送专用】
	*
	*/
	protected function formatImg($imgpath) {
		return substr($imgpath,0,4) == "http" ? $imgpath : 'http://'.$_SERVER['HTTP_HOST'].$imgpath;
	
	}
}