<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UploadFileModel as UploadFile;

// +----------------------------------------------------------------------
// | 爱能社
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://langyue.org All rights reserved.
// +----------------------------------------------------------------------
// | 首页
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class IndexController extends LimitController {
	private $banner;//广告图表
	private $city;//城市表
	private $home_column;//首页栏目表
	private $home_content;//首页文章表
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		//实例化广告图表
		$this->banner     = D('banner');
		//实例化城市表
		$this->city     = D('city');
		//实例化首页栏目表
		$this->home_column     = D('home_column');
		//实例化首页文章表
		$this->home_content     = D('home_content');
		
	}
	
	/*
	 *  首页
	 *	返回数据格式 
		{
			'status'=>状态,
			'message'=>提示信息
			//省市区县列表信息
			'cityList'=array(
				每条省份数据=array(
					'cid'=>省份ID,
					'name'=>省份名称,
					'state'=>状态 1正常 2停用,
					'city'=>城市集=array(
						每条城市数据=array(
							'cid'=>城市ID,
							'name'=>城市名称,
							'fid'=>所属省份ID,
							'state'=>状态 1正常 2停用,
							'county'=>区县集=array(
								每条区县数据=array(
									'cid'=>区县ID,
									'name'=>区县名称,
									'fid'=>所属城市ID,
									'type'=>无用,
									'state'=>状态 1正常 2停用,
								)
							),
							'countyNum'=>区县数量,
						)		
					),
					'cityNum'=>城市数量,
				)
			),
			//顶部滚动图部分
			'imgList'=array(
				每条滚动图数据=array(
					'img'=>图片地址,
					'title'=>图片说明,
					'urltype'=>跳转类型,
					'url'=>跳转ID或跳转链接,
					'orders'=>图片排序,
					'state'=>状态 1正常 2停用,
					'cid'=>图片ID,
				)
			),
			//Banner图部分
			'bannerList'=array(
				每条Banner图数据=array(
					'img'=>图片地址,
					'title'=>图片说明,
					'urltype'=>跳转类型,
					'url'=>跳转ID或跳转链接,
					'orders'=>图片排序,
					'state'=>状态 1正常 2停用,
					'cid'=>图片ID,
				)
			),
			//底部栏目文章部分
			'column'=array(
				每条栏目数据=array(
					'cid'=>栏目ID,
					'name'=>栏目名称,
					'orders'=>栏目排序,
					'state'=>状态 1正常 2停用,
					'content'=>内容推荐=array(
						'cid'=>内容ID,
						'fid'=>所属栏目ID,
						'title'=>内容标题,
						'content'=>内容内容,
						'cover'=>内容的封面图,
						'ctime'=>内容的创建时间（时间戳）,
						'orders'=>内容排序,
						'recommend'=>内容推荐 1推荐 2不推荐,
						'state'=>状态 1正常 2停用,
					),
				),
			)
		}  
		status = {
			1：获取成功；
			2：获取失败；
		}
	***/
	public function index(){
		//**********获取省市区县************//
		
		//获取省份
		$cityList=$this->city->where('type=1 and state=1')->select();
		$cityListNum=count($cityList);
		//获取城市
		for($i=0;$i<$cityListNum;$i++){	
			$cityList[$i]['city']=$this->city->where('fid='.$cityList[$i]['cid'].' and type=2 and state=1')->select();
			if($cityList[$i]['city']){
				$cityList[$i]['cityNum']=count($cityList[$i]['city']);
			}else{
				$cityList[$i]['city']=array();
				$cityList[$i]['cityNum']="0";
			}
			//获取区县
			for($j=0;$j<$cityList[$i]['cityNum'];$j++){
				$cityList[$i]['city'][$j]['county']=$this->city->where('fid='.$cityList[$i]['city'][$j]['cid'].' and type=3 and state=1')->select();
				$cityList[$i]['city'][$j]['countyNum']=count($cityList[$i]['city'][$j]['county']);
				//去除无用标签
				unset($cityList[$i]['city'][$j]['type']);
			}
			//去除无用标签
			unset($cityList[$i]['fid']);
			unset($cityList[$i]['type']);
			
		}
		//**********获取首页顶部滚动图************//
		//获取首页广告图
		$imgList=$this->banner->where("type=1 and state=1")->order('orders')->limit(5)->select();
		if(!$imgList){
			$output = array(
				'status' 	=>'2',
				'message'	=>'获取失败'                 
			);
			$this->ajaxReturn($output);
		}
		$imgListNum=count($imgList);
		for($i=0;$i<$imgListNum;$i++){
			//替换ID字段
			$imgList[$i]['cid']=$imgList[$i]['id'];
			unset($imgList[$i]['id']);
			//图片地址补全
			$imgList[$i]['img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$imgList[$i]['img'];
			//处理NULL
			foreach($imgList[$i] as $k=>$v){
			   if($imgList[$i][$k]==null){
				   $imgList[$i][$k]="";
			   }
			}
			//去除无用标签
			unset($imgList[$i]['type']);
		}
		//**********获取banner************//
		$bannerList=$this->banner->where("type=2 and state=1")->order('orders')->limit(5)->select();
		if(!$bannerList){
			$output = array(
				'status' 	=>'2',
				'message'	=>'获取失败'                 
			);
			$this->ajaxReturn($output);
		}
		$bannerListNum=count($bannerList);
		for($i=0;$i<$bannerListNum;$i++){
			//替换ID字段
			$bannerList[$i]['cid']=$bannerList[$i]['id'];
			unset($bannerList[$i]['id']);
			//图片地址补全
			$bannerList[$i]['img']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$bannerList[$i]['img'];
			//处理NULL
			foreach($bannerList[$i] as $k=>$v){
			   if($bannerList[$i][$k]==null){
				   $bannerList[$i][$k]="";
			   }
			}
			//去除无用标签
			unset($imgList[$i]['type']);
		}
		//**********获取底部栏目文章************//
		$column=$this->home_column->where('state=1')->order('orders')->limit(4)->select();
		//获取推荐文章
		for($i=0;$i<4;$i++){
			$column[$i]['content']=$this->home_content->where('fid='.$column[$i]['cid'].' and recommend=1 and state=1')->order('orders')->find();
			$column[$i]['content']['cover']='http://'.$_SERVER['HTTP_HOST'].__ROOT__.$column[$i]['content']['cover'];
			//去除html标签
			$column[$i]['content']['content']=mb_substr(strip_tags($column[$i]['content']['content']),0,20,'utf-8');
		}
		$output = array(
			'status' 	=>'1',
			'message'	=>'获取成功',
			//省市区县列表
			'cityList'	=>$cityList,  
			//顶部滚动图
			'imgList'	=>$imgList,
			//banner
			'bannerList'	=>$bannerList, 
			//底部栏目文章
			'column'	=>$column, 
			                 
		);
		$this->ajaxReturn($output);		
	}
	
	
	/*
	* 首页内容展示
	* 接收数据格式  'cid'=>内容ID
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
			'info'=array(
				'cid'=>内容ID,
				'fid'=>所属栏目ID,
				'title'=>内容标题,
				'content'=>内容内容,
				'cover'=>内容封面,
				'ctime'=>内容创建时间,
				'orders'=>1,
				'recommend'=>1,
				'state'=>1,
			)
		} 
	status = {
		1：获取成功；
		2：缺少内容ID；
		3：获取失败；
	}
	*/
	public function indexContent(){
		$cid =I('cid','');     
		if(empty($cid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少内容ID'
			);
			$this->ajaxReturn($output);
		}
		//获取内容信息
		$contentInfo=$this->home_content->where('cid='.$cid.' and state=1')->find();
		if($contentInfo){
			$contentInfo['ctime']=date('Y-m-d H:i',$contentInfo['ctime']);
			$contentInfo['content']=$this->MacthContent($contentInfo['content']);
			$output = array(
					'status' 	=>'1',
					'message'	=>'获取成功',
					'info'	=>$contentInfo
			);
		}else{
			$output = array(
					'status' 	=>'3',
					'message'	=>'获取失败'
			);			
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