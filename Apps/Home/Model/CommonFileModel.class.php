<?php
namespace Home\Model;
use Think\Model;
use Home\Model\ExhibitionMapModel as ExhibitionMap;
use Home\Model\PlanModel as PlanModel;
use Home\Model\LogFileModel as LogFile;
// +----------------------------------------------------------------------
// | 非速搜WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://fairso.com All rights reserved.
// +----------------------------------------------------------------------
// | 公共模块
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class CommonFileModel{
    static protected $upload;
    protected $loguser;
    public $error;
    public $search;
    public $exhibition;
    public $exhibition_venue;
    public $exhibition_info;
    public $exhibition_trade;
    public $message;
    public $information_article;
    public $information_column;
    public $information_tag;
    public $citys;
   public function __construct() {
		$this->loguser=D('log_user');
		$this->logrecord=D('log_record');
		$this->search=D('search');
		$this->exhibition=D('exhibition');
		$this->exhibition_venue=D('exhibition_venue');
		$this->exhibition_info=D('exhibition_info');
		$this->exhibition_trade=D('exhibition_trade');
		$this->message=D('message');
		$this->information_article=D('information_article');
		$this->information_column=D('information_column');
		$this->information_tag=D('information_tag');
		$this->citys=D('citys');
      /*  $dir = dirname(__FILE__);
       $this->upload = new Upload;
       $this->upload->maxSize   =     1048576 ;// 设置附件上传大小    
       $this->upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
       $this->upload->rootPath  = substr($dir,0,-16).'/Public';//'/home/www/html/app.langyue.org/phprksh/Public'
       $this->upload->savePath  = '/Uploads/';
       $this->upload->autoSub = true;
       $this->upload->subName = array('date','Ymd');
       $this->upload->saveName = array('uniqid',''); */
   }
   
   public function logAdd($types) {
		//准备数据包
		$data=null;
		//判断SESSION用户日志记录
		$loguserInfo=session('loguser');
		if($loguserInfo){
			if($loguserInfo['id']){
				$data['luid']=$loguserInfo['id'];
			}
			if($loguserInfo['browseragent']){
				$data['browseragent']=$loguserInfo['browseragent'];
			}
			if($loguserInfo['browserversion']){
				$data['browserversion']=$loguserInfo['browserversion'];
			}
			if($loguserInfo['browserplatform']){
				$data['browserplatform']=$loguserInfo['browserplatform'];
			}
			if($loguserInfo['ip']){
				$data['ip']=$loguserInfo['ip'];
			}
			if($loguserInfo['country']){
				$data['country']=$loguserInfo['country'];
			}
			if($loguserInfo['province']){
				$data['province']=$loguserInfo['province'];
			}
			if($loguserInfo['city']){
				$data['city']=$loguserInfo['city'];
			}
			if($loguserInfo['district']){
				$data['district']=$loguserInfo['district'];
			}
			if($loguserInfo['carrier']){
				$data['carrier']=$loguserInfo['carrier'];
			}
		}
		$data['ctime']=time();
		$data['cdate']=strtotime(date('Y-m-d',time()));
		if($types){
			if($types['typefid']){
				$data['typefid']=$types['typefid'];
			}
			if($types['typeid']){
				$data['typeid']=$types['typeid'];
			}
			if($types['typecid']){
				$data['typecid']=$types['typecid'];
			}
			if($types['title']){
				$data['title']=$types['title'];
			}
		}
		$logNewid=$this->logrecord->add($data);
		return $logNewid;
		//return $_SERVER;
   }
   
	/*添加搜索关键词数据
	*数据格式：array
		{
			searchtype =>  1(添加类型)
			searchkey =>  展会名称
			linkid	=>  展会ID
			signid	=>  标签ID
			searchimg	=>  展会缩略图
		}
		{
			searchtype =>  2添加类型)
			searchkey =>  展馆名称
			linkid	=>  展馆ID
			signid	=>  标签ID
			searchimg	=>  展馆缩略图
		}
	*/
	public function searchKey($searchtype,$searchkey,$linkid,$signid,$searchimg){
		//判断是添加数据还是修改搜索数据
		//准备展馆/展会id
		$searchId = $this->search->where("searchtype='".$searchtype."' && linkid='".$linkid."'")->getField('id');
		if(isset($searchId)){
			$data['searchtype'] =  $searchtype;  
			//拼接关键词名称
			$sign = $this->exhibition->where('id='.$linkid)->getField('sign');
			$labelArr = explode(',',$signid);
			$labelCount = count($labelArr);
			for($i=0;$i<$labelCount;$i++){
				//连接行业标签
				$lable .= $this->exhibition_trade->where('id='.$labelArr[$i])->getField('name');
			}
			$data['searchkey'] = $searchkey.$sign.$lable;
			$data['linkid'] =  $linkid;  
			$data['signid'] =  $signid;  
			$data['searchimg'] =  $searchimg;
			//执行生成搜索数据
			if($this->search->where('id='.$searchId)->save($data)){
				return true;
			}
		}else{
			$data['searchtype'] =  $searchtype;  
			//拼接关键词名称
			$sign = $this->exhibition->where('id='.$linkid)->getField('sign');
			$labelArr = explode(',',$signid);
			$labelCount = count($labelArr);
			for($i=0;$i<$labelCount;$i++){
				//连接行业标签
				$lable .= $this->exhibition_trade->where('id='.$labelArr[$i])->getField('name');
			}
			$data['searchkey'] = $searchkey.$sign.$lable;
			$data['linkid'] =  $linkid;  
			$data['signid'] =  $signid;  
			$data['searchimg'] =  $searchimg;
			//dump($data);die();
			//执行生成搜索数据
			if($this->search->add($data)){
				return true;
			}
		}
	}
	
	public function searchUpdata($type,$id){
		//判断是添加数据还是修改搜索数据
		$datas=null;
		if($type==1){
			//展会
			$datas=$this->exhibition->where("state=1 and bannerimg!='NULL' and id=".$id)->find();
		}else if($type==2){
			//展馆
			$datas=$this->exhibition_venue->where("state=1 and bannerimg!='NULL' and id=".$id)->find();
		}else if($type==3){
			//资讯
			$datas=$this->information_article->where("state=1 and statecode=2 and onlinestate=1 and id=".$id)->find();
		}
		if($datas){
			$data=null;
			$data['searchtype'] =  $type;
			$data['linkid'] = $id;
			$data['searchkey'] = $datas['name'].$datas['sign'];
			if($type==1){
				//获取最新的信息
				$signid=$this->exhibition_info->where('statecode=1 and state=1 and pid='.$datas['id'])->field('trade')->order('ctime desc')->limit(1)->select();
				if($signid){
					$signid=$signid[0]['trade'];
					$data['signid'] =  $signid;
					$labelArr = explode(',',$signid);
					$labelCount = count($labelArr);
					$lable=null;
					for($j=0;$j<$labelCount;$j++){
						//连接行业标签
						$lable .= $this->exhibition_trade->where('id='.$labelArr[$j])->getField('name');
					}
					$data['searchkey'] = $datas['name'].$datas['sign'].$lable;
				}
			}else if($type==2){
				$data['searchkey'] = $datas['name'].$datas['sign'];
			}else if($type==3){
				$data['searchkey'] = $datas['title'];
				if($datas['tags']){
					$data['signid'] =  $datas['tags'];
					$labelArr = explode(',',$datas['tags']);
					$labelCount = count($labelArr);
					$lable=null;
					for($j=0;$j<$labelCount;$j++){
						//连接行业标签
						$tradeStr=null;
						$tradeStr=$this->information_tag->where('id='.$labelArr[$j])->getField('name');
						if($tradeStr){
							$lable .= $tradeStr;
						}
					}
					if($lable){
						$data['searchkey'] .= $lable;
					}
				}
				
			}
			if($datas['thumbnail']){
				$data['searchimg'] =  $datas['thumbnail'];
			}
			$searchData=$this->search->where("searchtype=".$type." and linkid=".$id)->field('id')->find();
			if($searchData){
				$state=$this->search->where("id=".$searchData['id'])->save($data); 
			}else{
				$state=$this->search->add($data);
			}
			if($state){
				//更新成功
				return 1;
			}else{
				//更新失败
				return 3;
			}
		}else{
			$searchData=$this->search->where("searchtype=".$type." and linkid=".$id)->field('id')->find();
			if($searchData){
				//执行删除搜索索引
				$state=$this->search->where("id=".$searchData['id'])->delete();
				if($state){
					//更新成功
					return 1;
				}else{
					//更新失败
					return 3;
				}
			}else{
				//数据源获取失败
				return 2;
			}
		}
	}
    //执行系统内短消息添加
	public function systemInfo($dataInfo){
		//准备数据包
		$data = null;
		if(isset($dataInfo)){
			//发送方id
			if(isset($dataInfo['fid'])){
				$data['fid'] = $dataInfo['fid'];
			}
			//接收方id
			if(isset($dataInfo['jid'])){
				$data['jid'] = $dataInfo['jid'];
			}
			//发送内容
			if(isset($dataInfo['infocontent'])){
				$data['infocontent'] = $dataInfo['infocontent'];
			}
			//发送方类型
			if(isset($dataInfo['infotype'])){
				$data['infotype'] = $dataInfo['infotype'];
			}
			//发送ID
			if(isset($dataInfo['messageid'])){
				$data['messageid'] = $dataInfo['messageid'];
			}
			//消息发送时间
			$data['infoctime'] = time();
		}
		//执行消息发送成功
		if($this->message->add($data)){
			return true;
		}
	}
	//执行系统内短消息添加
	public function cityDataStr($areaid){
		$areaInfo=$this->citys->where('id='.$areaid)->find();
		if($areaInfo['level']==0){
			$data['id']=$areaInfo['id'];
			$data['name']=$areaInfo['name'];
		}else if($areaInfo['level']>0){
			$str=$areaInfo['name'];
			$pid=$areaInfo['pid'];
			$pids=$areaInfo['id'];
			for($i=$areaInfo['level'];$i>0;$i--){
				$cityInfo=$this->citys->where('id='.$pid)->find();
				$str=$cityInfo['name']."#".$str;
				$pids=$cityInfo['id']."#".$pids;
				$pid=$cityInfo['pid'];
			}
			$data['id']=$pids;
			$data['name']=$str;
		}
		return $data;
	}
	
	//天气预报接口
	//天气预报接口
	public function forestFun($areaid,$typeNum){
		set_time_limit(0);
		$private_key = 'e22faa_SmartWeatherAPI_e0e8e4c';
		$appid='bbfd78657519927f';
		$appid_six=substr($appid,0,6);
		$type='forecast_f';
		$date=date("YmdHi");
		$public_key="http://open.weather.com.cn/data/?areaid=".$areaid."&type=".$type."&date=".$date."&appid=".$appid;
		 
		$key = base64_encode(hash_hmac('sha1',$public_key,$private_key,TRUE));
		 
		$URL="http://open.weather.com.cn/data/?areaid=".$areaid."&type=".$type."&date=".$date."&appid=".$appid_six."&key=".urlencode($key);
		//echo $URL."<br /-->";
		 
		$string=file_get_contents($URL);
		//返回json格式
		if($typeNum == 1){
			echo $string;
		}
		//返回数组格式
		if($typeNum == 2){
			$forestArr = json_decode($string,true);
			return $forestArr;
		}
	}
	
	//支付宝支付成功订单/展位状态处理
	public function alipayCheckPublic($orderId,$payCtime,$trade_no){
		//支付成功实现展位状态、订单状态的改变
		//根据订单ID查找对应的展位ID
		$user = $_SESSION['userInfo'];
		//准备用户头像
		$oid = M('order')->where("id='".$orderId."' && uid='".$user['uid']."'")->getField('gid');
		//根据查找的展位ID修改展位状态为已销售
		M('exhibition_position')->where('id='.$oid)->setField('salestate',3);
		//准备订单部分状态的修改数据
		//修改订单状态为网站已确认
		$list['statecode'] = 3;
		//准备支付时间以及网站确认时间
		$list['paytime'] = strtotime($payCtime);
		$list['webtime'] = strtotime($payCtime);
		//准备支付方式
		$list['paytype'] = 3;
		//准备购买商品类型
		$list['gtype'] = 1;
		//准备代开发票状态
		$list['billstate'] = 1;
		//执行支付宝流水号的添加
		$list['trade_no'] = $trade_no;
		//记录操作日志
		$types=null;
		$types['typefid']=1401101;
		$types['typeid']=140110113;
		$types['controller']=CONTROLLER_NAME;
		$types['method']=ACTION_NAME;
		//用户id
		$types['cido']=$user['uid'];
		//订单id
		$types['cidt']=$orderId;
		$types['source']=$_SERVER['HTTP_REFERER'];
		$userName = M('user')->where('uid='.$user['uid'])->getField('nickname');
		$types['title']="记录".$userName."用户-会员中心-订单中心-执行支付宝支付";
		//实例化日志类
		$this->logM = new LogFile();
		//执行写入缓存操作
		$this->logM->logAdd($types);
		//执行订单支付完成
		$info = M('order')->where("id=".$orderId)->setField($list);
		if($info){
			//var_dump($oid);die();
			//准备展位id和展位配置cid
			$configArr = M("exhibition_position")->where('id='.$oid)->find();
			
			/*成功跳转至改变svj图展位状态方法再跳转至用户中心未支付展位页面
			*获取展位配置id
			*/
			$eid = $configArr['id'];
			$configid = $configArr['cid'];
			$this->ExhibitionMapM = new ExhibitionMap();
			$this->ExhibitionMapM->syncPositionMap($configid,$eid);
			
			//创建计划金额
			$this->PlanModel = new PlanModel();
			$this->PlanModel->orderFinsh($orderId);
			
			//***********************************给用户发短信和邮件*********************************************
			//准备好当前用户的电话和邮箱
			$uphone =   M('user')->where('uid='.$user['uid'])->getField('uphone');
			$uemail =   M('user')->where('uid='.$user['uid'])->getField('uemail');
			
			
			//准备订单支付金额
			$price = M('order')->where("id='".$orderId."' && uid='".$user['uid']."'")->getField('price');
			//准备订单号
			$sign = M('order')->where("id='".$orderId."' && uid='".$user['uid']."'")->getField('sign');
			//准备短信内容
			$content = "【非速搜】会员您好，展位订单：".$sign."已于".$payCtime."成功付款".$price."元，请与商家及时联系确认订单，平台热线：400-6688-733。";
			
			
			/*
			*如果子账号信息不存在则使用商家联系电话
			*准备商家子账户ID信息
			*/
			$sonId = M('order')->where("id='".$orderId."' && uid='".$user['uid']."'")->getField('sid'); 
			//准备子账户400电话信息
			$sonIdLink = M('enterprise_call_uid')->where('uid='.$sonId)->getField('cid');
			$phoneNumber = M('enterprise_call_number')->where('id='.$sonIdLink)->getField('number');
			//子账户真实姓名
			$realName = M('real_name_auth')->where('uid='.$sonId)->getField('name');
			if(isset($phoneNumber)){
				$phoneNumberd = '400-6688-733-'.$phoneNumber;
				$contents = "【非速搜】商家联系电话：".$phoneNumberd."，联系人姓名：".$realName."，如有疑问请咨询客服,客服热线：400-6688-733。";
			}else{
				//准备企业ID
				$parentId = M('order')->where("id='".$orderId."' && uid='".$user['uid']."'")->getField('eid'); 
				$phoneNumbers = M('enterprise_call_number')->where('eid='.$parentId)->order('id desc')->limit(1)->find();
				$phoneNumberd = '400-6688-733-'.$phoneNumbers['number'];
				$contents = "【非速搜】商家联系电话：".$phoneNumberd."，联系人姓名：".$realName."，如有疑问请咨询客服,客服热线：400-6688-733。";
			}
			
			//存在电话则发短信
			if(isset($uphone)){
				sendSms($uphone,$content);
				sendSms($uphone,$contents);
			}
			//存在邮箱则发邮件
			if(isset($uemail)){
				//准备邮箱内容
				$emailHref=C('WEBURL')."/Member/orderDetails/id/".$orderId;
				$emailContent=EmailTemplateP("您好：".$uemail);
				$emailContent.=EmailTemplateP("您的展位已购买成功！");
				$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,"查看详情"));
				$emailContent.=EmailTemplateSP("如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：");
				$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,$emailHref));
				$emailContent.=EmailTemplateP("<b>订单信息</b>");
				//查找订单信息
				$orderInfo = M('order')->where("id='".$orderId."' && uid='".$user['uid']."'")->find();
				//获取展会基础表数据
				$positionInfo=M('exhibition_position')->where('id='.$orderInfo['gid'])->field('id,eid,bid,sign,number,configtype,long,width,area')->find();
				$exhibitionInfo=M('exhibition')->where("id=".$positionInfo['eid'])->find();
				if($exhibitionInfo){
					if($exhibitionInfo['name']){
						$emailContent.=EmailTemplateSP("展会名称：".$exhibitionInfo['name']);
					}
					if($exhibitionInfo['pid']){
						//获取展馆名称
						$venueInfo=M(exhibition_venue)->where('id='.$exhibitionInfo['pid'])->field('id,name,areaid')->find();
						if($venueInfo['name']){
							$emailContent.=EmailTemplateSP("展馆名称：".$venueInfo['name']);
						}
					}
					if($exhibitionInfo['stime'] && $exhibitionInfo['etime']){
						$emailContent.=EmailTemplateSP("开展时间：".date("Y年m月d日",$exhibitionInfo['stime'])."-".date("Y年m月d日",$exhibitionInfo['etime']));
					}
					if($venueInfo['areaid']){
						//获取地区名称
						// $this->CommonFile = new CommonFile();
						// $cityInfo=$this->CommonFile->cityDataStr($venueInfo['areaid']);
						$cityInfo=$this->cityDataStr($venueInfo['areaid']);
						if($cityInfo['name']){
							$cityStr=str_replace("#","&nbsp;",$cityInfo['name']);
							$emailContent.=EmailTemplateSP("展会地区：".$cityStr);
						}
					}
					if($positionInfo['sign']){
						$emailContent.=EmailTemplateSP("展位编号：".$positionInfo['sign']);
					}
					if($positionInfo['number']){
						$emailContent.=EmailTemplateSP("商家展位号：".$positionInfo['number']);
					}
					if($positionInfo['configtype']==1){
						$emailContent.=EmailTemplateSP("展位类型：标展");
					}else if($positionInfo['configtype']==2){
						$emailContent.=EmailTemplateSP("展位类型：光地");
					}
					if($positionInfo['long'] && $positionInfo['width'] && $positionInfo['area']){
						$emailContent.=EmailTemplateSP("展位面积：".$positionInfo['long']."*".$positionInfo['width']."/".$positionInfo['area']."㎡");
					}
					if($orderInfo['sign']){
						$emailContent.=EmailTemplateSP("订单编号：".$orderInfo['sign']);
					}
					if($orderInfo['title']){
						$emailContent.=EmailTemplateSP("订单内容：".$orderInfo['title']);
					}
					if($orderInfo['price']){
						$emailContent.=EmailTemplateSP("订单金额：<span style='color:#FF6600'>￥".$orderInfo['price']."</span>");
					}
					$emailContent.=EmailTemplateSP("订单状态：<span style='color:#139f01'>已支付</span><span style='color:#139f01'>(支付宝)</span>");
					if($positionInfo['bid']){
						$enterpriseName = M('real_enterprise_auth')->getFieldById($positionInfo['bid'],'name');
						if($enterpriseName){
							$emailContent.=EmailTemplateSP("展位商家：".$enterpriseName);
						}
					}
					if($realName){
						$emailContent.=EmailTemplateSP("联系人姓名：".$realName);
					}
					if(isset($phoneNumber)){
						$emailContent.=EmailTemplateSP("商家客服热线：4006688733转".$phoneNumber);
					}else{
						$emailContent.=EmailTemplateSP("商家客服热线：4006688733转".$phoneNumbers);
					}
				}
				$emailContents=EmailTemplate($emailContent);
				sendMail($uemail,'非速搜——订单通知',$emailContents);
			}
			//***********************************给用户发短信和邮件*********************************************
			//***********************************给商家发短信和邮件*********************************************
			//判断该商家否有电话
			$busiphone = M('user')->where('uid='.$sonId)->getField('uphone');
			$busiemail = M('user')->where('uid='.$sonId)->getField('uemail');
			if(isset($busiphone)){
				$contentd = "【非速搜】商家您好，展位订单：".$sign."于".$payCtime."成功购买，交易金额：".$price."￥，请您及时与买家联系，平台热线：400-6688-733。";
				sendSms($busiphone,$contentd);
			}
			if(isset($busiemail)){
				//准备邮箱内容
				$emailHreff=C('WEBURL')."/Business/order_boss_comment/id/".$orderId;
				$emailContentf=EmailTemplateP("您好：".$busiemail);
				$emailContentf.=EmailTemplateP("您的展位已被成功购买，请您及时与企业联系，订单首批预订金将于10个工作日之后进行结算，请您注意账户资金！");
				$emailContentf.=EmailTemplateP(EmailTemplatePA($emailHreff,"查看详情"));
				$emailContentf.=EmailTemplateSP("如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：");
				$emailContentf.=EmailTemplateP(EmailTemplatePA($emailHreff,$emailHreff));
				$emailContentf.=EmailTemplateP("<b>订单信息</b>");
				//查找订单信息
				$orderInfof = M('order')->where("id='".$orderId."' && uid='".$user['uid']."'")->find();
				//获取展会基础表数据
				$positionInfof= M('exhibition_position')->where('id='.$orderInfof['gid'])->field('id,eid,bid,sign,number,configtype,long,width,area')->find();
				$exhibitionInfof= M('exhibition')->where("id=".$positionInfof['eid'])->find();
				if($exhibitionInfof){
					if($exhibitionInfof['name']){
						$emailContentf.=EmailTemplateSP("展会名称：".$exhibitionInfof['name']);
					}
					if($positionInfof['sign']){
						$emailContentf.=EmailTemplateSP("展位编号：".$positionInfof['sign']);
					}
					if($positionInfof['number']){
						$emailContentf.=EmailTemplateSP("商家展位号：".$positionInfof['number']);
					}
					if($positionInfof['configtype']==1){
						$emailContentf.=EmailTemplateSP("展位类型：标展");
					}else if($positionInfof['configtype']==2){
						$emailContentf.=EmailTemplateSP("展位类型：光地");
					}
					if($positionInfof['long'] && $positionInfof['width'] && $positionInfof['area']){
						$emailContentf.=EmailTemplateSP("展位面积：".$positionInfof['long']."*".$positionInfof['width']."/".$positionInfof['area']."㎡");
					}
					if($orderInfof['sign']){
						$emailContentf.=EmailTemplateSP("订单编号：".$orderInfof['sign']);
					}
					if($orderInfof['title']){
						$emailContentf.=EmailTemplateSP("订单内容：".$orderInfof['title']);
					}
					if($orderInfof['price']){
						$emailContentf.=EmailTemplateSP("订单金额：<span style='color:#FF6600'>￥".$orderInfof['price']."</span>");
					}
					$emailContentf.=EmailTemplateSP("订单状态：<span style='color:#139f01'>已支付</span><span style='color:#139f01'>(支付宝)</span>");
					//准备提交订单联系人信息
					$orderContact = M('order_contact')->where('oid='.$orderId)->find();
					if($user['isname'] == 1){
						$orderPeople = M('real_name_auth')->where('uid='.$user['uid'])->getField('name');
						$emailContentf.=EmailTemplateSP("联系人：".$orderPeople);
						$emailContentf.=EmailTemplateSP("联系电话：".$orderContact['ptel']);
						$emailContentf.=EmailTemplateSP("参展企业：".$orderContact['ename']);
					}else if($orderContact['pname']){
						$emailContentf.=EmailTemplateSP("联系人：".$orderContact['pname']);
						$emailContentf.=EmailTemplateSP("联系电话：".$orderContact['ptel']);
						$emailContentf.=EmailTemplateSP("参展企业：".$orderContact['ename']);
					}else{
						$emailContentf.=EmailTemplateSP("联系人：".$user['tname']);
						$emailContentf.=EmailTemplateSP("参展企业：".$user['ename']);
						if($user['uphone']){
							$emailContentf.=EmailTemplateSP("联系电话：".$user['uphone']);
						}else{
							$emailContentf.=EmailTemplateSP("联系电话：".$user['etel']);
						}
					}
				}
				$emailContentss=EmailTemplate($emailContentf);
				sendMail($busiemail,'非速搜——订单通知',$emailContentss);
			}
			//***********************************给商家发短信和邮件*********************************************
		}
		return true;
	}
	
	//京东网银支付成功订单/展位状态处理
	public function bankonlineCheckPublic($orderId,$orderSign,$orderPrice){
		//改变订单状态为网站已确认
		$mod['statecode'] = 3;
		//更新订单支付时间
		$mod['webtime'] = time(); 
		//代开发票状态
		$mod['billstate'] = 1;
		//支付类型
		$mod['paytype'] = 2;
		//准备购买商品类型
		$mod['gtype'] = 1;
		//执行更新
		$info = M("order")->where("id = ".$orderId)->setField($mod);
		// dump($info);
		if(isset($info)){
			//改变展位状态为3已购买
			//准备展位ID
			$positionId = M("order")->where("id = ".$orderId)->getField('gid');
			//执行展位状态改变
			M("exhibition_position")->where("id=".$positionId)->setField('salestate',3);
			//准备缓存数据
			$user = $_SESSION['userInfo'];
			//准备用户头像
			$headimg = M('user')->where('uid='.$user['uid'])->getField('headimg');
			
			//记录操作日志
			$types=null;
			$types['typefid']=1401101;
			$types['typeid']=140110116;
			$types['controller']=CONTROLLER_NAME;
			$types['method']=ACTION_NAME;
			//用户id
			$types['cido']=$user['uid'];
			//订单id
			$types['cidt']=$orderId;
			$types['source']=$_SERVER['HTTP_REFERER'];
			$userName = M('user')->where('uid='.$user['uid'])->getField('nickname');
			$types['title']="记录".$userName."用户-会员中心-订单中心-执行网银支付";
			//实例化日志类
			$this->logM = new LogFile();
			//执行写入缓存操作
			$this->logM->logAdd($types);
			
			//准备展位id和展位配置cid
			$configArr = M("exhibition_position")->where('id='.$positionId)->find();
			
			/*成功跳转至改变svj图展位状态方法再跳转至用户中心未支付展位页面
			*获取展位配置id
			*/
			$eid = $configArr['id'];
			$configid = $configArr['cid'];
			$this->ExhibitionMapM = new ExhibitionMap();
			$this->ExhibitionMapM->syncPositionMap($configid,$eid);
			
			//创建计划金额
			$this->PlanModel = new PlanModel();
			$this->PlanModel->orderFinsh($orderId);
			
			//***********************************给用户发短信和邮件*********************************************
			//准备好当前用户的电话和邮箱
			$uphone =   M('user')->where('uid='.$user['uid'])->getField('uphone');
			$uemail =   M('user')->where('uid='.$user['uid'])->getField('uemail');
			
			//准备订单支付金额
			$price = M('order')->where("id=".$orderId)->getField('price');
			//准备订单号
			$sign = M('order')->where("id=".$orderId)->getField('sign');
			//准备短信内容
			$content = "【非速搜】会员您好，展位订单：".$orderSign."已于".date('Y-m-d H:i:s',$mod['webtime'])."成功付款".$orderPrice."元，请与商家及时联系确认订单，平台热线：400-6688-733。";
			
			/*
			*如果子账号信息不存在则使用商家联系电话
			*准备商家子账户ID信息
			*/
			$sonId = M('order')->where("id=".$orderId)->getField('sid'); 
			//准备子账户400电话信息
			$sonIdLink = M('enterprise_call_uid')->where('uid='.$sonId)->getField('cid');
			$phoneNumber = M('enterprise_call_number')->where('id='.$sonIdLink)->getField('number');
			//子账户真实姓名
			$realName = M('real_name_auth')->where('uid='.$sonId)->getField('name');
			if(isset($phoneNumberd)){
				$phoneNumberd = '400-6688-733-'.$phoneNumber;
				$contents = "【非速搜】商家联系电话：".$phoneNumberd."，联系人姓名：".$realName."，如有疑问请咨询客服,客服热线：400-6688-733。";
			}else{
				//准备企业ID
				$parentId = M('order')->where("id=".$orderId)->getField('eid'); 
				$phoneNumbers = M('enterprise_call_number')->where('eid='.$parentId)->order('id desc')->limit(1)->find();
				$parentNumberd = '400-6688-733-'.$phoneNumbers['number'];
				$contents = "【非速搜】商家联系电话：".$parentNumberd."，联系人姓名：".$realName."，如有疑问请咨询客服,客服热线：400-6688-733。";
			}
			
			//存在电话则发短信
			if(isset($uphone)){
				sendSms($uphone,$content);
				sendSms($uphone,$contents);
			}
			//存在邮箱则发邮件
			if(isset($uemail)){
				//准备邮箱内容
				$emailHref=C('WEBURL')."/Member/orderDetails/id/".$orderId;
				$emailContent=EmailTemplateP("您好：".$uemail);
				$emailContent.=EmailTemplateP("您的展位已购买成功！");
				$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,"查看详情"));
				$emailContent.=EmailTemplateSP("如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：");
				$emailContent.=EmailTemplateP(EmailTemplatePA($emailHref,$emailHref));
				$emailContent.=EmailTemplateP("<b>订单信息</b>");
				//查找订单信息
				$orderInfo = M('order')->where("id=".$orderId)->find();
				//获取展会基础表数据
				$positionInfo=M('exhibition_position')->where('id='.$orderInfo['gid'])->field('id,eid,bid,sign,number,configtype,long,width,area')->find();
				$exhibitionInfo=M('exhibition')->where("id=".$positionInfo['eid'])->find();
				if($exhibitionInfo){
					if($exhibitionInfo['name']){
						$emailContent.=EmailTemplateSP("展会名称：".$exhibitionInfo['name']);
					}
					if($exhibitionInfo['pid']){
						//获取展馆名称
						$venueInfo=M(exhibition_venue)->where('id='.$exhibitionInfo['pid'])->field('id,name,areaid')->find();
						if($venueInfo['name']){
							$emailContent.=EmailTemplateSP("展馆名称：".$venueInfo['name']);
						}
					}
					if($exhibitionInfo['stime'] && $exhibitionInfo['etime']){
						$emailContent.=EmailTemplateSP("开展时间：".date("Y年m月d日",$exhibitionInfo['stime'])."-".date("Y年m月d日",$exhibitionInfo['etime']));
					}
					if($venueInfo['areaid']){
						//获取地区名称
						$cityInfo=$this->cityDataStr($venueInfo['areaid']);
						if($cityInfo['name']){
							$cityStr=str_replace("#","&nbsp;",$cityInfo['name']);
							$emailContent.=EmailTemplateSP("展会地区：".$cityStr);
						}
					}
					if($positionInfo['sign']){
						$emailContent.=EmailTemplateSP("展位编号：".$positionInfo['sign']);
					}
					if($positionInfo['number']){
						$emailContent.=EmailTemplateSP("商家展位号：".$positionInfo['number']);
					}
					if($positionInfo['configtype']==1){
						$emailContent.=EmailTemplateSP("展位类型：标展");
					}else if($positionInfo['configtype']==2){
						$emailContent.=EmailTemplateSP("展位类型：光地");
					}
					if($positionInfo['long'] && $positionInfo['width'] && $positionInfo['area']){
						$emailContent.=EmailTemplateSP("展位面积：".$positionInfo['long']."*".$positionInfo['width']."/".$positionInfo['area']."㎡");
					}
					if($orderInfo['sign']){
						$emailContent.=EmailTemplateSP("订单编号：".$orderInfo['sign']);
					}
					if($orderInfo['title']){
						$emailContent.=EmailTemplateSP("订单内容：".$orderInfo['title']);
					}
					if($orderInfo['price']){
						$emailContent.=EmailTemplateSP("订单金额：<span style='color:#FF6600'>￥".$orderInfo['price']."</span>");
					}
					$emailContent.=EmailTemplateSP("订单状态：<span style='color:#139f01'>已支付</span><span style='color:#139f01'>(网银)</span>");
					if($positionInfo['bid']){
						$enterpriseName = M('real_enterprise_auth')->getFieldById($positionInfo['bid'],'name');
						if($enterpriseName){
							$emailContent.=EmailTemplateSP("展位商家：".$enterpriseName);
						}
					}
					if($realName){
						$emailContent.=EmailTemplateSP("联系人姓名：".$realName);
					}
					if(isset($phoneNumber)){
						$emailContent.=EmailTemplateSP("商家客服热线：4006688733转".$phoneNumber);
					}else{
						$emailContent.=EmailTemplateSP("商家客服热线：4006688733转".$phoneNumber);
					}
				}
				$emailContents=EmailTemplate($emailContent);
				sendMail($uemail,'非速搜——订单通知',$emailContents);
			}
			//***********************************给用户发短信和邮件*********************************************
			//***********************************给商家发短信和邮件*********************************************
			//判断该商家否有电话
			$busiphone = M('user')->where('uid='.$sonId)->getField('uphone');
			$busiemail = M('user')->where('uid='.$sonId)->getField('uemail');
			if(isset($busiphone)){
				$contentd = "【非速搜】商家您好，展位订单：".$sign."于".date('Y-m-d H:i:s',$mod['webtime'])."成功购买，交易金额：".$price."￥，请您及时与买家联系，平台热线：400-6688-733。";
				sendSms($busiphone,$contentd);
			}
			if(isset($busiemail)){
				//准备邮箱内容
				$emailHreff=C('WEBURL')."/Business/order_boss_comment/id/".$orderId;
				$emailContentf=EmailTemplateP("您好：".$busiemail);
				$emailContentf.=EmailTemplateP("您的展位已被成功购买，请您及时与企业联系，订单首批预订金将于10个工作日之后进行结算，请您注意账户资金！");
				$emailContentf.=EmailTemplateP(EmailTemplatePA($emailHreff,"查看详情"));
				$emailContentf.=EmailTemplateSP("如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：");
				$emailContentf.=EmailTemplateP(EmailTemplatePA($emailHreff,$emailHreff));
				$emailContentf.=EmailTemplateP("<b>订单信息</b>");
				//查找订单信息
				$orderInfof = M('order')->where("id=".$orderId)->find();
				//获取展会基础表数据
				$positionInfof= M('exhibition_position')->where('id='.$orderInfof['gid'])->field('id,eid,bid,sign,number,configtype,long,width,area')->find();
				$exhibitionInfof= M('exhibition')->where("id=".$positionInfof['eid'])->find();
				if($exhibitionInfof){
					if($exhibitionInfof['name']){
						$emailContentf.=EmailTemplateSP("展会名称：".$exhibitionInfof['name']);
					}
					if($positionInfof['sign']){
						$emailContentf.=EmailTemplateSP("展位编号：".$positionInfof['sign']);
					}
					if($positionInfof['number']){
						$emailContentf.=EmailTemplateSP("商家展位号：".$positionInfof['number']);
					}
					if($positionInfof['configtype']==1){
						$emailContentf.=EmailTemplateSP("展位类型：标展");
					}else if($positionInfof['configtype']==2){
						$emailContentf.=EmailTemplateSP("展位类型：光地");
					}
					if($positionInfof['long'] && $positionInfof['width'] && $positionInfof['area']){
						$emailContentf.=EmailTemplateSP("展位面积：".$positionInfof['long']."*".$positionInfof['width']."/".$positionInfof['area']."㎡");
					}
					if($orderInfof['sign']){
						$emailContentf.=EmailTemplateSP("订单编号：".$orderInfof['sign']);
					}
					if($orderInfof['title']){
						$emailContentf.=EmailTemplateSP("订单内容：".$orderInfof['title']);
					}
					if($orderInfof['price']){
						$emailContentf.=EmailTemplateSP("订单金额：<span style='color:#FF6600'>￥".$orderInfof['price']."</span>");
					}
					$emailContentf.=EmailTemplateSP("订单状态：<span style='color:#139f01'>已支付</span><span style='color:#139f01'>(网银)</span>");
					$nickname = M('user')->where('uid='.$user['uid'])->getField('nickname');
					if($nickname){
						$emailContentf.=EmailTemplateSP("企业名称：".$nickname);
					}
					//准备提交订单联系人信息
					$orderContact = M('order_contact')->where('oid='.$orderId)->find();
					if($user['isname'] == 1){
						$orderPeople = M('real_name_auth')->where('uid='.$user['uid'])->getField('name');
						$emailContentf.=EmailTemplateSP("联系人：".$orderPeople);
						$emailContentf.=EmailTemplateSP("联系电话：".$orderContact['ptel']);
						$emailContentf.=EmailTemplateSP("参展企业：".$orderContact['ename']);
					}else if($orderContact['pname']){
						$emailContentf.=EmailTemplateSP("联系人：".$orderContact['pname']);
						$emailContentf.=EmailTemplateSP("联系电话：".$orderContact['ptel']);
						$emailContentf.=EmailTemplateSP("参展企业：".$orderContact['ename']);
					}else{
						$emailContentf.=EmailTemplateSP("联系人：".$user['tname']);
						$emailContentf.=EmailTemplateSP("参展企业：".$user['ename']);
						if($user['uphone']){
							$emailContentf.=EmailTemplateSP("联系电话：".$user['uphone']);
						}else{
							$emailContentf.=EmailTemplateSP("联系电话：".$user['etel']);
						}
					}
				}
				$emailContentss=EmailTemplate($emailContentf);
				sendMail($busiemail,'非速搜——订单通知',$emailContentss);
			}
			//***********************************给商家发短信和邮件*********************************************
		}
		return true;
	}
}




























