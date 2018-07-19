<?php
namespace Admin\Controller;
use Think\Controller;
use Home\Model\UploadFileModel as UploadFile;
use Home\Model\LogFileModel as LogFile;
// +----------------------------------------------------------------------
// | 超级医生WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://hopenoah.com All rights reserved.
// +----------------------------------------------------------------------
// | 公共控制器
// +----------------------------------------------------------------------
// | Author: Davin <yulong@hopenoah.com>
// +----------------------------------------------------------------------
class CommonController extends Controller {

	private $Verifys;//验证码类
	private $citys;//城市表
	private $hope_disease_type;	//实例化疾病类型管理表
	private $hope_link;	//实例化疾病联系管理表
	private $hope_entry;	//实例化疾病词条管理表
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		
		//实例化验证码类
		$this->Verifys = new \Think\Verify();
		//实例化城市表
		$this->citys     = D('citys');
		//实例化疾病类型管理表
		$this->hope_disease_type = D('hope_disease_type');
		//实例化疾病联系管理表
		$this->hope_link = D('hope_link');
		//实例化疾病词条管理表
		$this->hope_entry = D('hope_entry');
		//实例化缓存类
		$this->redis=S(array());
	}
	
	
	
	/*
	*疾病类型
	*接收数据格式：	'type'=>查询数据类型
					{
						1=>从一级开始查询
						2=>从二级开始查询
					}
					'fid'=>疾病父级ID
					'level'=>待查询子类级别
					{
						1=>父级
						2=>子集
					}
	*返回数据格式
					{
						'status'=>状态信息
						'message'=>提示信息
					}
	status = {
		1：查询成功
		2：缺少疾病父级ID
		3：查询疾病级别格式错误
		4：未查询到疾病类型有效数据
	}
	*/
	public function DiseaseList(){
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); 
		$fid =I('fid',''); 
		$pid =I('pid',''); 
		$mid =I('mid',''); 
		$level =I('level',''); 
		$type =I('type',''); 
		$ptype =I('ptype',''); 
		if(empty($type)){
			$output = array(
					'status' 	=>'5',
					'message'	=>'缺少查询类型码'
			);
			$this->ajaxReturn($output);
		}
		if(empty($level) || $level>3){
			$output = array(
					'status' 	=>'3',
					'message'	=>'查询疾病级别格式错误'
			);
			$this->ajaxReturn($output);
		}
		if($type == 1){
			$data = $this->hope_disease_type->where('level='.$level.'&& state =1')->select();
		}else if($type == 2){
			if(empty($fid)){
				$output = array(
						'status' 	=>'2',
						'message'	=>'缺少疾病父级ID'
				);
				$this->ajaxReturn($output);
			}
			$data = $this->hope_disease_type->where('level='.$level.'&& fid='.$fid.'&& state =1')->select();
		}else if($type == 3){
			if(empty($pid)){
				$output = array(
						'status' 	=>'6',
						'message'	=>'缺少疾病首拼ID'
				);
				$this->ajaxReturn($output);
			}
			$data = $this->hope_disease_type->where('level='.$level.'&& pid="'.$pid.'"&& state =1')->select();
		}else if($type == 4){
			if(empty($pid)){
				$output = array(
						'status' 	=>'7',
						'message'	=>'缺少疾病首拼ID'
				);
				$this->ajaxReturn($output);
			}
			if(empty($mid)){
				$output = array(
						'status' 	=>'8',
						'message'	=>'缺少资讯信息ID'
				);
				$this->ajaxReturn($output);
			}
			if(empty($ptype)){
				$output = array(
						'status' 	=>'9',
						'message'	=>'缺少文章全局类型'
				);
				$this->ajaxReturn($output);
			}
			$data = $this->hope_disease_type->where('level='.$level.'&& pid="'.$pid.'"&& state =1')->select();
			//获取关联表数据
			$info = $this->hope_link->where('sign_id='.$mid.'&& type='.$ptype.' && state=1')->select();
			$dataC = count($data);
			$infoC = count($info);
			$sixHTML = '';
			for($i=0;$i<$dataC;$i++){
				$n = 0;
				$m = 0;
				if($infoC){
					for($s=0;$s<$infoC;$s++){
						if($data[$i]['id'] == $info[$s]['disease_id']){
							$n = $n + 1;
						}else{
							$m = $m + 1;
						}
						if($n+$m == $infoC){
							if($n == 1){
								$sixHTML .= '<div class="publicSpan" data-type="3" style="background:red;color:white;" data-state="1" data-id="'.$data[$i]['id'].'">'.$data[$i]['name'].'</div>';
								$sixHTML .= '<input name="disease_id[]" value="'.$data[$i]['id'].'" type="hidden">';
							}else{
								$sixHTML .= '<div class="publicSpan" data-type="3" data-state="2" data-id="'.$data[$i]['id'].'">'.$data[$i]['name'].'</div>';
							}
						}
					}
				}else{
					$sixHTML .= '<div class="publicSpan" data-type="3" data-state="2" data-id="'.$data[$i]['id'].'">'.$data[$i]['name'].'</div>';
				}
			}
			$sixHTML .= '<div style="clear:both"></div>';
			$data = null;
			$data = $sixHTML;
		}else if($type == 5){
			if(empty($fid)){
				$output = array(
						'status' 	=>'2',
						'message'	=>'缺少疾病父级ID'
				);
				$this->ajaxReturn($output);
			}
			//按照疾病ID进行分组
			$sql = "SELECT * FROM `hope_link` WHERE `sign_type`='1' && `type`='1' &&`state`='1' GROUP BY `sign_id`";
			$linkDisease = D()->query($sql);
			$linkC = count($linkDisease);
			$newArr = array();
			for($l=0;$l<$linkC;$l++){
				if($linkDisease[$l]['body_id'] == $fid){
					$data[$l]['id'] = $linkDisease[$l]['sign_id'];
					$data[$l]['name'] = $this->hope_entry->where('id='.$linkDisease[$l]['sign_id'])->getField('cname');
					$data[$l]['pid'] = $this->hope_entry->where('id='.$linkDisease[$l]['sign_id'])->getField('pid');
				}
			}
		}else if($type == 6){
			if(empty($fid)){
				$output = array(
						'status' 	=>'10',
						'message'	=>'缺少所属科室ID'
				);
				$this->ajaxReturn($output);
			}
			$data = $this->hope_disease_type->where('level='.$level.'&& department_id="'.$fid.'"&& state =1')->select();
		}
		if($data){
			$output = array(
					'status' 	=>'1',
					'data'		=>$data,
					'message'	=>'查询成功'
			);
			$this->ajaxReturn($output);
		}else{
			$output = array(
					'status' 	=>'4',
					'message'	=>'未查询到疾病类型有效数据'
			);
			$this->ajaxReturn($output);
		}
	}
	
	
	/*
	* 图形验证码生成
	* 接收数据格式
		必传：'sign'=>验证码图形标识码
		选传：'fontSize'=>字体（默认为30）,'length'=>长度（默认为4）,'useNoise'=>杂点开关（默认为关闭）,
	* 返回数据格式 png图形
	*/
	public function verifyCreate($sign,$fontSize = "60",$length ="4",$useNoise = false){
		$this->Verifys->fontSize = $fontSize;
		$this->Verifys->length   = $length;
		$this->Verifys->useNoise = $useNoise;
		$this->Verifys->entry($sign);
	}
	
	
	/*
	* 纯数字短信验证码生成并发送
	* 接收数据格式		'sign'=>验证码标识符
	* 					'phone'=>待发送手机号
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：发送成功；
		2：缺少标识号；
		3：缺少手机号；
	}
	*/
	public function verifySMSCreateSend(){
		$sign =I('sign',''); 
		$phone =I('phone',''); 
		if(empty($sign)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少标识码'
			);
			$this->ajaxReturn($output);
		}
		if(empty($phone)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少手机号'
			);
			$this->ajaxReturn($output);
		}
		$this->Verifys->length   = 4;
		$this->Verifys->codeSet = '0123456789'; 
		$smsCode= $this->Verifys->entryText($sign);
		sendSms($phone,"【非速搜】您的订单验证码是".$smsCode."，1分钟有效，客服热线400-6688-733");
		$output = array(
			'status' 	=>'1',
			'message'	=>'发送成功'
		);
		$data=null;
		$data['phone']=$phone;
		$data['state']=2;
		$data['ctime']=time();
		session($phone.'#'.$sign,$data);
		$this->ajaxReturn($output);
	}
	
	
	/*
	* 验证码校验
	* 接收数据格式		'sign'=>验证码标识符
	* 					'verify'=>验证码
	* 					'isSession'=>成功后是否储存SESSION
	* 					'phone'=>参数isSession依赖
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：验证码正确；
		2：缺少标识号；
		3：缺少验证码；
		4：验证码错误；
	}
	*/
	public function verifyCheck(){
		$sign =I('sign',''); 
		$verify =I('verify',''); 
		$isSession =I('isSession','2'); 
		$phone =I('phone',''); 
		if(empty($sign)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少标识号'
			);
			$this->ajaxReturn($output);
		}
		if(empty($verify)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少验证码'
			);
			$this->ajaxReturn($output);
		}
		//判断是否存储SESSION
		if($isSession==1){
			if(empty($phone)){
				$output = array(
						'status' 	=>'4',
						'message'	=>'缺少手机号（参数isSession依赖条件）'
				);
				$this->ajaxReturn($output);
			}
		}
		//校验验证码
		$verifyState=$this->Verifys->check($verify,$sign);
		if($verifyState){
			$output = array(
					'status' 	=>'1',
					'message'	=>'验证码正确'
			);
			if($isSession==1){
				//执行存储验证
				if(session('?'.$phone.'#'.$sign)){
					//存在行为,校验有效期
					$data=null;
					$data=session($phone.'#'.$sign);
					if(time()-180 > $data['ctime']){
						//超出验证码有效期
						$output = array(
							'status' 	=>'6',
							'message'	=>'超出验证码有效期，请重新获取'
						);
						$this->ajaxReturn($output);
					}
					//更新SESSION状态
					$data['state']=1;
					session($phone.'#'.$sign,$data);
				}else{
					$output = array(
						'status' 	=>'5',
						'message'	=>'手机号非法操作'
					);
				}
				$this->ajaxReturn($output);
			}
			$this->ajaxReturn($output);
		}else{
			$output = array(
					'status' 	=>'5',
					'message'	=>'验证码错误'
			);
			//session($phone.'#'.$sign,null);
			$this->ajaxReturn($output);
		}	
	}
	
	
	/*
	* 邮件验证码(MD5)校验
	* 接收数据格式		'sign'=>验证码标识符
	* 					'verify'=>验证码
	* 					'isSession'=>成功后是否储存SESSION
	* 					'email'=>参数isSession依赖
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：验证码正确；
		2：缺少标识号；
		3：缺少验证码；
		4：验证码错误；
	}
	*/
	public function verifyEmailCheck(){
		$sign =I('sign',''); 
		$verify =I('verify',''); 
		$isSession =I('isSession','2'); 
		$email =I('email',''); 
		if(empty($sign)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少标识号'
			);
			$this->ajaxReturn($output);
		}
		if(empty($verify)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少验证码'
			);
			$this->ajaxReturn($output);
		}
		//判断是否存储SESSION
		if($isSession==1){
			if(empty($email)){
				$output = array(
						'status' 	=>'4',
						'message'	=>'缺少手机号（参数isSession依赖条件）'
				);
				$this->ajaxReturn($output);
			}
		}
		//校验验证码
		$verifyState=$this->Verifys->check($verify,$sign);
		if($verifyState){
			$output = array(
					'status' 	=>'1',
					'message'	=>'验证码正确'
			);
			if($isSession==1){
				//执行存储验证
				//处理邮箱的点，SESSION的名不能含有点
				$emails=str_replace('.', '',$email);
				if(session('?'.$emails.'#'.$sign)){
					//存在行为,校验有效期
					$data=null;
					$data=session($emails.'#'.$sign);
					if(time()-1800 > $data['ctime']){
						//超出验证码有效期
						$output = array(
							'status' 	=>'7',
							'message'	=>'超出验证码有效期，请重新获取'
						);
						$this->ajaxReturn($output);
					}
					//更新SESSION状态
					$data['state']=1;
					session($emails.'#'.$sign,$data);
				}else{
					$output = array(
						'status' 	=>'6',
						'message'	=>'手机号非法操作'
					);
				}
				$this->ajaxReturn($output);
			}
			$this->ajaxReturn($output);
		}else{
			$output = array(
					'status' 	=>'5',
					'message'	=>'验证码错误'
			);
			//session($phone.'#'.$sign,null);
			$this->ajaxReturn($output);
		}	
	}
	
	
	 //执行从redis写入mysql数据库
    public function redisToMysql(){
    	//从缓存读取redisUid的日志数量
		$uid = $this->redis->lsize("redisLogRecord");
		//从缓存中取出数据，并返回$data为二维数组
		for($i=1;$i<=$uid;$i++){
			$data[] = $this->redis->hgetall('log_record:'.$i);
		}
		//将数据写入数据库
		$count = count($data);
		$record= M("log_record");
		$recordlist =  $record->select();
		for($j=0;$j<$count;$j++){
			$rTom=null;
			$rTom['luid'] = $data[$j]['luid'];
			$rTom['typefid'] = $data[$j]['typefid'];
			$rTom['typeid'] = $data[$j]['typeid'];
			$rTom['typecid'] = $data[$j]['typecid'];
			$rTom['title'] = $data[$j]['title'];
			$rTom['browseragent'] = $data[$j]['browseragent'];
			$rTom['browserversion'] = $data[$j]['browserversion'];
			$rTom['browserplatform'] = $data[$j]['browserplatform'];
			$rTom['ip'] = $data[$j]['ip'];
			$rTom['country'] = $data[$j]['country'];
			$rTom['province'] = $data[$j]['province'];
			$rTom['city'] = $data[$j]['city'];
			$rTom['district'] = $data[$j]['district'];
			$rTom['carrier'] = $data[$j]['carrier'];
			$rTom['ctime'] = $data[$j]['ctime'];
			$rTom['cdate'] = $data[$j]['cdate'];
			$rTom['state'] = 1;
			//执行将从redis得到的日志保存至Mysql数据库
			$info = $record->add($rTom);
			dump($rTom);
			dump($info);
		}
		die();
		//判断从redis得到的日志保存至Mysql数据库是否成功
		if($info){
			$this->redirect("Index/index");
		}else{
			$this->error("redis记录日志保存至Mysql数据库过程中出现错误,请管理员查看!");
		} 
    }
	
	
	/*
	* 获取省市县信息校验
	* 接收数据格式		'pid'=>上级ID
	* 					'level'=>级别码
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：城市列表获取成功；
		2：缺少上级ID；
		3：缺少级别码；
		4：级别码非法；
		5：城市列表获取失败（无数据）；
	}
	*/
	public function CityList(){
		$pid =I('pid','');     
		$level =I('level','');     
		if(empty($pid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少上级ID'
			);
			$this->ajaxReturn($output);
		}
		if(empty($level)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少级别码'
			);
			$this->ajaxReturn($output);
		}
		if($level<0 or $level>4){
			$output = array(
					'status' 	=>'4',
					'message'	=>'级别码非法'
			);
			$this->ajaxReturn($output);
		}
		//准备城市列表数据
		$cityList=$this->citys->where('pid='.$pid.' && level='.$level)->select();
		if($cityList){
			$output = array(
					'status' 	=>'1',
					'message'	=>'城市列表获取成功',
					'citylist'	=>$cityList
			);
		}else{
			$output = array(
					'status' 	=>'5',
					'message'	=>'城市列表获取失败（无数据）'
			);
		}
		$this->ajaxReturn($output);
	}
	
	/*
	* 返回并获取省市县信息校验
	* 接收数据格式		'pid'=>上级ID
	* 					'level'=>级别码
	* 返回数据格式
		{
			'status'=>状态,
			'message'=>提示信息
		} 
	status = {
		1：城市列表获取成功；
		2：缺少上级ID；
		3：缺少级别码；
		4：级别码非法；
		5：城市列表获取失败（无数据）；
	}
	*/
	public function ReturnCityList(){
		//获取当前用户信息
		$mod = $this->userInfo;
		//获取城市id
		$mod['cityId'] = $this->citys->where('id='.$mod['area'])->getField("pid");
		//获取省id
		$mod['provinceId'] = $this->citys->where('id='.$mod['cityId'])->getField("pid");
		
		$pid =I('pid','');     
		$level =I('level','');     
		if(empty($pid)){
			$output = array(
					'status' 	=>'2',
					'message'	=>'缺少上级ID'
			);
			$this->ajaxReturn($output);
		}
		if(empty($level)){
			$output = array(
					'status' 	=>'3',
					'message'	=>'缺少级别码'
			);
			$this->ajaxReturn($output);
		}
		if($level<0 or $level>4){
			$output = array(
					'status' 	=>'4',
					'message'	=>'级别码非法'
			);
			$this->ajaxReturn($output);
		}
		//准备城市列表数据
		$cityList=$this->citys->where('pid='.$pid.' && level='.$level)->select();
		if($cityList){
			$output = array(
					'status' 	=>'1',
					'message'	=>'城市列表获取成功',
					'citylist'	=>$cityList,
					'mod'		=>$mod
			);
		}else{
			$output = array(
					'status' 	=>'5',
					'message'	=>'城市列表获取失败（无数据）'
			);
		}
		$this->ajaxReturn($output);
	}
	
	
	public function action(){
		error_reporting(0);
		$typeid = $_GET['type'];
		if($typeid == 1){
			//身份证正面
			$imgPath = 'Message/headImg/';
		} 
		
		//源文件名称
		$picname = $_FILES['mypic']['name'];
		//文件大小
		$picsize = $_FILES['mypic']['size'];
		if($picname != "") {  
			if($picsize > 2048000 || $picsize == 0) {  
				// echo '文件大小不能超过2M!';
				$arr = array(  
					'state'=>3, 
					'content'=>'文件大小不能超过2M!'
				); 
				//返回上传文件信息
				$this->ajaxReturn($arr);  
				exit;  
			}  
			$type = strstr($picname, '.');  
			if ($type != ".gif" && $type != ".jpg" && $type != ".png" && $type != ".jpeg" && $type != ".GIF" && $type != ".JPG" && $type != ".PNG" && $type != ".JPEG"){ 
				// echo '文件格式不正确!';
				$arr = array(  
					'state'=>2, 
					'content'=>'文件格式不正确!'
				); 
				//返回上传文件信息
				$this->ajaxReturn($arr);  
				exit;  
			}  
			$rand = rand(100, 999); 
			//新文件名称
			$pics = date("YmdHis").$rand.$type;
			//上传路径  
			$pic_path = "./Public/Uploads/".$imgPath.$pics;   
			move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);  
		}  
		//文件大小
		$size = round($picsize/1024,2); 
		$picpaths = "/Public/Uploads/".$imgPath. $pics; 
		//准备需要写入缓存的数据
		//上传的文件类型
		$data['type'] = $typeid;
		//上传用户的uid
		$user = $this->userInfo;
		$data['uid'] = $user['uid'];
		//源文件名称
		$data['sourcename'] = $picname;
		//新文件名称
		$data['newname'] = $pics;
		//文件存储路径
		$data['path'] = $picpaths;
		//文件创建时间
		$data['ctime'] = time();
		//文件使用状态(默认废除)
		$data['statecode'] = 2;
		// //将所有上传的图片写入redis缓存,并返回每一次记录的id
		// //选择3号数据库
		// $this->redis->select(3);
		// //将自增的id写入缓存
		// $filePrev = $this->redis->incr('filenum');
		// //将准备的数据写入缓存
		// $this->redis->hmset('file:'.$filePrev,$data);
		//实例化对象
		$uploadsId = M('hope_uploads');
		$uploadsId->url = $picpaths;	    
		$uploadsId->uptime = time();	    
		$filePrevID = $uploadsId->add();
		$arr = array(  
			'state'=>1, 
			'type'=>$typeid, 
			'name'=>$picname,  
			'pic'=>$pics,  
			'size'=>$size, 
			'path'=>$picpaths, 
			'fileid'=>$filePrevID, 
			'content'=>'文件上传成功!'
		); 
		
		function my_get_browser(){
			if(empty($_SERVER['HTTP_USER_AGENT'])){
				return '命令行，机器人来了！';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9.0')){
				return 'Internet Explorer 9.0';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')){
				return 'Internet Explorer 8.0';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')){
				return 'Internet Explorer 7.0';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')){
				return 'Internet Explorer 6.0';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){
				return 'Firefox';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){
				return 'Chrome';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari')){
				return 'Safari';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera')){
				return 'Opera';
			}
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE')){
				return '360SE';
			}
		} 
		//获取浏览器信息
		$browserAgent = my_get_browser();
		if($browserAgent == 'Internet Explorer 9.0' || $browserAgent == 'Internet Explorer 8.0' || $browserAgent == 'Internet Explorer 7.0'){
			$res=json_encode($arr);
			$res='<textarea>'.$res.'</textarea>';
			echo $res;
		}else{
			//返回上传文件信息
			$this->ajaxReturn($arr);
		}		
	}
	
	
	
	
}