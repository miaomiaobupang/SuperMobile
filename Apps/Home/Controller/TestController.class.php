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
class TestController extends LimitController {
	private $user;//用户表
	private $hope_disease_type;	//实例化疾病类型管理表
	private $hope_entry;	//实例化词条管理表
	private $hope_entry_info;	//实例化词条信息管理表
	private $hope_uploads;	//实例化文件管理表
	private $hope_message;	//实例化资讯管理表
	private $hope_question;	//实例化问题管理表
	private $hope_link;	//实例化全局关系管理表
	private $hope_temp_data;	//实例化系统临时抓取数据管理表

	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		header("Content-type:text/html;charset=utf-8");
		//实例化用户表
		$this->user     = D('user');
		//实例化疾病类型管理表
		$this->hope_disease_type = D('hope_disease_type');
		//实例化词条信息管理表
		$this->hope_entry_info = D('hope_entry_info');
		//实例化文件管理表
		$this->hope_uploads = D('hope_uploads');
		//实例化资讯管理表
		$this->hope_message = D('hope_message');
		//实例化问题管理表
		$this->hope_question = D('hope_question');
		//实例化词条管理表
		$this->hope_entry = D('hope_entry');
		//实例化全局关系管理表
		$this->hope_link = D('hope_link');
		//实例化系统临时抓取数据管理表
		$this->hope_temp_data = D('hope_temp_data');
		
		//判断是否登录
		$this->session = $_SESSION['user'];
	}
	public function index(){
		$data = $this->hope_disease_type->where('state=1 && level = 1')->select();
		$countOne = count($data);
		for($i=0;$i<$countOne;$i++){
			$info = null;
			$info = $this->hope_disease_type->where('state=1 && level = 2 && fid='.$data[$i]['id'])->select();
			$countTwo = count($info);
			$listHTML = null;
			for($m=0;$m<$countTwo;$m++){
				$status = $this->hope_link->where('disease_id='.$info[$m]['id'])->select();
				if($status){
					$listHTML .= '<a href='.C('WEBURLS').'/diseaseList/'.$info[$m]['id'].' target="_blank"><span title="'.$info[$m]['name'].'" class="keywords">'.$info[$m]['name'].'</span></a>';
					$data[$i]['m'][$m]['id'] = $info[$m]['id'];
					$data[$i]['m'][$m]['name'] = $info[$m]['name'];
					$data[$i]['listHtml'] = $listHTML;
				}
			}
		}
		//疾病类型
		$this->assign('data',$data);
		//推荐超级医生
		$oneArr = array(
						0=>8,
						1=>9
					);
		$oneCount = count($oneArr);
		ini_set('memory_limit','512M');
		for($i=0;$i<$oneCount;$i++){
			$oneDoctor[$i] = $this->hope_entry->where('id='.$oneArr[$i])->find();
			$ine_file_id = $this->hope_entry_info->where('id='.$oneDoctor[$i]['entry_info_id'])->getField('file_id');
			$oneDoctor[$i]['abstract'] = $this->hope_entry_info->where('id='.$oneDoctor[$i]['entry_info_id'])->getField('abstract');
			$oneDoctor[$i]['img'] = $this->hope_uploads->where('id='.$ine_file_id)->getField('url');
			if($i==0){
				$oneDoctor[$i]['message'] = $this->hope_message->where('type=6&& state=1')->limit(0,4)->order('id desc')->select();
			}else{
				$oneDoctor[$i]['message'] = $this->hope_message->where('type=6&& state=1')->limit(4,7)->order('id desc')->select();
			}
			$tmpC = count($oneDoctor[$i]['message']);
			for($t=0;$t<$tmpC;$t++){
				$oneDoctor[$i]['message'][$t]['ctime'] = date('Y-m-d',$oneDoctor[$i]['message'][$t]['ctime']);
			}
		}
		$twoDoctor = $this->hope_entry->where('entry_type_id=2 && statecode =1 && state=1')->limit('0,11')->select();
		
		$this->assign('oneDoctor',$oneDoctor);
		$this->assign('twoDoctor',$twoDoctor);
		
		//推荐顶级医院
		$twoArr = array(
						0=>63,
						1=>58
					);
		$twoCount = count($twoArr);	
		$line_file_id = null;		
		$oneHospital = null;		
		for($i=0;$i<$twoCount;$i++){
			$oneHospital[$i] = $this->hope_entry->where('id='.$twoArr[$i])->find();
			$line_file_id = $this->hope_entry_info->where('id='.$oneHospital[$i]['entry_info_id'])->getField('file_id');
			$oneHospital[$i]['abstract'] = $this->hope_entry_info->where('id='.$oneHospital[$i]['entry_info_id'])->getField('abstract');
			$oneHospital[$i]['img'] = $this->hope_uploads->where('id='.$line_file_id)->getField('url');
			if($i==0){
				$oneHospital[$i]['message'] = $this->hope_message->where('type=7&& state=1')->limit(0,3)->order('id desc')->select();
			}else{
				$oneHospital[$i]['message'] = $this->hope_message->where('type=7&& state=1')->limit(3,6)->order('id desc')->select();
			}
			$tmpD = count($oneHospital[$i]['message']);
			if($tmpD >0){
				for($t=0;$t<$tmpD;$t++){
					$oneHospital[$i]['message'][$t]['ctime'] = date('Y-m-d',$oneHospital[$i]['message'][$t]['stime']);
				}
			}
		}			
		$this->assign('oneHospital',$oneHospital);
		
		//最新资讯
		$NewMessage = $this->hope_message->where('type=1&& state=1')->limit(0,6)->order('id desc')->select();
		$this->assign('NewMessage',$NewMessage);
		//最新临床
		// $oneExample = $this->hope_message->where('type=2')->limit(0,3)->order('id desc')->select();
		//热门资讯
		$hotMessage = $this->hope_message->where('state=1')->limit(0,3)->order('view_num desc')->select();
		
		//推荐回答
		$oneTuiQuestion = $this->hope_question->where('state=1')->limit(0,9)->order('answer_num desc')->select();
		// dump($oneTuiQuestion);die();
		//最新回答
		$oneNewQuestion = $this->hope_question->where('state=1')->limit(0,9)->order('id desc')->select();
		$this->assign('oneExample',$hotMessage);
		$this->assign('oneTuiQuestion',$oneTuiQuestion);
		$this->assign('oneNewQuestion',$oneNewQuestion);
		
		//药物信息
		$onePill = $this->hope_entry->where('entry_type_id=4&& state=1')->order('id desc')->limit(0,6)->select();
		$threeCount = count($onePill);
		for($i=0;$i<$threeCount;$i++){
			$ine_file_id = null;
			$ine_file_id = $this->hope_entry_info->where('id='.$onePill[$i]['entry_info_id'])->getField('file_id');
			$onePill[$i]['img'] = $this->hope_uploads->where('id='.$ine_file_id)->getField('url');
		}
		$this->assign('onePill',$onePill);
		
		//医疗前沿
		$oneDoctorPre = $this->hope_message->where('type=1&& state=1')->limit(0,6)->order('id desc')->select();
		$fourCount = count($oneDoctorPre);
		for($i=0;$i<$fourCount;$i++){
			$oneDoctorPre[$i]['img'] = $this->hope_uploads->where('id='.$oneDoctorPre[$i]['file_id'])->getField('url');
			$oneDoctorPre[$i]['content'] = mb_substr($oneDoctorPre[$i]['content'],0,15,'utf-8');
		}
		$this->assign('oneDoctorPre',$oneDoctorPre);
		
		//最新公益
		$oneNewCommonweal = $this->hope_message->where('type=3&& state=1')->order('id desc')->limit(4)->select();
		$fiveCount = count($oneNewCommonweal);
		for($i=0;$i<$fiveCount;$i++){
			$oneNewCommonweal[$i]['img'] = $this->hope_uploads->where('id='.$oneNewCommonweal[$i]['file_id'])->getField('url');
			$oneNewCommonweal[$i]['content'] = mb_substr($oneNewCommonweal[$i]['content'],0,15,'utf-8');
		}
		
		//世界大师
		$oneNewMaster = $this->hope_message->where('type=5&& state=1')->order('id desc')->limit(4)->select();
		$fiveCount = count($oneNewMaster);
		for($i=0;$i<$fiveCount;$i++){
			$oneNewMaster[$i]['img'] = $this->hope_uploads->where('id='.$oneNewMaster[$i]['file_id'])->getField('url');
		}
		$this->assign('oneNewCommonweal',$oneNewCommonweal);
		$this->assign('oneNewCommonweals',$oneNewCommonweal);
		$this->assign('oneNewMaster',$oneNewMaster);
		$this->assign('oneNewMasters',$oneNewMaster);
		
		$this->display();
	}

	/***********************************************以下为测试方法******************************************************/
	/***********************************************以下为测试方法******************************************************/
	/***********************************************以下为测试方法******************************************************/
	
	//同步资讯简介
	public function asyncData(){
		ini_set('memory_limit','512M');
		$info = $this->hope_entry_info->where('abstract is null')->select();
		// $info = $this->hope_message->where('abstract is null')->select();
		dump($info);die();
		$count = count($info);
		for($i=0;$i<$count;$i++){
			$subject = strip_tags($info[$i]['content']);//去除html标签
			$pattern = '/\s/';//去除空白
			$content = preg_replace($pattern,'',$subject);  
			$abstract = mb_substr($content,0,60,'utf-8');//截取80个汉字
			$this->hope_entry_info->where('id='.$info[$i]['id'])->setField('abstract',$abstract);
			
			echo '<span style="color:red;font-size:20px;">'.$info[$i]['id'].'&nbsp;&nbsp;&nbsp;</span>'.$info[$i]['cname'].'更新完成<br/>';
		}
	}
	
	//疾病类型表中文转首音
	public function HanToPinyin(){
		$info = $this->hope_disease_type->select();
		// die();
		$count = count($info);
		for($i=0;$i<$count;$i++){
			$pid = '';
			$headName = mb_substr($info[$i]['name'],0,1,'utf-8');
			$pid = ChineseToPinyin($headName,3);
			$state = $this->hope_disease_type->where('id='.$info[$i]['id'])->setField('pid',$pid);
			if($state){
				echo $info[$i]['name'].'<p style="color:red;">已更新为</p>'.$pid.'<br/>';
			}
		}
	}
	
	//词条名称中文转拼音
	public function HanToPinyinS(){
		$info = $this->hope_entry->select();
		// die();
		$count = count($info);
		for($i=0;$i<$count;$i++){
			$pid = '';
			$headName = $info[$i]['cname'];
			$pids = str_replace(' ','',ChineseToPinyin($headName,2));
			if(strlen($pids) <= 10){
				$pid = $pids;
			}else{
				$pid = str_replace(' ','',ChineseToPinyin($headName,3));
			}
			// sleep(2);
			$state = $this->hope_entry->where('id='.$info[$i]['id'])->setField('pid',$pid);
			if($state){
				echo $info[$i]['cname'].'<p style="color:red;">已更新为</p>'.$pid.'<br/>';
			}
		}
	}
	
	
	public function distribution(){
		//将已上线、未分配的赋值
		$info = $this->hope_message->where("edit_num = 0 && sid != 1 &&  sid != 3 &&  sid != 18 &&  sid != 17 &&  sid != 16 &&  sid != 15 &&  sid != 14 &&  sid != 13 &&  sid != 12 &&  sid != 11 &&  sid != 10 &&  sid != 9")->field('id')->limit(0,200)->order('id desc')->select();
		dump($info);die();
		$count = count($info);
		if($count > 0){
			for($i=0;$i<$count;$i++){
				$this->hope_message->where('id ='.$info[$i]['id'])->setField('sid',19);
			}
		}
		//随机为5个账号分配未编辑资讯
		// $IdArr = $this->hope_message->where('sid is null')->field('id')->select();
		// dump($IdArr);
		// $UidArr = $this->user_adminrole->where('rid=7 && state=1')->select();
		// dump($IdArr);die();
		// $IdC = count($IdArr);
		// for($a=0;$a<$IdC;$a++){
			// if($a < 1177){
				// $this->hope_message->where('id ='.$IdArr[$a]['id'])->setField('sid',8);
			// }
		// }
	}
	
	//生成百度提交siteMap链接文件
	public function siteMAP(){
		$entryArr = $this->hope_entry->where('statecode=1 && state=1')->select();
		$messageArr = $this->hope_message->where('statecode=1 && state=1')->select();
		$questionArr = $this->hope_question->select();
		$Ecount = count($entryArr);
		$Mcount = count($messageArr);
		$Qcount = count($questionArr);
		for($e=0;$e<$Ecount;$e++){
			if($entryArr[$e]['entry_type_id'] == 1){
				$Etxt .= C('WEBURLS').'/jibing/'.$entryArr[$e]['pid']."\r\n";
			}else if($entryArr[$e]['entry_type_id'] == 2){
				$Etxt .= C('WEBURLS').'/yisheng/'.$entryArr[$e]['pid']."\r\n";
			}else if($entryArr[$e]['entry_type_id'] == 3){
				$Etxt .= C('WEBURLS').'/yiyuan/'.$entryArr[$e]['pid']."\r\n";
			}else if($entryArr[$e]['entry_type_id'] == 4){
				$Etxt .= C('WEBURLS').'/yaowu/'.$entryArr[$e]['pid']."\r\n";
			}
		}
		for($m=0;$m<$Mcount;$m++){
			$Mtxt .= C('NEWSURL').'/'.$messageArr[$m]['id'].".html\n";
		}
		for($q=0;$q<$Qcount;$q++){
			$Qtxt .= C('WENDA').'/Answer/'.$questionArr[$q]['id'].".html\n";
		}
		$WITHtxt = $Etxt.$Mtxt.$Qtxt;
		$name = date('Ymd-H-i',time());
		$state = file_put_contents($name.'.txt',$WITHtxt);
		if($state){
			echo $name.'.txt----文件写入完毕';
		}
	}
	
	//格式化所有资讯内容链接
	public function synaHref(){
		$message = $this->hope_message->field('id,content')->select();
		$count = count($message);
		for($m=0;$m<$count;$m++){
			$content = null;
			$content = ereg_replace("<a [^>]*>|<\/a>","",$message[$m]['content']);
			$this->hope_message->where('id='.$message[$m]['id'])->setField('content',$content);
			echo '第'.$message[$m]['id'].'条更新完毕<br/>';
		}
	}
	
	
	/*国外数据资源抓取入库程序
	*	type{
			1=>医院
			2=>医生
			3=>疾病
			4=>科室
			5=>案例
			6=>资讯
		}
	*
	*	status{
			1=>创建成功
			2=>缺少数据类型
			3=>缺少标题名称
			4=>缺少主体内容
			5=>创建数据失败
			6=>已更新该数据
		}
	*
	*/
	public function USAdataToDatabase(){
		$cname = I('cname','');
		$ename = I('ename','');
		$overview = I('overview','');
		$imgurl = I('imgurl','');
		$country = I('country','');
		$countact = I('countact','');
		$fromurl = I('fromurl','');
		$type = I('type','');
		$content = I('content','');
		if(empty($type)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少数据类型'
			);
			$this->ajaxReturn($output);
		}
		if(empty($cname)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少标题名称'
			);
			$this->ajaxReturn($output);
		}
		if(empty($content)){
			$output = array(
					'status' 	=>'4',
					'message'	=>'缺少主体内容'
			);
			$this->ajaxReturn($output);
		}
		$punctuation = array('!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', ',', ' ', '-', '?', '，', '。', '.', '（', '）', '·');
		$data['cname'] = str_replace($punctuation,'',$cname);
		$data['ename'] = $ename;
		$data['overview'] = $overview;
		$data['imgurl'] = $imgurl;
		$data['country'] = $country;
		$data['countact'] = $countact;
		$tempu=parse_url($fromurl);  
		$domainArr=explode('.',$tempu['host']); 
		$data['domain'] = $domainArr[1].'.'.$domainArr[2];
		$data['fromurl'] =$fromurl;
		$data['type'] = $type;
		$data['content'] = $content;
		$data['ctime'] = time();
		$data['statecode'] = 2;
		$name_status = $this->hope_temp_data->where('cname="'.$data['cname'].'"')->select();
		if($name_status){
			$this->hope_temp_data->where('id='.$name_status['id'])->create($data);
			$this->hope_temp_data->where('id='.$name_status['id'])->save();
			$output = array(
				'status' 	=>'6',
				'message'	=>'已更新该数据'
			);
			$this->ajaxReturn($output);
		}else{
			$state = $this->hope_temp_data->add($data);
			if($state){
				$output = array(
					'status' 	=>'1',
					'message'	=>'创建成功'
				);
				$this->ajaxReturn($output);
			}else{
				$output = array(
						'status' 	=>'5',
						'message'	=>'创建数据失败'
				);
				$this->ajaxReturn($output);
			}
		}
	}
	
	
	//读取美国站抓取数据
	public function usaDataClear(){
		$type = I('type','');
		$domain = I('domain','');
		if($type){
			$where['type'] = array('eq',$type);
		}
		if($domain){
			$where['domain'] = array('eq',$domain);
		}
		$countUSA = $this->hope_temp_data->where($where)->count();
		dump($countUSA);
		$HTML .= '<h1>'.$countUSA.'</h1>';
		$data = $this->hope_temp_data->where($where)->limit(0,3)->select();
		$count = count($data);
		for($i=0;$i<$count;$i++){
			$data[$i]['content'] = json_decode(htmlspecialchars_decode($data[$i]['content']),JSON_UNESCAPED_SLASHES);
			//输出json转换期间的错误提示
			// echo $errorinfo = json_last_error(); 
			//输出演示数据
			if($data[$i]['type'] == 1){
				$typeName = '医院';
			}else if($data[$i]['type'] == 2){
				$typeName = '医生';
			}else if($data[$i]['type'] == 3){
				$typeName = '疾病';
			}else if($data[$i]['type'] == 4){
				$typeName = '科室';
			}else if($data[$i]['type'] == 5){
				$typeName = '案例';
			}else if($data[$i]['type'] == 6){
				$typeName = '资讯';
			}else if($data[$i]['type'] == 7){
				$typeName = '临床实验';
			}else if($data[$i]['type'] == 8){
				$typeName = '治疗方案';
			}else if($data[$i]['type'] == 9){
				$typeName = '文章';
			}else if($data[$i]['type'] == 10){
				$typeName = '癌症对情感和身体的影响';
			}
			$HTML .= '<div style="width:200px;text-align:center;height:40px;line-height:40px;background:red;color:white;font-size:18px;font-weight:bold;">'.$typeName.'</div>';
			$HTML .= '<h1>'.$data[$i]['cname'].'</h1>';
			$HTML .= '<h4>'.$data[$i]['ename'].'</h4>';
			$HTML .= '<div><span style="color:red;">简介：</span>'.htmlspecialchars_decode($data[$i]['overview']).'</div>';
			if($data[$i]['imgurl']){
				//批量下载图片
				$newPath = null;
				// $newPath = $this->download($data[$i]['imgurl'],'./Public/Home/tempImg/');
				if($newPath['res']){
					$HTML .= '<div><span style="color:red;">封面图：</span><img src='.C('TEMPIMG').$newPath['newName'].' width="200px" height="170px;"></div>';
				}
			}
			if($data[$i]['country'] == 1){
				$HTML .= '<div> <span style="color:red;">国家：</span>中国</div>';
			}else if($data[$i]['country'] == 2){
				$HTML .= '<div> <span style="color:red;">国家：</span>美国</div>';
			}else if($data[$i]['country'] == 3){
				$HTML .= '<div> <span style="color:red;">国家：</span>日本</div>';
			}
			
			//相关联数据的输出
			if($data[$i]['type'] == 1){
				$typeName = '医院';
			}else if($data[$i]['type'] == 2){
				//医生
				//简介
				if(!$data[$i]['overview'] || empty($data[$i]['overview'])){
					$data[$i]['overview'] = $data[$i]['content']['bio'];
				}
				//描述
				$HTML .= '<div><span style="color:red;">描述</span>'.$data[$i]['content']['description'].'</div>';
				//相关信息
				$info = null;
				$info = $data[$i]['content']['info'];
				$iCount = count($info);
				$infoHtml = null;
				for($ii=0;$ii<$iCount;$ii++){
					$infoHtml .= $info[$ii]['title'].'：'.$info[$ii]['content'].'<br/>';
				}
				$HTML .= '<div><span style="color:red;">医生信息</span>'.$infoHtml.'</div>';
				//治的疾病
				$can_treat = null;
				$can_treat = $data[$i]['content']['can_treat'];
				$aCount = count($can_treat);
				$can_treatName = null;
				for($a=0;$a<$aCount;$a++){
					$can_treatName .= $can_treat[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">治疗疾病</span>'.$can_treatName.'</div>';
				//相关临床试验
				$clinical_trials = null;
				$clinical_trials = $data[$i]['content']['clinical_trials'];
				$aCount = count($clinical_trials);
				$clinical_trialsName = null;
				for($a=0;$a<$aCount;$a++){
					$clinical_trialsName .= $clinical_trials[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关临床试验</span>'.$clinical_trialsName.'</div>';
				
				
			}else if($data[$i]['type'] == 3){
				//疾病
				//简介
				if(!$data[$i]['overview'] || empty($data[$i]['overview'])){
					$data[$i]['overview'] = $data[$i]['content']['overview'];
				}
				//成因
				$HTML .= '<div><span style="color:red;">成因</span>'.$data[$i]['content']['factor'].'</div>';
				//症状
				$HTML .= '<div><span style="color:red;">症状</span>'.$data[$i]['content']['symptoms'].'</div>';
				//诊断
				$HTML .= '<div><span style="color:red;">诊断</span>'.$data[$i]['content']['diagnosis'].'</div>';
				//治疗
				$HTML .= '<div><span style="color:red;">治疗</span>'.$data[$i]['content']['treatment'].'</div>';
				//相关医院
				$centers = null;
				$centers = $data[$i]['content']['centers'];
				$aCount = count($centers);
				$centersName = null;
				for($a=0;$a<$aCount;$a++){
					$centersName .= $centers[$a]['name'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关医院</span>'.$centersName.'</div>';
				//相关医生
				$doctors = null;
				$doctors = $data[$i]['content']['doctors'];
				$aCount = count($doctors);
				$doctorsName = null;
				for($a=0;$a<$aCount;$a++){
					$doctorsName .= $doctors[$a]['name'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关医生</span>'.$doctorsName.'</div>';
				//相关案例
				$case = null;
				$case = $data[$i]['content']['case'];
				$aCount = count($case);
				$caseName = null;
				for($a=0;$a<$aCount;$a++){
					$caseName .= $case[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关案例</span>'.$caseName.'</div>';
				//相关临床试验
				$clinical_trials = null;
				$clinical_trials = $data[$i]['content']['clinical_trials'];
				$aCount = count($clinical_trials);
				$clinical_trialsName = null;
				for($a=0;$a<$aCount;$a++){
					$clinical_trialsName .= $clinical_trials[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关临床试验</span>'.$clinical_trialsName.'</div>';
				
			}else if($data[$i]['type'] == 4){
				//科室
				//治的疾病
				$can_treat = null;
				$can_treat = $data[$i]['content']['can_treat'];
				$aCount = count($can_treat);
				$can_treatName = null;
				for($a=0;$a<$aCount;$a++){
					$can_treatName .= $can_treat[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">治疗疾病</span>'.$can_treatName.'</div>';
				//联系方式
				if(!$data[$i]['countact'] || empty($data[$i]['countact'])){
					$data[$i]['countact'] = $data[$i]['content']['contact'];
				}
				//相关图片
				$photos = null;
				$photos = $data[$i]['content']['photos'];
				$pCount = count($photos);
				$pImg = null;
				if($pCount){
					for($p=0;$p<$pCount;$p++){
						//批量下载图片
						$newPath = null;
						// $newPath = $this->download($photos[$p],'./Public/Home/tempImg/');
						if($newPath['res']){
							$pImg .= '<img src="'.C('TEMPIMG').$newPath['newName'].'" style="margin-right:20px;width:150px;height:120px;">';	
						}
					}
				}
				$HTML .= '<div><span style="color:red;">相关图片</span>'.$pImg.'</div>';
				//相关医生
				$doctors = null;
				$doctors = $data[$i]['content']['doctors'];
				$aCount = count($doctors);
				$doctorsName = null;
				for($a=0;$a<$aCount;$a++){
					$doctorsName .= $doctors[$a]['name'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关医生</span>'.$doctorsName.'</div>';
				//相关案例
				$case = null;
				$case = $data[$i]['content']['case'];
				$aCount = count($case);
				$caseName = null;
				for($a=0;$a<$aCount;$a++){
					$caseName .= $case[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关案例</span>'.$caseName.'</div>';
				//相关临床试验
				$clinical_trials = null;
				$clinical_trials = $data[$i]['content']['clinical_trials'];
				$aCount = count($clinical_trials);
				$clinical_trialsName = null;
				for($a=0;$a<$aCount;$a++){
					$clinical_trialsName .= $clinical_trials[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关临床试验</span>'.$clinical_trialsName.'</div>';
				//相关新闻
				$news = null;
				$news = $data[$i]['content']['news'];
				$aCount = count($news);
				$newsName = null;
				for($a=0;$a<$aCount;$a++){
					$newsName .= $news[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关新闻</span>'.$newsName.'</div>';
				//其他信息
				$resources = null;
				$resources = $data[$i]['content']['resources'];
				$aCount = count($resources);
				$resourcesName = null;
				for($a=0;$a<$aCount;$a++){
					$resourcesName .= $resources[$a]['text'].'，';
				}
				$HTML .= '<div><span style="color:red;">其他信息</span>'.$resourcesName.'</div>';
			}else if($data[$i]['type'] == 5){
				//案例
				//日期
				$HTML .= '<div><span style="color:red;">日期</span>'.$data[$i]['content']['date'].'</div>';
				//作者
				$HTML .= '<div><span style="color:red;">作者</span>'.$data[$i]['content']['author'].'</div>';
				//内容
				$HTML .= '<div><span style="color:red;">内容</span>'.$data[$i]['content']['content'].'</div>';
				//相关标签
				$tags = null;
				$tags = $data[$i]['content']['tags'];
				$aCount = count($tags);
				$tagsName = null;
				for($a=0;$a<$aCount;$a++){
					$tagsName .= $tags[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关标签</span>'.$tagsName.'</div>';
				//评论信息
				$comments = null;
				$comments = $data[$i]['content']['comments'];
				$iCount = count($comments);
				$infoHtml = null;
				for($ii=0;$ii<$iCount;$ii++){
					$infoHtml .= $comments[$ii]['author'].'：'.$comments[$ii]['content'].'<br/>';
				}
				$HTML .= '<div><span style="color:red;">评论信息</span>'.$infoHtml.'</div>';
				
			}else if($data[$i]['type'] == 6){
				$typeName = '资讯';
			}else if($data[$i]['type'] == 7){
				//临床实验
				//副标题
				$HTML .= '<div><span style="color:red;">副标题</span>'.$data[$i]['content']['sub_title'].'</div>';
				//调查者
				$HTML .= '<div><span style="color:red;">调查者</span>'.$data[$i]['content']['investigator'].'</div>';
				//阶段
				$HTML .= '<div><span style="color:red;">阶段</span>'.$data[$i]['content']['phase'].'</div>';
				//概要信息
				$HTML .= '<div><span style="color:red;">概要信息</span>'.$data[$i]['content']['summary'].'</div>';
				//参与条件
				$HTML .= '<div><span style="color:red;">参与条件</span>'.$data[$i]['content']['eligibility'].'</div>';
				//相关信息
				$info = null;
				$info = $data[$i]['content']['info'];
				$iCount = count($info);
				$infoHtml = null;
				for($ii=0;$ii<$iCount;$ii++){
					$infoHtml .= $info[$ii]['title'].'：'.$info[$ii]['content'].'<br/>';
				}
				$HTML .= '<div><span style="color:red;">相关信息</span>'.$infoHtml.'</div>';
				//相关医生
				$doctors = null;
				$doctors = $data[$i]['content']['doctors'];
				$aCount = count($doctors);
				$doctorsName = null;
				for($a=0;$a<$aCount;$a++){
					$doctorsName .= $doctors[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关医生</span>'.$doctorsName.'</div>';
				//相关疾病
				$diseases = null;
				$diseases = $data[$i]['content']['diseases'];
				$aCount = count($diseases);
				$diseasesName = null;
				for($a=0;$a<$aCount;$a++){
					$diseasesName .= $diseases[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关疾病</span>'.$diseasesName.'</div>';
				
			}else if($data[$i]['type'] == 8){
				//治疗方案
				//内容
				$HTML .= '<div><span style="color:red;">相关内容</span>'.$data[$i]['content']['content'].'</div>';
				//相关图片
				// $newPath = $this->download($photos[$p],'./Public/Home/tempImg/');
				$HTML .= '<div><span style="color:red;">相关图片</span><img src="'.C('TEMPIMG').$newPath['newName'].'" style="margin-right:20px;width:150px;height:120px;"></div>';
			}else if($data[$i]['type'] == 9){
				//相关图片
				// $newPath = $this->download($photos[$p],'./Public/Home/tempImg/');
				$HTML .= '<div><span style="color:red;">相关图片</span><img src="'.C('TEMPIMG').$newPath['newName'].'" style="margin-right:20px;width:150px;height:120px;"></div>';
				//内容
				$HTML .= '<div><span style="color:red;">年报</span>'.$data[$i]['content']['date'].'</div>';
				//作者
				$HTML .= '<div><span style="color:red;">作者</span>'.$data[$i]['content']['author'].'</div>';
				//内容
				$HTML .= '<div><span style="color:red;">相关内容</span>'.$data[$i]['content']['content'].'</div>';
				//相关疾病
				$cancer = null;
				$cancer = $data[$i]['content']['cancer'];
				$aCount = count($cancer);
				$cancerName = null;
				for($a=0;$a<$aCount;$a++){
					$cancerName .= $cancer[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关疾病</span>'.$cancerName.'</div>';
				//相关话题
				$topics = null;
				$topics = $data[$i]['content']['topics'];
				$aCount = count($topics);
				$topicsName = null;
				for($a=0;$a<$aCount;$a++){
					$topicsName .= $topics[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关疾病</span>'.$topicsName.'</div>';
				//相关标签
				$tags = null;
				$tags = $data[$i]['content']['tags'];
				$aCount = count($tags);
				$tagsName = null;
				for($a=0;$a<$aCount;$a++){
					$tagsName .= $tags[$a]['title'].'，';
				}
				$HTML .= '<div><span style="color:red;">相关标签</span>'.$tagsName.'</div>';
				//评论信息
				$comments = null;
				$comments = $data[$i]['content']['comments'];
				$iCount = count($comments);
				$infoHtml = null;
				for($ii=0;$ii<$iCount;$ii++){
					$infoHtml .= $comments[$ii]['author'].'：'.$comments[$ii]['content'].'<br/>';
				}
				$HTML .= '<div><span style="color:red;">评论信息</span>'.$infoHtml.'</div>';
				
			}else if($data[$i]['type'] == 10){
				//内容
				$HTML .= '<div><span style="color:red;">相关内容</span>'.$data[$i]['content']['content'].'</div>';
				
			}
			$HTML .= '<div><span style="color:red;">联系方式：</span>'.$data[$i]['countact'].'</div><br/><br/><br/>';
			$HTML .= '<div style="width:100%;border:2px solid red;"></div>';
		}
		echo ereg_replace("<a [^>]*>|<\/a>","",$HTML);
	}
	
	/**
    *   下载图片方法
    *
    */
    public function download($url,$path='./Public/Home/images/'){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		$file = curl_exec($ch);
		curl_close($ch);
		$filename = pathinfo($url, PATHINFO_BASENAME);
		//生成新名称
		$newName = 'hopenoah'.md5(microtime()).'.jpg';
		$resource = fopen($path.$newName, 'a');
		fwrite($resource, $file);
		$res0 = fclose($resource);
		return array('res'=>$res0,'newName'=>$newName);
    }
	
	//批量摘除域名
	public function clearDomainFunc(){
		// $Url='http://www.dana-farber.org/merkel-cell-carcinoma/'; 
		// $tempu=parse_url($Url);  
		// $domainArr=explode('.',$tempu['host']); 
		// $domain = $domainArr[1].'.'.$domainArr[2];
		// dump($domain);die;
		
		$data = $this->hope_temp_data->where('domain is null')->field('id,fromurl')->select();
		// dump($data);die;
		$count = count($data);
		for($i=0;$i<$count;$i++){
			$Url=$data[$i]['fromurl'];  
			$tempu=parse_url($Url);  
			$domainArr=explode('.',$tempu['host']); 
			$domain = $domainArr[1].'.'.$domainArr[2];
			$state = $this->hope_temp_data->where('id='.$data[$i]['id'])->setField('domain',$domain);
			if($state){
				echo '第'.$data[$i]['id'].'条更新成功'.$domain.'<br/>';
			}
		}
		
	}
	
	
	//正则测试
	public function zhengzeTest(){
		$host = $_SERVER['HTTP_HOST'];
		$request_uri = $_SERVER['REQUEST_URI'];
		dump($host);
		die;
		$x="<strong>11,08,03,10,07</strong><td>测试</td><span>测试</span><strong>1108031007</strong>";
		// preg_match_all("/<strong>(.|\n)*?<\/strong>/m",$x,$match); 
		preg_match_all("/<strong>(.*?)<\/strong>/m",$x,$match);
		dump($match);
		
		//**************************正则匹配并替换图片**************************//
		//去除文章中的a标签
		$v1->content = preg_replace("/<a[^>]*>(.*?)<\/a>/is", "$1", $v1->content);
		//匹配文章中的img标签
		preg_match_all('/<img.*?src="(.*?)".*?>/is',$v1->content,$img);
		//判断是否添加缩略图变量 0未添加 1添加
		$ifadd = 0;
		//下载图片到本地
		foreach($img[0] as $k2=>$v2){
			//调用下载图片方法
			$res0 = $this->download($img[1][$k2]);
			//判断图片是否下载成功
			if($res0['res']){
				 //img地址
				$imgadd = $request->get('url').$res0['newName'];
				//判断是否为img字段添加链接
				if($ifadd == 0){
					$res1 = DB::table('hopenoah0421_information')->where('id','=',$v1->id)->update(['img'=>$imgadd]);
					if($res1){
						 $ifadd = 1;
					}
				}
				//更换文章中的img地址
			   $v1->content = str_replace($img[1][$k2],$imgadd,$v1->content);
			}else{                  
				//删除文章中下载未成功的img标签
				$v1->content = str_replace($v2," ",$v1->content);
			}
		}
	}
	
	//导出词条资讯审核清单列表
	public function messsageListFunc(){
		$mUidArr = [4,5,9,10,11,12,13,14,15,16,17,19,32];
		$eUidArr = [31];
		$mc = count($mUidArr);
		echo '<div style="position:fixed;right:100px;"><h1><a href="#message" name="message">资讯</a></h1><h1><a href="#entry" name="entry">词条</a></h1></div>';
		echo '<div id="message" style="cursor:pointer;">资讯</div>';
		for($m=0;$m<$mc;$m++){
			$uMessageArr = null;
			$uMessageArr = $this->hope_message->where('sid='.$mUidArr[$m].'&& statecode=1 && state = 1')->select();
			$mmc = count($uMessageArr);
			for($mms=0;$mms<$mmc;$mms++){
				$uname = $this->user->where('uid='.$mUidArr[$m])->getField('tname');
				echo '<a href="http://news.superdoctor.cn/'.$uMessageArr[$mms]['id'].'" target="_blank">'.$uMessageArr[$mms]['cname'].'</a><-----><span style="color:red;">'.$uname.'</span><br/>';
				echo 'news.superdoctor.cn/'.$uMessageArr[$mms]['id'].'<br/>';
			}
		}
		
		echo '<div id="entry" style="cursor:pointer;">词条</div>';
		$ec = count($eUidArr);
		for($e=0;$e<$ec;$e++){
			$uEntryArr = null;
			$uEntryArr = $this->hope_entry->where('sid='.$eUidArr[$e].'&& statecode=1 && state = 1')->select();
			$eec = count($uEntryArr);
			for($ees=0;$ees<$eec;$ees++){
				$uname = $this->user->where('uid='.$eUidArr[$e])->getField('tname');
				echo '<a href="http://superdoctor.cn/Entry/detail/id/'.$uEntryArr[$ees]['pid'].'" target="_blank">'.$uEntryArr[$ees]['cname'].'</a><-----><span style="color:red;">'.$uname.'</span><br/>';
				echo 'http://superdoctor.cn/Entry/detail/id/'.$uEntryArr[$ees]['pid'].'<br/>';
			}
		}
	}
	
	
	
	//读取美国站抓取数据
	public function clearUsaData(){
		// 发送一个 24 小时候过期的 cookie
		// setcookie("hopenoahStart",7457,time()+3600*24*300);
		// setcookie("hopenoahErrorNum",0,time()+3600*24*300);
		// setcookie("hopenoahError",0,time()+3600*24*300);
		$this->display('clearUsaData');
	}
	public function usaDataClearFunc(){
		set_time_limit(0);
		$start = intval(I('num',0));
		$data = $this->hope_temp_data->where('id='.$start)->select();
		$count = count($data);
		if($start == 9000){
			$output = array(
				'status' 	=>'4',
				'message'	=>'数据过滤完毕'
			);
			$this->ajaxReturn($output);
		}else{
			for($i=0;$i<$count;$i++){
				$hopeList = null;
				$data[$i]['content'] = json_decode(htmlspecialchars_decode($data[$i]['content']),JSON_UNESCAPED_SLASHES);
				//输出json转换期间的错误提示
				// echo $errorinfo = json_last_error(); 
				//输出演示数据
				if($data[$i]['type'] == 1){
					$typeName = '医院';
				}else if($data[$i]['type'] == 2){
					$typeName = '医生';
				}else if($data[$i]['type'] == 3){
					$typeName = '疾病';
				}else if($data[$i]['type'] == 4){
					$typeName = '科室';
				}else if($data[$i]['type'] == 5){
					$typeName = '案例';
				}else if($data[$i]['type'] == 6){
					$typeName = '资讯';
				}else if($data[$i]['type'] == 7){
					$typeName = '临床实验';
				}else if($data[$i]['type'] == 8){
					$typeName = '治疗方案';
				}else if($data[$i]['type'] == 9){
					$typeName = '文章';
				}else if($data[$i]['type'] == 10){
					$typeName = '癌症对情感和身体的影响';
				}
				$hopeList['cname'] = $data[$i]['cname'];
				$hopeList['ename'] = $data[$i]['ename'];
				if($data[$i]['imgurl']){
					//批量下载图片
					$newPath = null;
					$newPath = $this->download($data[$i]['imgurl'],'./Public/Home/tempImg/');
					if($newPath['res']){
						$uploads['url'] = $newPath['newName'];
						$uploads['uptime'] = time();
						$fileID = $this->hope_uploads->add($uploads);
					}
				}
				if(!$fileID){
					$fileID = 0;
				}
				if($data[$i]['type'] == 2 || $data[$i]['type'] == 3){
					$headName = $data[$i]['cname'];;
					$pids = str_replace(' ','',ChineseToPinyin($headName,2));
					if(strlen($pids) <= 10){
						$pid = $pids;
					}else{
						$pid = str_replace(' ','',ChineseToPinyin($headName,3));
					}
					$hopeList['pid'] = $pid;
					$hopeListInfo['abstract'] = htmlspecialchars_decode($data[$i]['overview']);
					$hopeListInfo['file_id'] = $fileID;
					$hopeListInfo['uid'] = 1;
					$hopeListInfo['country_id'] = $data[$i]['country'];
					$hopeListInfo['ctime'] = time();
					$hopeListInfo['statecode'] = 1;
					$hopeListInfo['state'] = 1;
				}else{
					$hopeList['file_id'] = $fileID;
				}
				$hopeList['uid'] = 1;
				$hopeList['sys_type'] = 2;
				$hopeList['ctime'] = time();
				$hopeList['stime'] = 1520287566;
				$hopeList['statecode'] = 1;
				$hopeList['state'] = 1;
				$hopeList['fromID'] = $data[$i]['type'];
				//相关联数据的输出
				if($data[$i]['type'] == 1){
					$typeName = '医院';
				}else if($data[$i]['type'] == 2){
					$hopeListInfo['entry_type_id'] = 2;
					//医生
					//简介
					if(!$data[$i]['overview'] || empty($data[$i]['overview'])){
						$hopeListInfo['abstract'] = $data[$i]['content']['bio'];
					}
					//描述
					$HTML .= '<div><span style="color:red;">描述</span>'.$data[$i]['content']['description'].'</div>';
					//医生信息
					$info = null;
					$info = $data[$i]['content']['info'];
					$iCount = count($info);
					$infoHtml = null;
					for($ii=0;$ii<$iCount;$ii++){
						$infoHtml .= $info[$ii]['title'].'：'.$info[$ii]['content'].'<br/>';
					}
					$hopeListInfo['content'] = $infoHtml;
				}else if($data[$i]['type'] == 3){
					$hopeListInfo['entry_type_id'] = 1;
					//疾病
					//简介
					if(!$data[$i]['overview'] || empty($data[$i]['overview'])){
						$hopeListInfo['abstract'] = $data[$i]['content']['overview'];
					}
					//成因
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">成因</span>'.$data[$i]['content']['factor'].'</div>';
					//症状
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">症状</span>'.$data[$i]['content']['symptoms'].'</div>';
					//诊断
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">诊断</span>'.$data[$i]['content']['diagnosis'].'</div>';
					//治疗
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">治疗</span>'.$data[$i]['content']['treatment'].'</div>';
					$hopeListInfo['content'] = $HTML;
				}else if($data[$i]['type'] == 4){
					//科室
					//治的疾病
					$can_treat = null;
					$can_treat = $data[$i]['content']['can_treat'];
					$aCount = count($can_treat);
					$can_treatName = null;
					for($a=0;$a<$aCount;$a++){
						$can_treatName .= $can_treat[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">治疗疾病</span>'.$can_treatName.'</div>';
					//联系方式
					if(!$data[$i]['countact'] || empty($data[$i]['countact'])){
						$data[$i]['countact'] = $data[$i]['content']['contact'];
					}
					//相关图片
					$photos = null;
					$photos = $data[$i]['content']['photos'];
					$pCount = count($photos);
					$pImg = null;
					if($pCount){
						for($p=0;$p<$pCount;$p++){
							//批量下载图片
							$newPath = null;
							$newPath = $this->download($photos[$p],'./Public/Home/tempImg/');
							if($newPath['res']){
								$pImg .= '<img src="'.C('TEMPIMG').$newPath['newName'].'" style="margin-right:20px;width:280px;height:220px;">';	
							}
						}
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关图片</span>'.$pImg.'</div>';
					//相关医生
					$doctors = null;
					$doctors = $data[$i]['content']['doctors'];
					$aCount = count($doctors);
					$doctorsName = null;
					for($a=0;$a<$aCount;$a++){
						$doctorsName .= $doctors[$a]['name'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关医生</span>'.$doctorsName.'</div>';
					//相关案例
					$case = null;
					$case = $data[$i]['content']['case'];
					$aCount = count($case);
					$caseName = null;
					for($a=0;$a<$aCount;$a++){
						$caseName .= $case[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关案例</span>'.$caseName.'</div>';
					//相关临床试验
					$clinical_trials = null;
					$clinical_trials = $data[$i]['content']['clinical_trials'];
					$aCount = count($clinical_trials);
					$clinical_trialsName = null;
					for($a=0;$a<$aCount;$a++){
						$clinical_trialsName .= $clinical_trials[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关临床试验</span>'.$clinical_trialsName.'</div>';
					//相关新闻
					$news = null;
					$news = $data[$i]['content']['news'];
					$aCount = count($news);
					$newsName = null;
					for($a=0;$a<$aCount;$a++){
						$newsName .= $news[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关新闻</span>'.$newsName.'</div>';
					//其他信息
					$resources = null;
					$resources = $data[$i]['content']['resources'];
					$aCount = count($resources);
					$resourcesName = null;
					for($a=0;$a<$aCount;$a++){
						$resourcesName .= $resources[$a]['text'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">其他信息</span>'.$resourcesName.'</div>';
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">联系方式：</span>'.$data[$i]['countact'].'</div>';
					$hopeList['content'] = $HTML;
				}else if($data[$i]['type'] == 5){
					//案例
					//日期
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">日期</span>'.$data[$i]['content']['date'].'</div>';
					//作者
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">作者</span>'.$data[$i]['content']['author'].'</div>';
					//内容
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">内容</span>'.$data[$i]['content']['content'].'</div>';
					//相关标签
					$tags = null;
					$tags = $data[$i]['content']['tags'];
					$aCount = count($tags);
					$tagsName = null;
					for($a=0;$a<$aCount;$a++){
						$tagsName .= $tags[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关标签</span>'.$tagsName.'</div>';
					//评论信息
					$comments = null;
					$comments = $data[$i]['content']['comments'];
					$iCount = count($comments);
					$infoHtml = null;
					for($ii=0;$ii<$iCount;$ii++){
						$infoHtml .= $comments[$ii]['author'].'：'.$comments[$ii]['content'].'<br/>';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">评论信息</span>'.$infoHtml.'</div>';
					$hopeList['content'] = $HTML;
				}else if($data[$i]['type'] == 6){
					$typeName = '资讯';
				}else if($data[$i]['type'] == 7){
					//临床实验
					//副标题
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">副标题</span>'.$data[$i]['content']['sub_title'].'</div>';
					//调查者
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">调查者</span>'.$data[$i]['content']['investigator'].'</div>';
					//阶段
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">阶段</span>'.$data[$i]['content']['phase'].'</div>';
					//概要信息
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">概要信息</span>'.$data[$i]['content']['summary'].'</div>';
					//参与条件
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">参与条件</span>'.$data[$i]['content']['eligibility'].'</div>';
					//相关信息
					$info = null;
					$info = $data[$i]['content']['info'];
					$iCount = count($info);
					$infoHtml = null;
					for($ii=0;$ii<$iCount;$ii++){
						$infoHtml .= $info[$ii]['title'].'：'.$info[$ii]['content'].'<br/>';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关信息</span>'.$infoHtml.'</div>';
					//相关医生
					$doctors = null;
					$doctors = $data[$i]['content']['doctors'];
					$aCount = count($doctors);
					$doctorsName = null;
					for($a=0;$a<$aCount;$a++){
						$doctorsName .= $doctors[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关医生</span>'.$doctorsName.'</div>';
					//相关疾病
					$diseases = null;
					$diseases = $data[$i]['content']['diseases'];
					$aCount = count($diseases);
					$diseasesName = null;
					for($a=0;$a<$aCount;$a++){
						$diseasesName .= $diseases[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关疾病</span>'.$diseasesName.'</div>';
					$hopeList['content'] = $HTML;
					
				}else if($data[$i]['type'] == 8){
					//治疗方案
					//内容
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关内容</span>'.$data[$i]['content']['content'].'</div>';
					//相关图片
					$newPath = $this->download($photos[$p],'./Public/Home/tempImg/');
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关图片</span><img src="'.C('TEMPIMG').$newPath['newName'].'" style="margin-right:20px;width:150px;height:120px;"></div>';
					$hopeList['content'] = $HTML;
				}else if($data[$i]['type'] == 9){
					//相关图片
					$newPath = $this->download($photos[$p],'./Public/Home/tempImg/');
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关图片</span><img src="'.C('TEMPIMG').$newPath['newName'].'" style="margin-right:20px;width:150px;height:120px;"></div>';
					//内容
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">年报</span>'.$data[$i]['content']['date'].'</div>';
					//作者
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">作者</span>'.$data[$i]['content']['author'].'</div>';
					//内容
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关内容</span>'.$data[$i]['content']['content'].'</div>';
					//相关疾病
					$cancer = null;
					$cancer = $data[$i]['content']['cancer'];
					$aCount = count($cancer);
					$cancerName = null;
					for($a=0;$a<$aCount;$a++){
						$cancerName .= $cancer[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关疾病</span>'.$cancerName.'</div>';
					//相关话题
					$topics = null;
					$topics = $data[$i]['content']['topics'];
					$aCount = count($topics);
					$topicsName = null;
					for($a=0;$a<$aCount;$a++){
						$topicsName .= $topics[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关疾病</span>'.$topicsName.'</div>';
					//相关标签
					$tags = null;
					$tags = $data[$i]['content']['tags'];
					$aCount = count($tags);
					$tagsName = null;
					for($a=0;$a<$aCount;$a++){
						$tagsName .= $tags[$a]['title'].'，';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关标签</span>'.$tagsName.'</div>';
					//评论信息
					$comments = null;
					$comments = $data[$i]['content']['comments'];
					$iCount = count($comments);
					$infoHtml = null;
					for($ii=0;$ii<$iCount;$ii++){
						$infoHtml .= $comments[$ii]['author'].'：'.$comments[$ii]['content'].'<br/>';
					}
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">评论信息</span>'.$infoHtml.'</div>';
					$hopeList['content'] = $HTML;
					
				}else if($data[$i]['type'] == 10){
					//内容
					$HTML .= '<div><span style="font-size:16px;font-weight:bold;">相关内容</span>'.$data[$i]['content']['content'].'</div>';
					$hopeList['content'] = $HTML;
					
				}
				// 发送一个 24 小时候过期的 cookie
				setcookie("hopenoahStart",$data[$i]['id'],time()+3600*24*300);
				// dump($_COOKIE['hopenoahStart']);
				// dump($data[$i]['id']);
				// die;
				//保存数据
				if($data[$i]['type'] == 2 || $data[$i]['type'] == 3){
					$entry_id = $this->hope_entry->add($hopeList);
					if($entry_id){
						$hopeListInfo['entry_id'] = $entry_id;
						$state = $this->hope_entry_info->add($hopeListInfo);
						if($state){
							//更新数据为已过滤
							$this->hope_temp_data->where('id='.$data[$i]['id'])->setField('statecode',1);
							$output = array(
								'status' => '1',
								'startID' => $data[$i]['id'],
								'message' => '数据添加成功'
							);
							$this->ajaxReturn($output);
						}else{
							$output = array(
								'status' 	=>'2',
								'startID' => $data[$i]['id'],
								'message'	=>'数据添加失败'
							);
							$this->ajaxReturn($output);
						}
					}
				}else{
					$state = $this->hope_message->add($hopeList);
					if($state){
						//更新数据为已过滤
						$this->hope_temp_data->where('id='.$data[$i]['id'])->setField('statecode',1);
						$output = array(
							'status' => '1',
							'startID' => $data[$i]['id'],
							'message' => '数据添加成功'
						);
						$this->ajaxReturn($output);
					}else{
						$output = array(
							'status' 	=>'3',
							'startID' => $data[$i]['id'],
							'message'	=>'数据添加失败'
						);
						$this->ajaxReturn($output);
					}
				}
			}
		}
	}
	//查找重复疾病
	public function familiarFunc(){
		$str = '唇癌dfgfhgffgh舌癌fdgdfgdfg牙龈癌dfgdfgd口底癌gjghjgj下颚癌ereyghgh口腔其他癌';
		
		//判断字符是否包含中文
		$matchStr = preg_match('/[\x{4e00}-\x{9fa5}]/u',$str);
		if($matchStr){
			$newStr = ChineseToPinyin($str,4);
		}
		dump($newStr);
		die;
		$disease = '唇癌,舌癌,牙龈癌,口底癌,下颚癌,口腔其他癌,腮腺癌,唾液腺癌,扁桃体癌,喉癌,鼻咽癌,梨状窝癌,食道癌,胃癌,小肠癌,结肠癌,直肠癌,大肠癌,肛门癌,肝癌,胆癌,贲门癌,胃肠道基质肿瘤,胰腺癌,耳癌,副窦的恶性肿瘤,气管癌,肺癌,胸腺癌,胸膜癌,骨癌,多发性骨髓瘤,黑色素瘤,其他皮肤癌,间皮瘤,卡波西氏肉瘤,腹膜癌,乳腺癌,外阴癌,阴道癌,宫颈癌,子宫癌,卵巢癌,胎盘癌,阴茎癌,摄护腺癌,睾丸癌,前列腺癌,肾癌,输尿管癌,膀胱癌,眼癌,视网膜母细胞瘤,脑瘤,甲状腺癌,肾上腺癌,多发性骨髓瘤和,性浆细胞肿瘤,淋巴瘤,白血病,免疫增生病';
		$diseaseArr = explode(',',$disease);
		$count = count($diseaseArr);
		for($i=0;$i<$count;$i++){
			$state = $this->hope_disease_type->where('name="'.$diseaseArr[$i].'"')->find();
			if($state){
				$newDiseaseOne .= $diseaseArr[$i].'<br/>';
			}else{
				$newDiseaseTwo .= '<span style="color:red;">'.$diseaseArr[$i].'</span><br/>';
			}
		}
		echo $newDiseaseOne;
		echo $newDiseaseTwo;
	}
	
	
	
	/**
	 * 发送短信
	 */
	// public function sendSms($phone,$entry,$message){
	public function sendSms(){
		// $t = Vendor('Sms.SignatureHelper');
		import('ORG.Util.SignatureHelper');
		$helper = new \Org\Util\SignatureHelper();

		dump($helper);die;
		$params = array ();

		// *** 需用户填写部分 ***

		// fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
		$accessKeyId = "your access key id";
		$accessKeySecret = "your access key secret";

		// fixme 必填: 短信接收号码
		$params["PhoneNumbers"] = $phone;

		// fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
		$params["SignName"] = "厚朴诺亚toptpa";

		// fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
		$params["TemplateCode"] = "SMS_136385280";

		// fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
		$params['TemplateParam'] = Array (
			"entry" => $entry,
            "message" => $message
		);

		// *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
		if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
			$params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
		}

		// 初始化SignatureHelper实例用于设置参数，签名以及发送请求
		$helper = new \Org\Util\SignatureHelper();

		dump($helper);die;
		// 此处可能会抛出异常，注意catch
		$content = $helper->request(
			$accessKeyId,
			$accessKeySecret,
			"dysmsapi.aliyuncs.com",
			array_merge($params, array(
				"RegionId" => "cn-hangzhou",
				"Action" => "SendSms",
				"Version" => "2017-05-25",
			))
		);
		return $content;
	}
	
	public function aaa(){
		$hopeList = '大家对于癌症是恐惧的，认为<a href="">234@qq.com</a>必肝癌死<a href="">http://www.super.cn</a>无疑，';
		echo $hopeList.'<br/>';
		$newcontent = ereg_replace("<a [^>]*>[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)<\/a>|<a [^>]*>((https|http|ftp|rtsp|mms)?:\/\/)[^\s]<\/a>","",$hopeList);
		echo $newcontent;die; 
		$a = keyWordsHref(2,'相信大家对于癌症是恐惧的，认为得了癌症就必肝癌死无疑，直接放弃肝癌抵抗，直接宣判自己的死刑。我们都知道治疗癌症不仅依靠药物和医生，还需要患者本身的心态和生活规律，有利于癌症的治疗。下面讲的这篇抗癌日记讲述的是一名患有转移瘤癌患者的抗癌日记。不知道会不会对与病魔斗争的其他肺癌患者是否有帮助呢？和有什么做法是值得学肝癌习和借鉴的。下面我们深度肺癌了解一下吧。','我们的故事');
		dump($a);
		echo $a['content'].'<br/>';
		echo $a['title'];
	}
	
	public function infoWeightFunc(){
		
	}
}