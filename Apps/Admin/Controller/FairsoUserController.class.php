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
	class FairsoUserController extends LimitController{
		private $user;//用户表
		private $citys;//城市表
		private $real_name_auth;//真实姓名认证表
		private $real_enterprise_auth;//真实姓名认证表
		private $log_user;//日志用户表
		private $feedback_manage;//反馈管理表
		/**
		* 构造方法
		*/
		public function __construct() {
			parent::__construct();
			//实例化展会场馆表
			$this->citys     = D('citys');
			$this->user  = D('user');
			$this->real_name_auth     = D('real_name_auth');
			$this->real_enterprise_auth     = D('real_enterprise_auth');
			$this->feedback_manage     = D('feedback_manage');
			
			$this->log_user  = D('log_user');
			//实例化日志类
			$this->logM = new LogFile();
			//实例化公共类
			$this->common = new CommonFile();
		}
		//加载非速搜游客用户信息显示页面
		public function index(){
			//实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120101;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = $_SESSION['user']['tname'];
			$types['title']="记录".$userName."用户-非速搜用户管理-非速搜用户-查看列表";
			
			$name=I('name','');
			$member=I('member','');
			
			if($name){
				$where['_string'] = ' ( uphone like "%'.$name.'%") OR ( uemail like "%'.$name.'%")';
				$types['typeid']=100120105;
				$types['title']="记录".$userName."用户-非速搜用户管理-非速搜用户-检索操作-检索内容:".$name;
				$args['name']=$name;
			}
			if($member==1){
				$where['role']=array('NEQ','4');
				$types['typeid']=100120104;
				$types['title']="记录".$userName."用户-非速搜用户管理-非速搜用户-全部用户筛选";
				$args['member']=$member;
			}else if($member==2){
				$where['role']=2;
				$types['typeid']=100120102;
				$types['title']="记录".$userName."用户在非速搜用户管理-非速搜用户-普通会员筛选";
				$args['member']=$member;
			}else if($member==3){
				$where['role']= 3;
				$types['typeid']=100120103;
				$types['title']="记录".$userName."用户在非速搜用户管理-非速搜用户-商家用户筛选";
				$args['member']=$member;
			}else if($member==4){
				$where['isname']= 1;
				$where['role'] =array('NEQ',4);
				// $types['typeid']=100120103;
				// $types['title']="记录".$userName."用户在非速搜用户管理-非速搜用户-商家用户筛选";
				$args['member']=$member;
			}else if($member==5){
				$where['isname']= 2;
				$where['role'] =array('NEQ',4);
				// $types['typeid']=100120103;
				// $types['title']="记录".$userName."用户在非速搜用户管理-非速搜用户-商家用户筛选";
				$args['member']=$member;
			}else if(!$name && !$member){
				$where['role'] =array('NEQ',4);
				$where['isname'] =array('NEQ',3);
				$where['state'] =1;
			}
			
			$count = $this->user->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$list = $this ->user->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			
			$this->assign('row',$show);
			$this->assign('list',$list);
			$this->assign('member',$member);
	        //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","FairsoUser/index");
	        $this->assign("Js","FairsoUser/indexjs");
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
	        $this->display("Conmons/Frame");
		}
		//加载非速搜游客用户信息显示页面
		public function forbidIndex(){
			//实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120116;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = $_SESSION['user']['tname'];
			$types['title']="记录".$userName."用户-非速搜用户管理-被禁用用户-查看列表";
			
			$name=I('name','');
			$memberAll=I('memberAll','');
			$member=I('member','');
			$business=I('business','');
			
			if($name){
				$where['_string'] = ' ( uphone like "%'.$name.'%") OR ( uemail like "%'.$name.'%")';
				$types['typeid']=100120117;
				$types['title']="记录".$userName."用户-非速搜用户管理-被禁用用户-检索操作-检索内容:".$name;
				$args['uphone']=$name;
				$args['uemail']=$name;
			}else if($memberAll){
				$where['role']!= '4';
				$types['typeid']=100120118;
				$types['title']="记录".$userName."用户-非速搜用户管理-被禁用用户-全部用户筛选";
				$args['role']!='4';
			}else if($member){
				$where['role']= $member;
				$types['typeid']=100120119;
				$types['title']="记录".$userName."用户在非速搜用户管理-被禁用用户-普通会员筛选";
				$args['role']=$member;
			}else if($business){
				$where['role']= $business;
				$types['typeid']=100120120;
				$types['title']="记录".$userName."用户在非速搜用户管理-被禁用用户-商家用户筛选";
				$args['role']=$business;
			}
			$where['role'] =array('NEQ',4);
			$where['isname'] =array('NEQ',3);
			$where['state'] =2;
			
			$count = $this->user->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$list = $this ->user->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			
			$this->assign('row',$show);
			$this->assign('list',$list);
	        //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","FairsoUser/forbidIndex");
	        $this->assign("Js","FairsoUser/indexjs");
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
	        $this->display("Conmons/Frame");
		}
		//加载非速搜游客用户审核列表显示页面
		public function userCheckList(){
	        //实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120106;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = $_SESSION['user']['tname'];
			$types['title']="记录".$userName."用户-非速搜用户管理-用户审核-查看列表";
			//获取检索内容
			$name=I('name','');
			//获取页码
			$p=I('p','');
			if($name){
				$where['_string'] = ' ( uphone like "%'.$name.'%") OR ( uemail like "%'.$name.'%")';
				$types['typeid']=100120107;
				$types['title']="记录".$userName."用户-非速搜用户管理-用户审核-进行检索-检索内容:".$name;
			}
			$where['role'] =array('NEQ',4);
			$where['isname'] =array('EQ',3);
			$where['state'] =1;
			
			$args['uphone']=$name;
			$args['uemail']=$name;
			$count = $this->user->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10,$args);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$list = $this ->user->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			
			$this->assign('row',$show);
			$this->assign('list',$list);
			$this->assign('p',$p);
			$this->assign('jname',$name);
	        //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","FairsoUser/userCheckList");
	        $this->assign("Js","FairsoUser/userCheckListjs");
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
	        $this->display("Conmons/Frame");
		}
		//加载非速搜游客用户信息编辑页面
		public function edit($id){
			$data = $this->user->where('uid='.$id)->find();
			$this->assign('vo',$data);
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","FairsoUser/edit");
	        $this->assign("Js","FairsoUser/indexjs");
			
			//实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120108;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜用户管理-非速搜用户-查看".$data["nickname"]."编辑页面";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			$this->display("Conmons/Frame");
		}
		//执行修改用户信息
		public function update(){
			$uid = I("uid",'');
			$uname = I("uname",'');
			$tname = I("tname",'');
			$upassOne = I("upassOne",'');
			$upassTwo = I("upassTwo",'');
			$sex = I("sex",'');
			//判断用户名是否存在
			if($uname){
				$userUname=$this->user->where("uname= '".$uname."' AND state=1 AND uid!=".$uid)->count();
				if($userUname){
					$this->error("用户名已存在！");
				}else{
					$data['uname']=$uname;
				}
			}else{
				$this->error("用户名不能为空！");
			}
			//判断手机号码是否存在
			// if($uphone){
				// $userUphone=$this->user->where("uphone= '".$uphone."' AND state=1 AND uid!=".$uid)->count();
				// if($userUphone){
					// $this->error("该手机号码已存在！");
				// }else{
					// $data['uphone']=$uphone;
				// }
			// }else{
				// $this->error("手机号码不能为空！");
			// }
			//判断邮箱是否存在
			// if($uemail){
				// $userUemail=$this->user->where("uemail= '".$uemail."' AND state=1 AND uid!=".$uid)->count();
				// if($userUemail){
					// $this->error("该邮箱已存在！");
				// }else{
					// $data['uemail']=$uemail;
				// }
			// }else{
				// $this->error("邮箱不能为空！");
			// }
			//判断真实姓名是否存在
			if(!$tname){
				$this->error("真实姓名不能为空！");
			}else{
				$data['tname']=$tname;
			}
			if($upassOne && $upassTwo){
				//判断两次输入的密码是否一致
				if($upassOne == $upassTwo){
					$data['upass'] = MD5($upassOne);
				}else{
					$this->error("两次密码输入不一致！");
				}
			}else{
				$this->error("密码不能为空！");
			}
			$data["sex"] = $sex;
			
	        //实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120109;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$data['uid'];
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜用户管理-非速搜用户-修改".$data["nickname"]."-执行修改信息";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			if($this->user->where('uid='.$uid)->save($data)){
				$this->redirect("Role/showUser");
			}else{
				$this->error("修改失败!");
			}
		}
		
		//执行用户添加
		public function insert(){
			$uname = I("uname",'');
			$tname=I('tname','');
			$upassOne = I("upassOne");
			$upassTwo = I("upassTwo");
			//判断用户名是否存在
			if($uname){
				$userUname=$this->user->where("uname= '".$uname."' AND state=1")->count();
				if($userUname){
					$this->error("用户名已存在！");
				}else{
					$data['uname']=$uname;
				}
			}else{
				$this->error("用户名不能为空！");
			}
			//判断真实姓名是否存在
			if($tname){
				$data['tname']=$tname;
			}else{
				$this->error("真实姓名不能为空！");
			}
			//判断两次输入的密码是否一致
			if($upassOne == $upassTwo){
				$data["upass"] = MD5($upassOne);
			}else{
				$this->error("两次密码输入不一致！");
			}
			
			//判断是手机注册(优先考虑)
			// if($uphone){
				// $userUphone=$this->user->where('uphone='.$uphone." AND state=1")->count();
				// if($userUphone){
					// $this->error("该手机号码已存在！");
				// }else{
					//获取手机号
					// $data["uphone"] = $uphone;
					//确定是手机号注册
					// $data["ctype"] = 1;
					//手机认证
					// $data["isphone"] = 1;
				// }
				// if($uemail){
					// $userUemail=$this->user->where("uemail= '".$uemail."' AND state=1")->count();
					// if($userUemail){
						// $this->error("该邮箱已存在！");
					// }else{
						//获取邮箱号
						// $data["uemail"] = $uemail;
						//邮箱认证
						// $data["isemail"] = 1;
					// }
				// }else{
					// 邮箱未认证
					// $data["isemail"] = 2;
				// }
			// }else{
				//判断是邮箱注册
				// if($uemail){
					// $userUemail=$this->user->where("uemail= '".$uemail."' AND state=1")->count();
					// if($userUemail){
						// $this->error("该邮箱已存在！");
					// }else{
						//获取邮箱号
						// $data["uemail"] = $uemail;
						//确定是邮箱注册
						// $data["ctype"] = 2;
						//邮箱认证
						// $data["isemail"] = 1;
						//手机未认证
						// $data["isphone"] = 2;
					// }
				// }
			// }
			$data["headimg"] = "/Public/huiyuan.jpg";
			$data["sex"] = I("sex");
			//未实名认证(默认未实名认证)
			$data["isname"] = 2;
			//默认是游客
			$data["role"] = 4;
			//用户创建时间
			$data["ctime"] = time();
		
			//执行保存
			$add=$this->user->add($data);
			if($add){
				//实例化日志类
				$this->logM = new LogFile();
				//记录操作日志
				$types=null;
				$types['typefid']=1001201;
				$types['typeid']=100120110;
				$types['controller']=CONTROLLER_NAME;
				$types['method']=ACTION_NAME;
				$types['cido']=$add;
				$types['source']=$_SERVER['HTTP_REFERER'];
				//获取当前操作的用户名
				$userName = session('logUserName');
				$types['title']="记录".$userName."用户-非速搜用户管理-非速搜用户-添加管理员-执行添加".$data["nickname"];
				//执行写入缓存操作
				$this->logM->logAdd($types);
				$this->redirect("Role/showUser");
			}else{
				$this->error("添加管理员失败!");
			}
		}
		
		//执行用户禁用操作
		public function del($id){
			//获取被删除用户信息
			$delUname = $this->user->where("uid=".$id)->getField("nickname");
	        //实例化日志类
			$this->logM = new LogFile();
			//记录操作日志
			$types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120111;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$type=I('type','');
			if($type==1){
				//执行启用状态
				$state=$this->user->where("uid=".$id)->setField('state',1);
				$types['title']="记录".$userName."用户-非速搜用户管理-被禁用用户-启用".$name;
				//执行写入缓存操作
				$this->logM->logAdd($types);
				$this->redirect("FairsoUser/index");
			}else{
				//执行禁用状态
				$state=$this->user->where("uid=".$id)->setField('state',2);
				$types['title']="记录".$userName."用户-非速搜用户管理-非速搜用户-禁用".$name;
				//执行写入缓存操作
				$this->logM->logAdd($types);
				$this->redirect("FairsoUser/forbidIndex");
			}
		}
		
		//执行用户管理操作的日志更新
		public function doLogUpdate(){
	        //实例化日志类
	        $this->logM = new LogFile();
			$this->logM->redisToMysql();
		}
		
		/**
		后台执行实名认证成功
		接收的值
		{
			'uid'=>用户uid
		}
		返回的值 无
		***/
		public function userApproveTrue(){
			$uid=I('uid','');
			//实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120112;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$uid;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$nickname=$this->user->where('uid='.$uid)->getField('nickname');
			$types['title']="记录".$userName."用户-非速搜用户管理-用户审核-审核".$nickname."-执行审核通过";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			// 将user表isname改成1
			$name=$this->real_name_auth->where('uid='.$uid)->getField('name');
			$map['tname']=$name;
			$map['isname']=1;
			$isName=$this->user->where('uid='.$uid)->save($map);
			if($isName){
				$data['statecode']='1';
				$data['ktime']=time();
				$state=$this->real_name_auth->where('uid='.$uid)->setField($data);
				if($state){
					//***********************************给用户发短信和邮件*********************************************
						//准备手机号码和邮箱
						$uphone=$this->user->where('uid='.$uid)->getField('uphone');
						$uemail=$this->user->where('uid='.$uid)->getField('uemail');
						//准备短信内容
						$content="【非速搜】尊敬的会员，恭喜您已通过非速搜展会网的实名认证。在线订展现已开通，预定展会从此轻松便捷。在线订展-让展会更贴心。退订回N";
						//存在电话则发短信
						if(isset($uphone)){
							sendSms($uphone,$content);
						}
						//存在邮箱则发邮件
						if(isset($uemail)){
							//准备邮箱内容
							$emailHref=C('WEB')."/Perbusiness/index";
							$emailContent=EmailTemplateP("尊敬的会员：".$uemail);
							$emailContent.=EmailTemplateP("您好，恭喜您已通过非速搜展会网的实名认证，在线订展现已开通，预定展会从此轻松便捷。在线订展—让展会更贴心。");
							$emailContent.=EmailTemplateP("<a href='".C('WEB')."/Perbusiness/accountAuthDetails'>查看详情</a>");
							$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,"发布展会、展位请先进行商家资质认证【商家认证】"));
							$emailContent.=EmailTemplateSP("如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：");
							$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,$emailHref));
							
							$emailContents=EmailTemplate($emailContent);
							sendMail($uemail,'非速搜--认证通知',$emailContents);
					}
					//***********************************给用户发短信和邮件*********************************************
					$this->redirect('FairsoUser/index');
				}else{
					$this->error('修改实名认证状态失败！');
				}
			}else{
				$this->error('执行实名认证失败！');
			}
		}
		/**
			后台执行实名认证失败
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
		public function userApproveFalse(){
			$uid=I('uid','');
			$field1=I('field1','');
			$field2=I('field2','');
			$reason1=I('reason1','');
			$reason2=I('reason2','');
			$reason=I('reason','');
			
			//实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120113;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$uid;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$nickname=$this->user->where('uid='.$uid)->getField('nickname');
			$types['title']="记录".$userName."用户-非速搜用户管理-用户审核-审核".$nickname."-执行审核失败";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			$isName=$this->user->where('uid='.$uid)->setField('isname','2');
			if($isName){
				$data['statecode']='3';
				$data['ktime']=time();
				$data['reason']=$field1.'@@@'.$reason1.'###'.$field2.'@@@'.$reason2.'##@@##'.$reason;
				// dump($data);exit;
				$this->real_name_auth->where('uid='.$uid)->create($data);
				$state=$this->real_name_auth->save();
				if($state){
					//***********************************给用户发短信和邮件*********************************************
						//准备手机号码和邮箱
						$uphone=$this->user->where('uid='.$uid)->getField('uphone');
						$uemail=$this->user->where('uid='.$uid)->getField('uemail');
						//准备短信内容
						$content="【非速搜】尊敬的会员，很遗憾您提交的实名认证材料未通过审核，请登录非速搜展会网查看原因并重新提交。在线订展-让展会更贴心";
						//存在电话则发短信
						if(isset($uphone)){
							sendSms($uphone,$content);
						}
						//存在邮箱则发邮件
						if(isset($uemail)){
							//准备邮箱内容
							$emailHref=C('WEB')."/Perbusiness/accountAuthEdit";
							$emailContent=EmailTemplateP("尊敬的会员：".$uemail);
							$emailContent.=EmailTemplateP("【非速搜】尊敬的会员，很遗憾您提交的实名认证材料未通过审核，请登录非速搜展会网查看原因并重新提交。在线订展-让展会更贴心");
							$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,"发布展会、展位请先进行商家资质认证【商家认证】"));
							$emailContent.=EmailTemplateSP("如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：");
							$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,$emailHref));
							
							$emailContents=EmailTemplate($emailContent);
							sendMail($uemail,'非速搜--认证通知',$emailContents);
						}
					//***********************************给用户发短信和邮件*********************************************
					$this->redirect('FairsoUser/index');
				}else{
					$this->error('执行实名认证失败！');
				}
			}
		}
		/**
		【展示页】后台用户详情页展示
		接收的值：
		{
			'uid'=>用户uid
		}
		返回的值：
		{
			'uid'=>用户uid
			'uname'=>用户名
			'uphone'=>用户电话
			'uemail'=>用户邮箱
			'nickname'=>用户昵称
			'headimg'=>用户头像
			'address'=>用户地址
			'area'=>区域
			'sex'=>用户性别 1男 2女 3保密
			'birthday'=>生日（年月日）
			'score'=>积分
			'ctime'=>创建时间
			'ctype'=>创建类型 1手机号 2邮箱 3QQ 4微信
			'isphone '=>手机认证 1认证通过 2认证未通过
			'isemail'=>邮箱认证 1认证通过 2认证未通过
			'role'=>1游客 2普通会员 3商家用户 4后台管理员
			'name'=>姓名
			'idcard '=>身份证号码
			'cardimgu '=>身份证扫描件正面
			'cardimgd'=>身份证扫描件背面
			'stime '=>身份证有效期开始时间
			'etime '=>身份证有效期结束时间
		}
		**/
		public function userDetail(){
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","FairsoUser/userDetail");
	        $this->assign("Js","FairsoUser/userDetailjs");
			
			$uid=I('uid','');
			
			//查询会员表信息
			$user=$this->user->where('uid='.$uid)->find();
			
			//实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120114;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$uid;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜用户管理-非速搜用户-执行查看".$user['nickname']."详情信息";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			//查询县/区
			$county=$this->citys->where('id='.$user['area'])->field('pid,name')->find();
			//查询市
			$city=$this->citys->where('id='.$county['pid'])->field('pid,name')->find();
			//查询省
			$province=$this->citys->where('id='.$city['pid'])->field('name')->find();
			$user['areaName']=$province['name'].' '.$city['name'].' '.$county['name'];
			//创建时间
			$user['ctime']=date('Y-m-d H:i:s',$user['ctime']);
			
			//查询真实姓名认证
			$realName=$this->real_name_auth->where('uid='.$uid)->find();
			//判断身份证有效期是否长期有效
			if($realName['stime']==0){
				$num=1;
			}else{
				//身份证有效期
				$realName['stime']=date('Y-m-d',$realName['stime']);
				$realName['etime']=date('Y-m-d',$realName['etime']);
			}
			//查询企业名称
			if($user['role']==3){
				$real=$this->real_enterprise_auth->where('uid='.$uid)->field('id,name')->find();
				$user['realid']=$real['id'];
				$user['real']=$real['name'];
			}
			// dump($user);exit;
			$this->assign('user',$user);
			$this->assign('realName',$realName);
			$this->assign('num',$num);
			$this->display("Conmons/Frame");
		}
		/**
		【展示页】后台用户审核页展示
		接收的值：
		{
			'uid'=>用户uid
		}
		返回的值：
		{
			'uid'=>用户uid
			'uname'=>用户名
			'uphone'=>用户电话
			'uemail'=>用户邮箱
			'nickname'=>用户昵称
			'headimg'=>用户头像
			'address'=>用户地址
			'area'=>区域
			'sex'=>用户性别 1男 2女 3保密
			'birthday'=>生日（年月日）
			'score'=>积分
			'ctime'=>创建时间
			'ctype'=>创建类型 1手机号 2邮箱 3QQ 4微信
			'isphone '=>手机认证 1认证通过 2认证未通过
			'isemail'=>邮箱认证 1认证通过 2认证未通过
			'role'=>1游客 2普通会员 3商家用户 4后台管理员
			'name'=>姓名
			'idcard '=>身份证号码
			'cardimgu '=>身份证扫描件正面
			'cardimgd'=>身份证扫描件背面
			'stime '=>身份证有效期开始时间
			'etime '=>身份证有效期结束时间
		}
		**/
		public function userCheck(){
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","FairsoUser/userCheck");
	        $this->assign("Js","FairsoUser/userCheckjs");
			
			$uid=I('uid','');
			//获取页码
			$p=I('p','');
			//获取检索内容
			$jname=I('name','');
			//查询会员表信息
			$user=$this->user->where('uid='.$uid)->find();
			
	        //实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120115;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$uid;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = session('logUserName');
			$types['title']="记录".$userName."用户-非速搜用户管理-用户审核-审核".$user['nickname']."-查看审核页面";
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			//查询县/区
			$county=$this->citys->where('id='.$user['area'])->field('pid,name')->find();
			//查询市
			$city=$this->citys->where('id='.$county['pid'])->field('pid,name')->find();
			//查询省
			$province=$this->citys->where('id='.$city['pid'])->field('name')->find();
			$user['areaName']=$province['name'].' '.$city['name'].' '.$county['name'];
			//生日（年月日）
			// $user['birthday']=date('Y-m-d',$user['birthday']);
			//创建时间
			$user['ctime']=date('Y-m-d H:i:s',$user['ctime']);
			//查询真实姓名认证
			$realName=$this->real_name_auth->where('uid='.$uid)->find();
			//判断身份证有效期是否长期有效
			if($realName['stime']==0){
				$num=1;
			}
			//身份证有效期
			$realName['stime']=date('Y-m-d',$realName['stime']);
			$realName['etime']=date('Y-m-d',$realName['etime']);
			
			
			$this->assign('num',$num);
			$this->assign('user',$user);
			$this->assign('realName',$realName);
			$this->assign('p',$p);
			$this->assign('jname',$jname);
			$this->display("Conmons/Frame");
		}
		/*【展示页】已处理反馈列表
		*接收的值 无
		*返回的值{
			'id' => ID
			'uid' => 用户ID 0为游客
			'content' => 内容
			'contact' => 联系方式
			'ip' => IP
			'ctime' => 反馈时间
			'statecode' => 状态码 1已处理 2待处理
			'state' => 状态 1正常 2停用
			'nickname' => 用户昵称
		}
		*/
		public function processed(){
			 //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","FairsoUser/processed");
	        $this->assign("Js","FairsoUser/processedjs");
			//执行写入缓存操作
			$this->logM->logAdd($types);
			//实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120115;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = $_SESSION['user']['nickname'];
			$types['title']="记录".$userName."用户-反馈管理-已处理反馈-查看已处理反馈列表";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			//获取页码
			$p=I('p','');
			
			$count = $this->feedback_manage->where('statecode=1')->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->feedback_manage->where('statecode=1')->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			
			$len=count($data);
			for($i=0;$i<$len;$i++){
				//判断反馈有无用户ID
				if($data[$i]['uid']!=0){
					$data[$i]['nickname']=$this->user->where('uid='.$data[$i]['uid'])->getField('nickname');
				}else{
					$data[$i]['nickname']='游客';
				}
				//创建时间格式
				$data[$i]['ctime']=date("Y-m-d H:i:s",$data[$i]['ctime']);
				//换行
				$data[$i]['content']=str_replace("\n",'<br/>',$data[$i]['content']);
			}
			
			$this->assign('data',$data);
			$this->assign('row',$show);
			$this->assign('p',$p);
	        $this->display("Conmons/Frame");
		}
		/*【展示页】待处理反馈列表
		*接收的值 无
		*返回的值{
			'id' => ID
			'uid' => 用户ID 0为游客
			'content' => 内容
			'contact' => 联系方式
			'ip' => IP
			'ctime' => 反馈时间
			'statecode' => 状态码 1已处理 2待处理
			'state' => 状态 1正常 2停用
			'nickname' => 用户昵称
		}
		*/
		public function pending(){
			 //加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
	   		//加载内容页模板
			$this->assign("Tel","FairsoUser/pending");
	        $this->assign("Js","FairsoUser/indexjs");
			//执行写入缓存操作
			$this->logM->logAdd($types);
			//实例化日志类
	        $this->logM = new LogFile();
	        //记录操作日志
	        $types=null;
			$types['typefid']=1001201;
			$types['typeid']=100120115;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = $_SESSION['user']['nickname'];
			$types['title']="记录".$userName."用户-反馈管理-待处理反馈-查看待处理反馈列表";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			//获取页码
			$p=I('p','');
			
			$count = $this->feedback_manage->where('statecode=2')->count();// 查询满足要求的总记录数
			$Page = new \Think\PageBootstrap($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			$data = $this ->feedback_manage->where('statecode=2')->limit($Page->firstRow.','.$Page->listRows)->order('ctime DESC') ->select();
			
			$len=count($data);
			for($i=0;$i<$len;$i++){
				//判断反馈有无用户ID
				if($data[$i]['uid']!=0){
					$data[$i]['nickname']=$this->user->where('uid='.$data[$i]['uid'])->getField('nickname');
				}else{
					$data[$i]['nickname']='游客';
				}
				//创建时间格式
				$data[$i]['ctime']=date("Y-m-d H:i:s",$data[$i]['ctime']);
				//换行
				$data[$i]['content']=str_replace("\n",'<br/>',$data[$i]['content']);
			}
			$this->assign('data',$data);
			$this->assign('row',$show);
			$this->assign('p',$p);
	        $this->display("Conmons/Frame");
		}
		/*执行反馈禁用启用
		*接收的值 {
			'id' => ID
			'type' => 类型
		}
		*返回的值 无
		*/
		public function Prodel(){
			$id=I('id','');
			$type=I('type',1);
			//获取页码
			$p=I('p','');
			
			//实例化日志类
			$this->logM = new LogFile();
			//记录操作日志
			$types=null;
			$types['typefid']=1001201;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = $_SESSION['user']['nickname'];
			//获取反馈的状态码
			$statecode=$this->feedback_manage->where('id='.$id)->getField('statecode');
			if($type==1){
				//执行启用状态
				$state=$this->feedback_manage->where("id=".$id)->setField('state',1);
				$types['typeid']=160120102;
				if($statecode==1){
					$types['title']="记录".$userName."用户-反馈管理-已处理反馈-已处理反馈-ID:".$id."-执行启用";
				}else{
					$types['title']="记录".$userName."用户-反馈管理-待处理反馈-待处理反馈-ID:".$id."-执行启用";
				}
			}else{
				//执行禁用状态
				$state=$this->feedback_manage->where("id=".$id)->setField('state',2);
				$types['typeid']=160120103;
				if($statecode==1){
					$types['title']="记录".$userName."用户-反馈管理-已处理反馈-已处理反馈-ID:".$id."-执行禁用";
				}else{
					$types['title']="记录".$userName."用户-反馈管理-待处理反馈-待处理反馈-ID:".$id."-执行禁用";
				}
			}
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			if($statecode==1){
				$this->redirect("FairsoUser/processed",array('p'=>$p));
			}else{
				$this->redirect("FairsoUser/pending",array('p'=>$p));
			}
		}
		/*执行反馈处理
		*接收的值 {
			'id' => ID
		}
		*返回的值 无
		*/
		public function deal(){
			$id=I('id','');
			//实例化日志类
			$this->logM = new LogFile();
			//记录操作日志
			$types=null;
			$types['typefid']=1001201;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			$types['cido']=$id;
			$types['source']=$_SERVER['HTTP_REFERER'];
			//获取当前操作的用户名
			$userName = $_SESSION['user']['nickname'];
			$types['title']="记录".$userName."用户-反馈管理-已处理反馈-已处理反馈-ID:".$id."-执行启用";
			//执行写入缓存操作
			// $this->logM->logAdd($types);
			$save=$this->feedback_manage->where("id=".$id)->setField('statecode',1);
			if($save){
				$this->redirect('FairsoUser/processed');
			}else{
				$this->error('操作失败！');
			}
		}
	}
?>