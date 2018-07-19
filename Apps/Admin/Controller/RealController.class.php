<?php 
	namespace Admin\Controller;
	use Think\Controller;
	use Home\Model\LogFileModel as LogFile;
	use Home\Model\CommonFileModel as CommonFile;
// +----------------------------------------------------------------------
// | 超级医生WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://hopenoah.com All rights reserved.
// +----------------------------------------------------------------------
// | 词条控制器
// +----------------------------------------------------------------------
// | Author: Davin <yulong@hopenoah.com>
// +----------------------------------------------------------------------
	class RealController extends LimitController{
		private $citys;//城市表
		private $exhibition_pic;//展会类图片表
		private $exhibition_venue_config;//展会分展馆配置表
		private $exhibition_trade;//行业标签表
		private $real_enterprise_auth;//真实企业认证表
		private $exhibition_venue_info;//展馆信息表
		private $enterprise_user;//企业用户管理表
		private $user;//用户表
		private $real_name_auth;//真实姓名认证表
		private $enterprise_user_group;//企业用户管理表
		private $enterprise_auth_rule;//企业权限组管理表
		private $enterprise_level;//企业级别表
		private $log_user;//日志用户表
		private $exhibition_ledger_total;//企业总金额表
		private $bill_contents;//发票表
		private $order;//订单表
		private $exhibition_position;//展位表
		private $contract_order;//企业合同表
		private $contract_enterprise_templet;//企业合同条款表
		private $exhibition_venue;//展馆表
		private $exhibition_info;//展会信息表
		private $exhibition;//展会表
		private $log_record;//日志表
		//加载非速搜游客用户信息显示页面
		/**
		 * 构造方法
		 */
		public function __construct() {
			parent::__construct();
			//实例化展会场馆表
			$this->citys     = D('citys');
			$this->exhibition_pic     = D('exhibition_pic');
			$this->exhibition_venue_config     = D('exhibition_venue_config');
			$this->exhibition_trade     = D('exhibition_trade');
			$this->real_enterprise_auth     = D('real_enterprise_auth');
			$this->exhibition_venue_info     = D('exhibition_venue_info');
			$this->user  = D('user');
			$this->real_name_auth  = D('real_name_auth');
			$this->enterprise_user     = D('enterprise_user');
			$this->enterprise_user_group     = D('enterprise_user_group');
			$this->enterprise_auth_rule     = D('enterprise_auth_rule');
			$this->enterprise_level     = D('enterprise_level');
			$this->exhibition_ledger_total     = D('exhibition_ledger_total');
			$this->bill_contents     = D('bill_contents');
			$this->order     = D('order');
			$this->exhibition_position     = D('exhibition_position');
			$this->contract_order     = D('contract_order');
			$this->contract_enterprise_templet     = D('contract_enterprise_templet');
			$this->exhibition_venue     = D('exhibition_venue');
			$this->exhibition_info     = D('exhibition_info');
			$this->exhibition     = D('exhibition');
			$this->log_record     = D('log_record');
			$this->log_user  = D('log_user');
			//实例化日志类
			$this->logM = new LogFile();
			//实例化公共类
			$this->common = new CommonFile();
		}
		/**
		【展示页】企业列表页
		接受的值 无
		返回的值 
		{
			
		}
		***/
		public function index(){
	        //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","Real/index");
	        $this->assign("Js","Real/indexjs");
	        //实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120101;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业-查看列表";
			//获取检索内容
			$name=I('name','');
			//获取页码
			$p=I('p','');
			if($name){
				$where['name'] = array('LIKE','%'.$name.'%');
				$types['typeid']=120120103;
				$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业-进行检索-检索内容:".$name;
			}
			$where['statecode']='1';
			$where['state']='1';
			//执行写入缓存操作
			$this->logM->logAdd($types);
			$args['name']=$name;
			$count = $this->real_enterprise_auth->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->real_enterprise_auth ->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('ktime DESC') ->select();
			//判断是否是后台admin登陆
			if($_SESSION['uid']==1){
				$num=1;
			}
			//判断企业是否存在手机号码
			$len=count($data);
			for($i=0;$i<$len;$i++){
				$real[$i]=$this->user->where('uid='.$data[$i]['uid'])->field('uid,uphone,tname,nickname')->find();
				if($real[$i]['uphone']!=NULL){
					$data[$i]['uphoneNum']=1;
				}
				//企业负责人
				$data[$i]['tname']=$real[$i]['tname'];
				$data[$i]['nickname']=$real[$i]['nickname'];
			}
			$this->assign('row',$show);
			$this->assign('num',$num);
			$this->assign('datas',$data);
			$this->assign('p',$p);
			$this->assign('jname',$name);
	        $this->display("Conmons/Frame");
		}
		/**
		【展示页】未认领企业列表页
		接受的值 无
		返回的值 
		{
			
		}
		***/
		public function Unclaimed_index(){
	        //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","Real/Unclaimed_index");
	        $this->assign("Js","Real/Unclaimed_indexjs");
	        //实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120106;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-未认领企业-查看列表";
			
			$name=I('name','');
			if($name){
				$where['name'] = array('LIKE','%'.$name.'%');
				$types['typeid']=120120105;
				$types['title']="记录".$userName."用户-非速搜企业管理-未认领企业-进行检索-检索内容:".$name;
			}
			$where['statecode']='4';
			//执行写入缓存操作
			$this->logM->logAdd($types);
			$args['name']=$name;
			$count = $this->real_enterprise_auth->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->real_enterprise_auth ->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC') ->select();
			
			$len=count($data);
			for($i=0;$i<$len;$i++){
				$lever['levelName']=$this->enterprise_level->where('id='.$data[$i]['level'])->getField('name');
				$data[$i]['levelName']=$lever['levelName'];
			}
			// dump($data);exit;
			$this->assign('row',$show);
			$this->assign('datas',$data);
	        $this->display("Conmons/Frame");
		}
		/**
		【展示页】企业审核列表页
		接受的值 无
		返回的值 
		{
			
		}
		***/
		public function real_checkList(){
	        //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","Real/real_checkList");
	        $this->assign("Js","Real/real_checkListjs");
	        //实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120107;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-待审核企业-查看列表";
			
			$name=I('name','');
			if($name){
				$where['name'] = array('LIKE','%'.$name.'%');
				$types['typeid']=120120104;
				$types['title']="记录".$userName."用户-非速搜企业管理-待审核企业-进行检索-检索内容:".$name;
			}
			$where['statecode'] = array('in','2,5');
			//执行写入缓存操作
			$this->logM->logAdd($types);
			$args['name']=$name;
			$count = $this->real_enterprise_auth->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->real_enterprise_auth ->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC') ->select();
			$len=count($data);
			for($i=0;$i<$len;$i++){
				//企业负责人
				$real[$i]=$this->user->where('uid='.$data[$i]['uid'])->field('tname,nickname')->find();
				$data[$i]['tname']=$real[$i]['tname'];
				$data[$i]['nickname']=$real[$i]['nickname'];
			}
			$this->assign('row',$show);
			$this->assign('datas',$data);
	        $this->display("Conmons/Frame");
		}
		/**
		【展示页】企业审核失败列表页
		接受的值 无
		返回的值 
		{
			
		}
		***/
		public function real_checkFalse(){
	        //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","Real/real_checkFalse");
	        $this->assign("Js","Real/real_checkFalsejs");
	        ///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120108;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-未通过企业-查看列表";
			
			$name=I('name','');
			if($name){
				$where['name'] = array('LIKE','%'.$name.'%');
				$types['typeid']=120120102;
				$types['title']="记录".$userName."用户-非速搜企业管理-未通过企业-进行检索-检索内容:".$name;
			}
			$where['statecode']='3';
			//执行写入缓存操作
			$this->logM->logAdd($types);
			$args['name']=$name;
			$count = $this->real_enterprise_auth->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->real_enterprise_auth ->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC') ->select();
			$len=count($data);
			for($i=0;$i<$len;$i++){
				$real[$i]=$this->user->where('uid='.$data[$i]['uid'])->field('tname,nickname')->find();
				$data[$i]['tname']=$real[$i]['tname'];
				$data[$i]['nickname']=$real[$i]['nickname'];
			}
			$this->assign('row',$show);
			$this->assign('datas',$data);
	        $this->display("Conmons/Frame");
		}
		/*
		* 执行后台企业禁用
		* 接收数据格式  
		{
			'id' => ID
		}
		* 返回数据格式 无
		*/
		public function real_del(){
			$id=I('id','');
			$type=I('type',1);
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120109;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$name=$this->real_enterprise_auth->where('id='.$id)->getField('name');
			$userName = session('logUserName');
			
			if($type==1){
				//执行启用状态
				$state=$this->real_enterprise_auth->where("id=".$id)->setField('state',1);
				$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$name."-启用企业";
			}else{
				//执行禁用状态
				$state=$this->real_enterprise_auth->where("id=".$id)->setField('state',2);
				$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$name."-禁用企业";
			}
			//执行写入缓存操作
			$this->logM->logAdd($types);
			$this->redirect("Real/real_checkList");
		}
		/*
		* 执行后台企业审核
		
		* 接收数据格式  
		{
			'id' => ID
		}
		* 返回数据格式 无
		*/
		public function success_audit(){
			$id=I('id','');
			$name=$this->real_enterprise_auth->where('id='.$id)->field('name,uid')->find();
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120110;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$name['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-待审核企业:".$name['name']."-审核-执行审核通过";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			$data['statecode']='1';
			$data['ktime']=time();
			
			$realId = $this -> real_enterprise_auth->where('id='.$id)->save($data);
			$is_do=$this->enterprise_user->where('cid='.$id.' AND groups=0 AND statecode=5 AND state=1')->count();
			if($is_do==0 && $realId){
				$uid=$this->real_enterprise_auth->where('id='.$id)->getField('uid');
				$list['uid']=$uid;
				$list['cid']=$id;
				$list['ctime']=time();
				$list['statecode']='5';
				$userId=$this->enterprise_user->add($list);
				if($userId){
					$real=$this->user->where('uid='.$uid)->setField('role',3);
					if($real){
						$map['eid']=$id;
						$map['total']='0';
						$map['ctime']=time();
						$map['stime']=time();
						$mapAdd=$this->exhibition_ledger_total->add($map);
						if($mapAdd){
							$rew['eid']=$id;
							$rew['contents']='15,';
							$contentsAdd=$this->bill_contents->add($rew);
							if($contentsAdd){
								//***********************************给用户发短信和邮件*********************************************
									//准备手机号码和邮箱
									$uphone=$this->user->where('uid='.$uid)->getField('uphone');
									$uemail=$this->user->where('uid='.$uid)->getField('uemail');
									//准备短信内容
									$content="【非速搜】尊敬的商家，恭喜贵司已通过非速搜展会网的商家认证。现登录非速搜“商家中心”发布展位，即可实现展位在线销售。在线订展-让展会更贴心。退订回N";
									//存在电话则发短信
									if(isset($uphone)){
										sendSms($uphone,$content);
									}
									//存在邮箱则发邮件
									if(isset($uemail)){
										//准备邮箱内容
										$emailHref=C('WEB')."/Newexhibition/insertarea";
										$emailHref2=C('WEB')."/Newexhibition/insertexposition_area";
										$emailContent=EmailTemplateP("尊敬的商家：".$uemail);
										$emailContent.=EmailTemplateP("您好，恭喜贵公司已通过非速搜展会网的商家认证。现登录非速搜“商家中心”发布展位，即可实现展位在线销售。在线订展-让展会更贴心。");
										$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,"【发布展会】"));
										$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref2,"【发布展位】"));
										$emailContent.=EmailTemplateSP("如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：");
										$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,$emailHref));
										$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref2,$emailHref2));
										
										$emailContents=EmailTemplate($emailContent);
										sendMail($uemail,'非速搜--认证通知',$emailContents);
									}
								//***********************************给用户发短信和邮件*********************************************
								$this->redirect('real/index');
							}else{
								$this->error('创建发票内容商户表失败！');
							}
						}else{
							$this->error('创建企业总金额表失败！');
						}				
					}else{
						$this->error("企业认证失败！");
					}
				}
			}else{
				$this->error("企业状态更改失败！");
			}
		}
		/**
		后台执行企业认证失败
		接收的值
		{
			'uid'=>用户uid
			'field1'=>错误字段一
		   'field2'=>错误字段二
		   'reason1' => 错误原因一
		   'reason2' => 错误原因二
		   'reason' => 其他错误原因
		}
		返回的值 无
		***/
		public function failure_audit(){
			$id=I('id','');
			$uid=I('uid','');
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120111;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$uid;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$name=$this->real_enterprise_auth->where('id='.$id)->getField('name');
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-待审核企业:".$name."-审核-执行审核失败";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			$reason=I('reason','');
			
			$isName=$this->real_enterprise_auth->where('id='.$id)->setField('statecode',3);
			if($isName){
				$data['id']=$id;
				$data['reason']=$reason;
				$data['ktime']=time();
				$this->real_enterprise_auth->create($data);
				$state=$this->real_enterprise_auth->save();
				if($state){
					//***********************************给用户发短信和邮件*********************************************
						//准备手机号码和邮箱
						$uphone=$this->user->where('uid='.$uid)->getField('uphone');
						$uemail=$this->user->where('uid='.$uid)->getField('uemail');
						//准备短信内容
						$content="【非速搜】尊敬的会员，很遗憾贵司提交的商家认证材料未通过审核。请登录非速搜展会网查看失败原因并重新提交。在线订展-让展会更贴心。";
						//存在电话则发短信
						if(isset($uphone)){
							sendSms($uphone,$content);
						}
						//存在邮箱则发邮件
						if(isset($uemail)){
							//准备邮箱内容
							$emailHref=C('WEB')."/Perbusiness/perBusiEdit";
							$emailContent=EmailTemplateP("尊敬的会员：".$uemail);
							$emailContent.=EmailTemplateP("您好，很遗憾贵公司提交的商家认证材料未通过审核。请登录非速搜展会网查看失败原因并重新提交。在线订展-让展会更贴心。");
							$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,"查看详情"));
							$emailContent.=EmailTemplateSP("如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：");
							$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,$emailHref));
							
							$emailContents=EmailTemplate($emailContent);
							sendMail($uemail,'非速搜--认证通知',$emailContents);
						}
					//***********************************给用户发短信和邮件*********************************************
					$this->redirect('Real/real_checkFalse');
				}else{
					$this->error('执行实名认证失败！');
				}
			}
		}
		/**
		 * 【展示页】企业认证详情页面
			接收值 
			{
				'id' => 展馆信息ID
			}
			返回值 
			{
				
			}
		 */
		public function real_detail(){
			$id=I('id','');
			
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/real_detail");
			$this->assign("Js","Real/real_detailjs");
			
			//查询企业认证表信息
			$data=$this->real_enterprise_auth->where('id='.$id)->find();
			
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$data['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$data['name']."-详情-查看详情页";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			//企业级别
			$data['levelName']=$this->enterprise_level->where('id='.$data['level'])->getField('name');
			//行业标签
			$tagsStr=rtrim($data['tags'],',');
			$tagsArr=explode(',',$tagsStr);
			$length=count($tagsArr);
			for($k=0;$k<$length;$k++){
				$list[$k]=$this->exhibition_trade->where('id='.$tagsArr[$k])->field('name')->find();
				$listTrade.=$list[$k]['name'].' , ';
			}
			$data['tagsNum']=$listTrade=rtrim($listTrade,' , ');
			//查询创建者
			$creator=$this->user->where('uid='.$data['uid'])->field('nickname,tname,uemail')->find();
			$data['nickname']=$creator['nickname'];
			$data['tname']=$creator['tname'];
			$data['uemail']=$creator['uemail'];
			//查询县/区
			$county=$this->citys->where('id='.$data['area'])->field('pid,name')->find();
			//查询市
			$city=$this->citys->where('id='.$county['pid'])->field('pid,name')->find();
			//查询省
			$province=$this->citys->where('id='.$city['pid'])->field('name')->find();
			//将企业认证表里的企业开通的服务进行拆分
			$etypestr=rtrim($data['etype'],',');
			$etypearr=explode(',',$etypestr);
			if($etypearr){
				$len=count($etypearr);
				for($i=0;$i<$len;$i++){
					if($etypearr[$i]==1){
						$subnum[$i]='展馆展会';
					}else if($etypearr[$i]==2){
						$subnum[$i]='酒店';
					}else if($etypearr[$i]==3){
						$subnum[$i]='签证';
					}
					$dataNum.=$subnum[$i].' / ';
				}
					$dataNum=rtrim($dataNum,' / ');
			}else{
				$subnum='无';
			}
			$data['areaName']=$province['name'].' '.$city['name'].' '.$county['name'];
			$data['stime']=date("Y年m月d日",$data['stime']);
			$data['etime']=date("Y年m月d日",$data['etime']);
			//企业入驻时间
			$data['itime']=$this->log_record->where("typeid='120110404' AND cido=".$data['uid']." OR typeid='100130102' AND cido=".$data['uid'])->getField('ctime');
			
			//企业审核时间和审核人
			$here['typeid']=array('IN','120120110,120120111');
			$here['cido']=$id;
			$info=$this->log_record->where($here)->field('ctime,luid')->order("ctime DESC")->find();
			$data['ktime']=$info['ctime'];
			$uid=$this->log_user->where('id='.$info['luid'])->getField('uid');
			$data['luid']=$this->user->where('uid='.$uid)->getField('tname');
			
			$this->assign('row',$row);
			$this->assign('data',$data);
			$this->assign('etype',$dataNum);
			$this->display("Conmons/Frame");
		}
	 /**
	 * 【展示页】企业认证审核页面
		接收值 
		{
			'id' => 展馆信息ID
		}
		返回值 
		{
			
		}
	 */
	 public function real_check(){
	  //加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
		//加载内容页模板
		$this->assign("Tel","Real/real_check");
		$this->assign("Js","Real/real_checkjs");
		
		$id=I('id','');
		//查询企业认证表信息
		$data=$this->real_enterprise_auth->where('id='.$id)->find();
		
		///实例化日志类
		$this->logM = new LogFile();
		//记录操作日志
		$types=null;
		$types['typefid']=1201201;
		$types['typeid']=120120113;
		$types['controller']=CONTROLLER_NAME;
		$types['method']=ACTION_NAME;
		$types['cido']=$id;
		$types['cidt']=$data['uid'];
		$types['source']=$_SERVER['HTTP_REFERER'];
		//获取当前操作的用户名
		$userName = session('logUserName');
		$types['title']="记录".$userName."用户-非速搜企业管理-待审核企业:".$data['name']."-审核-查看企业认证审核页";
		//执行写入缓存操作
		$this->logM->logAdd($types);
		
		//查询创建者
		$creator=$this->user->where('uid='.$data['uid'])->field('nickname,tname,uemail')->find();
		$data['nickname']=$creator['nickname'];
		$data['tname']=$creator['tname'];
		$data['uemail']=$creator['uemail'];
		//查询县/区
		$county=$this->citys->where('id='.$data['area'])->field('pid,name')->find();
		//查询市
		$city=$this->citys->where('id='.$county['pid'])->field('pid,name')->find();
		//查询省
		$province=$this->citys->where('id='.$city['pid'])->field('name')->find();
		//将企业认证表里的企业开通的服务进行拆分
		$etypestr=rtrim($data['etype'],',');
		$etypearr=explode(',',$etypestr);
		if($etypearr){
			$len=count($etypearr);
			for($i=0;$i<$len;$i++){
				// [$i]=$etypearr[$i];
				if($etypearr[$i]==1){
					$subnum[$i]='展馆展会';
				}else if($etypearr[$i]==2){
					$subnum[$i]='酒店';
				}else if($etypearr[$i]==3){
					$subnum[$i]='签证';
				}
				$dataNum.=$subnum[$i].' / ';
			}
				$dataNum=rtrim($dataNum,' / ');
		}else{
			$subnum='无';
		}
		
		//企业级别
		$data['levelName']=$this->enterprise_level->where('id='.$data['level'])->getField('name');
		//行业标签
		$tagsStr=rtrim($data['tags'],',');
		$tagsArr=explode(',',$tagsStr);
		$length=count($tagsArr);
		for($k=0;$k<$length;$k++){
			$list[$k]=$this->exhibition_trade->where('id='.$tagsArr[$k])->field('name')->find();
			$listTrade.=$list[$k]['name'].' , ';
		}
		$data['tagsNum']=$listTrade=rtrim($listTrade,' , ');
			
		$data['areaName']=$province['name'].' '.$city['name'].' '.$county['name'];
		$data['stime']=date("Y年m月d日",$data['stime']);
		$data['etime']=date("Y年m月d日",$data['etime']);
		// dump($dataNum);exit;
		
		$this->assign('data',$data);
		$this->assign('etype',$dataNum);
		$this->display("Conmons/Frame");
	 }
	  /**
	 * 【展示页】企业用户组页
		接收值 
		{
			'id'=>企业Id
		}
		返回值 
		{
			
		}
	 */
	 public function  jurisdictionUser(){
		 //加载左侧导航菜单缓存
		//缓存初始化
		$cache = S(array());
		$this->assign("LeftNavInfo",session('LeftNav'));
		//加载内容页模板
		$this->assign("Tel","Real/jurisdictionUser");
		$this->assign("Js","Real/jurisdictionUserjs");
		
		$id=I('id','');
		//企业
		$real=$this->real_enterprise_auth->getFieldById($id,'name');
		
		///实例化日志类
		$this->logM = new LogFile();
		//记录操作日志
		$types=null;
		$types['typefid']=1201201;
		$types['typeid']=120120114;
		$types['controller']=CONTROLLER_NAME;
		$types['method']=ACTION_NAME;
		$types['cido']=$id;
		$types['cidt']=$this->real_enterprise_auth->getFieldById($id,'uid');
		$types['source']=$_SERVER['HTTP_REFERER'];
		//获取当前操作的用户名
		$userName = session('logUserName');
		$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$real."-权限-查看企业用户组页";
		//执行写入缓存操作
		$this->logM->logAdd($types);
		
		$count = $this->enterprise_user_group->where('cid='.$id)->count();// 查询满足要求的总记录数
		$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		$data = $this ->enterprise_user_group ->where('cid='.$id)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC') ->select();
		//权限
		$len=count($data);
		for($i=0;$i<$len;$i++){
			$numIdStr[$i]=rtrim($data[$i]['gid'],',');
			$numIdArr=explode(',',$numIdStr[$i]);
			$lengthId=count($numIdArr);
			for($k=0;$k<$lengthId;$k++){
				$map[$k]=$this->enterprise_auth_rule->where('id='.$numIdArr[$k])->getField('name');
				$list[$i].=$map[$k].'&nbsp;&nbsp;&nbsp;&nbsp;';
				$listName=rtrim($list[$i],'&nbsp;&nbsp;&nbsp;&nbsp;');
			}
			$data[$i]['listName']=$listName;
		}
		
		$this->assign('id',$id);
		$this->assign('row',$show);
		$this->assign('realName',$real);
		$this->assign('data',$data);
		$this->display("Conmons/Frame");
	 }
	 /*
	* 执行后台企业用户禁用
	* 接收数据格式  
	{
		'id' => ID
	}
	* 返回数据格式 无
	*/
	public function enterprise_del(){
		$id=I('id','');
		$pid=I('pid','');
		$type=I('type',1);
		//获取企业用户页码
		$p=I('p','');
		///实例化日志类
		$this->logM = new LogFile();
		//记录操作日志
		$types=null;
		$types['typefid']=1201201;
		$types['typeid']=120120116;
		$types['controller']=CONTROLLER_NAME;
		$types['method']=ACTION_NAME;
		$types['cido']=$pid;
		$types['cidt']=$id;
		$types['source']=$_SERVER['HTTP_REFERER'];
		//获取当前操作的用户名
		$userName = session('logUserName');
		$name=$this->real_enterprise_auth->where('id='.$pid)->getField('name');
		$uid=$this->enterprise_user->where('id='.$id)->getField('uid');
		$uidName=$this->real_name_auth->where('uid='.$uid)->getField('name');
		
		if($type==1){
			//执行启用状态
			$state=$this->enterprise_user->where("id=".$id)->setField('state',1);
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$name."-企业用户-执行启用子用户".$uidName;
		}else{
			//执行禁用状态
			$state=$this->enterprise_user->where("id=".$id)->setField('state',2);
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$name."-企业用户-执行禁用子用户".$uidName;
		}
		//执行写入缓存操作
		$this->logM->logAdd($types);
		$this->redirect("Real/enterpriseUser",array('id'=>$pid,'p'=>$p));
	}
	/*执行修改超级密码
	*接收的值 'id' => 企业ID
	*返回的值 status=1 密码修改成功 2密码修改失败
	**/
	public function realPass(){
		$id=I('id','');
		///实例化日志类
		$this->logM = new LogFile();
		//记录操作日志
		$types=null;
		$types['typefid']=1201201;
		$types['typeid']=120120117;
		$types['controller']=CONTROLLER_NAME;
		$types['method']=ACTION_NAME;
		$types['cido']=$id;
		$types['source']=$_SERVER['HTTP_REFERER'];
		//获取当前操作的用户名
		$userName = session('logUserName');
		$realName=$this->real_enterprise_auth->where('id='.$id)->getField('name');
		$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$realName."-超级密码-执行修改超级密码";
		//执行写入缓存操作
		$this->logM->logAdd($types);
		
		$uid=$this->real_enterprise_auth->where('id='.$id)->getField('uid');
		$user=$this->user->where('uid='.$uid)->field('uphone,uemail')->find();
		//真实姓名
		$userName=$this->real_name_auth->where('uid='.$uid)->getField('name');
		//随机产生十位密码
		function passNum($upass){
			$randNum=rand_num(10,1);
			$pass=md5($randNum);
			$read=$upass->where('upass='.$pass)->find();
			if($read){
				passNum($upass);
			}else{
				return $randNum;
			}
		}
		//新密码
		$randNum=passNum($this->real_enterprise_auth);
		$newPass=md5($randNum);
		//时间
		$time=time();
		$nowTime=date('Y-m-d H:i:s',$time);
		//内容
		$content="【非速搜】尊敬的商家".$userName."，您于".$nowTime."使用了找回超级密码功能，您的新密码是：".$randNum."，请慎重保管！如有疑问，请联系400-66887-33转0。";
		//判断是否有手机号码
		// $to="18810418955";
		if($user['uphone']!=NULL){
			//保存
			$send=$this->real_enterprise_auth->where('id='.$id)->setField('superpass',$newPass);
			if($send){
				//发送短信
				sendSms($user['uphone'],$content);
				$output= array(
					'status' 	=>'1',
					'message'	=>'密码修改成功！'
				);
			}else{
				$output= array(
					'status' 	=>'2',
					'message'	=>'密码修改失败！'
				);
			}
		}
		echo json_encode($output);
	}
	/**
		 * 【展示页】企业子用户页面
			接收值 
			{
				'id' => 企业ID
			}
			返回值 
			{
				
			}
		 */
		public function real_childUser(){
			$id=I('id','');
			
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/real_childUser");
			$this->assign("Js","Real/real_detailjs");
			
			//查询企业认证表信息
			$data=$this->real_enterprise_auth->where('id='.$id)->field('uid,name')->find();
			
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$data['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$data['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			
			//查询企业子用户
			$map = $this ->enterprise_user ->where('cid='.$id.' AND state=1')->select();
			$leng=count($map);
			for($i=0;$i<$leng;$i++){
				//注册时间
				$map[$i]['rtime']=$this->user->where('uid='.$map[$i]['uid'])->getField('ctime');
				//用户
				$map[$i]['userName']=$this->real_name_auth->where('uid='.$map[$i]['uid'])->getField('name');
				//入驻时间
				$map[$i]['ctime']=date('Y-m-d H:i:s',$map[$i]['ctime']);
			}
			$row=$map;
			unset($row[0]);
			
			$this->assign('map',$map[0]);
			$this->assign('row',$row);
			$this->assign('id',$id);
			$this->assign('rew',$data['name']);
			$this->display("Conmons/Frame");
		}
		/**
		 * 【展示页】企业订单页面
			接收值 
			{
				'id' => 企业ID
			}
			返回值 
			{
				'id' => 订单ID
				'sign' => 订单编码
				'trade_no' => 支付流水号
				'gid' => 展位ID
				'uid' => 操作用户ID
				'sid' => 商家ID
				'price' => 价格
				'paytype' => 支付类型（1线下支付 2网银在线 3支付宝 4微信）
				'paytime' => 支付时间
				'goods' => 展位编号
				'userName' => 商家名称
			}
		 */
		public function real_order(){
			$id=I('id','');
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/real_order");
			$this->assign("Js","Real/real_detailjs");
			
			//查询企业认证表信息
			$map=$this->real_enterprise_auth->where('id='.$id)->field('uid,name')->find();
			
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$map['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$map['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			$name=I('name','');
			if($name){
				$where['sign'] = array('LIKE','%'.$name.'%');
				$args['id']=$id;
				$args['name']=$name;
			}
			$where['eid']=$id;
			$where['state']=1;
			$count = $this->order->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->order->where($where)->field('id,sign,trade_no,gid,uid,sid,price,paytype,paytime')->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			$length=count($data);
			for($i=0;$i<$length;$i++){
				//展位编号
				$data[$i]['goods']=$this->exhibition_position->where('id='.$data[$i]['gid'])->getField('sign');
				//商家
				$data[$i]['userName']=$this->real_name_auth->where('uid='.$data[$i]['sid'])->getField('name');
				if(!$data[$i]['userName']){
					$data[$i]['userName']=$this->user->where('uid='.$data[$i]['sid'])->getField('nickname');
				}
			}
			$this->assign('map',$data);
			$this->assign('rew',$map['name']);
			$this->assign('row',$show);
			$this->assign('id',$id);
			$this->display("Conmons/Frame");
		}
		/**
		 * 【展示页】企业合同页面
			接收值 
			{
				'id' => 企业ID
			}
			返回值 
			{
				'id' => 企业合同ID
				'sign' => 合同编号
				'oid' =>  订单ID
				'osign' => 订单编号
				'uid' => 买家用户ID
				'euid' => 卖家ID
				'ctime' => 创建时间
				'statecode' => 状态码 1已生效 2已废除
				'buyer' => 买家名称
				'seller' => 卖家名称
			}
		 */
		public function real_contract(){
			$id=I('id','');
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/real_contract");
			$this->assign("Js","Real/real_detailjs");
			
			//查询企业认证表信息
			$map=$this->real_enterprise_auth->where('id='.$id)->field('uid,name')->find();
			
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$map['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$map['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			$name=I('name','');
			if($name){
				$where['_string'] = ' (sign like "%'.$name.'%")  OR ( osign like "%'.$name.'%") ';
				$args['id']=$id;
				$args['name']=$name;
			}
			$where['eid']=$id;
			$where['state']=1;
			$count = $this->contract_order->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->contract_order->where($where)->field('id,sign,oid,osign,uid,euid,ctime,statecode')->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			$length=count($data);
			for($i=0;$i<$length;$i++){
				//买家用户
				$data[$i]['buyer']=$this->user->where('uid='.$data[$i]['uid'])->getField('nickname');
				//卖家用户
				$data[$i]['seller']=$this->user->where('uid='.$data[$i]['euid'])->getField('nickname');
			}
			$this->assign('data',$data);
			$this->assign('rew',$map['name']);
			$this->assign('row',$show);
			$this->assign('id',$id);
			$this->display("Conmons/Frame");
		}
		/**
		 * 【展示页】企业合同条款页面
			接收值 
			{
				'id' => 企业ID
			}
			返回值 
			{
				'id' => ID
				'uid' => 商家UID
				'type' => 所属类型 1展会
				'content' => 条款内容
				'ctime' => 创建时间
				'statecode' => 状态码 1已启用 2已停用
				'title' => 备注
				'buyer' => 商家名称
			}
		 */
		public function real_clause(){
			$id=I('id','');
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/real_clause");
			$this->assign("Js","Real/real_detailjs");
			
			//查询企业认证表信息
			$map=$this->real_enterprise_auth->where('id='.$id)->field('uid,name')->find();
			
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$map['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$map['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			
			$count = $this->contract_enterprise_templet->where('eid='.$id.' AND state=1')->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->contract_enterprise_templet->where('eid='.$id.' AND state=1')->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			$length=count($data);
			for($i=0;$i<$length;$i++){
				//商家用户
				$data[$i]['buyer']=$this->user->where('uid='.$data[$i]['uid'])->getField('nickname');
			}
			$this->assign('data',$data);
			$this->assign('rew',$map['name']);
			$this->assign('row',$show);
			$this->assign('id',$id);
			$this->display("Conmons/Frame");
		}
		/**
		 * 【展示页】企业发布的展馆历史页面
			接收值 
			{
				'id' => 企业ID
			}
			返回值 
			{
				'id' => 展馆信息ID
				'pid' => 展馆ID
				'author' => 作者UID
				'ctime' => 创建时间
				'statecode' => 状态码 1认证成功 2审核中 3审核失败 4待认领
				'state' => 状态 1正常 2禁用
				'sign' => 展馆编号
				'authors' => 作者名称
			}
		 */
		public function real_venue(){
			$id=I('id','');
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/real_venue");
			$this->assign("Js","Real/real_detailjs");
			
			//查询企业认证表信息
			$map=$this->real_enterprise_auth->where('id='.$id)->field('uid,name')->find();
			
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$map['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$map['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			
			$uid=$this->enterprise_user->where('cid='.$id)->field('uid')->select();
			$uidCount=count($uid);
			for($k=0;$k<$uidCount;$k++){
				$uids.=$uid[$k]['uid'].',';
			}
			$where['author']=array('IN',$uids);
			$count = $this->exhibition_venue_info->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->exhibition_venue_info->where($where)->field('id,pid,author,ctime,statecode,state')->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			$length=count($data);
			for($i=0;$i<$length;$i++){
				//展馆编号
				$data[$i]['sign']=$this->exhibition_venue->where('id='.$data[$i]['pid'])->getField('sign');
				//展馆名称
				$data[$i]['name']=$this->exhibition_venue->where('id='.$data[$i]['pid'])->getField('name');
				//作者
				$data[$i]['authors']=$this->real_name_auth->where('uid='.$data[$i]['author'])->getField('name');
				if(!$data[$i]['authors']){
					$data[$i]['authors']=$this->user->where('uid='.$data[$i]['author'])->getField('nickname');
				}
			}
			$this->assign('data',$data);
			$this->assign('rew',$map['name']);
			$this->assign('row',$show);
			$this->assign('id',$id);
			$this->display("Conmons/Frame");
		}
		/**
		 * 【展示页】企业发布的展会历史页面
			接收值 
			{
				'id' => 企业ID
			}
			返回值 
			{
				'id' => 展会信息ID
				'cid' => 展馆ID
				'pid' => 展会ID
				'author' => 作者UID
				'ctime' => 更新时间
				'statecode' => 状态码 1审核通过 2审核中 3审核失败
				'state' => 状态 1正常 2禁用
				'sign' => 展会编号
				'name' => 展会名称
				'nameVenue' => 展会展馆
				'authors' => 作者
			}
		 */
		public function real_exhibition(){
			$id=I('id','');
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/real_exhibition");
			$this->assign("Js","Real/real_detailjs");
			
			//查询企业认证表信息
			$map=$this->real_enterprise_auth->where('id='.$id)->field('uid,name')->find();
			
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$map['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$map['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			
			$uid=$this->enterprise_user->where('cid='.$id)->field('uid')->select();
			$uidCount=count($uid);
			for($k=0;$k<$uidCount;$k++){
				$uids.=$uid[$k]['uid'].',';
			}
			$where['author']=array('IN',$uids);
			$count = $this->exhibition_info->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->exhibition_info->where($where)->field('id,cid,pid,author,ctime,statecode,state')->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			$length=count($data);
			for($i=0;$i<$length;$i++){
				//展会编号
				$data[$i]['sign']=$this->exhibition->where('id='.$data[$i]['pid'])->getField('sign');
				//展会名称
				$data[$i]['name']=$this->exhibition->where('id='.$data[$i]['pid'])->getField('name');
				//展馆名称
				$data[$i]['nameVenue']=$this->exhibition_venue->where('id='.$data[$i]['cid'])->getField('name');
				//作者
				$data[$i]['authors']=$this->real_name_auth->where('uid='.$data[$i]['author'])->getField('name');
				if(!$data[$i]['authors']){
					$data[$i]['authors']=$this->user->where('uid='.$data[$i]['author'])->getField('nickname');
				}
			}
			$this->assign('data',$data);
			$this->assign('rew',$map['name']);
			$this->assign('row',$show);
			$this->assign('id',$id);
			$this->display("Conmons/Frame");
		}
		/**
		 * 【展示页】企业发布的展位页面
			接收值 
			{
				'id' => 企业ID
			}
			返回值 
			{
				'id' => ID
				'sid' => 商家UID
				'hid' => 展馆ID
				'eid' => 展会ID
				'number' => 商家展位号
				'sign' => 展位编号
				'salestate' => 销售状态 1未预定 2已预订 3已购买 4已线下销售 5已过期
				'statecode' => 状态码 1审核通过 2审核中 3审核失败 4已绑定
				'commission' => 佣金
				'name' => 展会名称
				'nameVenue' => 展馆名称
				'authors' => 商家
			}
		 */
		public function real_position(){
			$id=I('id','');
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/real_position");
			$this->assign("Js","Real/real_detailjs");
			
			//查询企业认证表信息
			$map=$this->real_enterprise_auth->where('id='.$id)->field('uid,name')->find();
			
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$map['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$map['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			$name=I('name','');
			if($name){
				$where['sign'] = array('LIKE','%'.$name.'%');
				$args['id']=$id;
				$args['name']=$name;
			}
			$where['bid']=$id;
			$where['state']=1;
			$count = $this->exhibition_position->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->exhibition_position->where($where)->field('id,sid,hid,eid,sign,number,salestate,statecode,commission')->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			$length=count($data);
			for($i=0;$i<$length;$i++){
				//展会名称
				$data[$i]['name']=$this->exhibition->where('id='.$data[$i]['eid'])->getField('name');
				//展馆名称
				$data[$i]['nameVenue']=$this->exhibition_venue->where('id='.$data[$i]['hid'])->getField('name');
				//商家
				$data[$i]['authors']=$this->real_name_auth->where('uid='.$data[$i]['sid'])->getField('name');
				if(!$data[$i]['authors']){
					$data[$i]['authors']=$this->user->where('uid='.$data[$i]['sid'])->getField('nickname');
				}
			}
			$this->assign('data',$data);
			$this->assign('rew',$map['name']);
			$this->assign('row',$show);
			$this->assign('id',$id);
			$this->display("Conmons/Frame");
		}
		/**
		 * 【展示页】企业信息补充页
			接收值 
			{
				'id' => 企业ID
			}
			返回值 
			{
				'name' => 企业名称
				'people' => 企业法人
				'area' => 区域ID
				'coord' => 企业坐标
				'address' => 企业地址
				'stime' => 企业执照开始时间
				'etime' => 企业执照结束时间
				'tags' => 企业标签ID组
				'tagss' => 企业标签
			}
		*/
		public function realInfoAdd(){
			$id=I('id','');
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->assign("Tel","Real/realInfoAdd");
			$this->assign("Js","Real/realInfoAddjs");
			//获取页码
			$p=I('p','');
			//获取检索内容
			$jname=I('jname','');
			$sty=I('sty','');
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$map['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$map['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			$data=$this->real_enterprise_auth->where("id=".$id)->field("name,people,area,coord,address,stime,etime,tags")->find();
			//市
			$data['cityId']=$this->citys->where("id=".$data['area'])->getField('pid');
			//省
			$data['provinceId']=$this->citys->where("id=".$data['cityId'])->getField('pid');
			//企业标签
			$tagsStr=trim($data['tags'],',');
			$tagsArr=explode(',',$tagsStr);
			$tagsLen=count($tagsArr);
			for($i=0;$i<$tagsLen;$i++){
				$tags[$i]=$this->exhibition_trade->where('id='.$tagsArr[$i])->getField('name');
				$data['tagss'].=$tags[$i].',';
			}
			$data['tagss']=rtrim($data['tagss'],',');
			//时间
			if($data['stime']){
				$data['stime']=date("Y-m-d",$data['stime']);
			}else{
				$data['stime']='';
			}
			if($data['etime']){
				$data['etime']=date("Y-m-d",$data['etime']);
			}else{
				$data['etime']='';
			}
			//行业标签
			$list=$this->exhibition_trade->where('state=1')->select();
			$this->assign('data',$data);
			$this->assign('list',$list);
			$this->assign('id',$id);
			$this->assign('p',$p);
			$this->assign('jname',$jname);
			$this->assign('sty',$sty);
			$this->display("Conmons/Frame");
		}
		/*
		* 执行企业信息补充
		* 接收数据格式  
		{
			'id' => ID
			'name' => 企业名称
			'people' => 企业法人
			'area' => 区域ID
			'coord' => 企业坐标
			'address' => 企业地址
			'stime' => 企业执照开始时间
			'etime' => 企业执照结束时间
			'tags' => 企业标签ID组
			'tagss' => 企业标签
		}
		* 返回数据格式 无
		*/
		public function realInfoInsert(){
			$id=I('id','');
			///实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1201201;
			$types['typeid']=120120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['cidt']=$map['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜企业管理-已通过企业:".$map['name']."-详情-查看详情页";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			
			$people=I('people','');
			$area=I('area','');
			$coord=I('coord','');
			$address=I('address','');
			$stime=I('stime','');
			$stime=strtotime($stime);
			$etime=I('etime','');
			$etime=strtotime($etime);
			$tags=I('tags','');
			$isValid=I('isValid','');
			
			$data['people']=$people;
			$data['area']=$area;
			$data['coord']=$coord;
			$data['address']=$address;
			$data['tags']=$tags;
			if($stime){
				$data['stime']=$stime;
			}else{
				$data['stime']='';
			}
			if($etime){
				$data['etime']=$etime;
			}else{
				$data['etime']='';
			}
			
			$save=$this->real_enterprise_auth->where('id='.$id)->save($data);
			if($save){
				$this->redirect("Real/index",array('p'=>$p,'name'=>$jname));
			}else{
				$this->error("执行补充失败");
			}
		}
}

?>