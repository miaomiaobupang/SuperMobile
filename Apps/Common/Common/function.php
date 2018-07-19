<?php
    /***
	*CURL 并行请求
	*@param array  如       array('106.426,29.553404','106.426,29.553404');
	*return array  返回数据 array('106.426,29.553404','106.426,29.553404');
	*
	*/
	function httpCurl($arr = array()) {
	    //坐标分离后的数组
	    $lngArr = array();
		
		
		/***HTTP头参数****/
		$httpHeader = array(
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_HEADER => FALSE,
		CURLOPT_FOLLOWLOCATION => TRUE,
		CURLOPT_MAXREDIRS => 50,
		CURLOPT_AUTOREFERER => TRUE,
		CURLOPT_NOBODY => FALSE,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:6.0.2) Gecko/20100101 Firefox/6.0.2',
	   );
		/*array(
			CURLOPT_FAILONERROR=>true,  //返回HTTP状态码，默认返回小于400
			CURLOPT_HEADER  =>false, //是否把文件头最为文件流输出
			CURLOPT_RETURNTRANSFER => true,//返回文件流
			CURLOPT_ENCODING =>  'gzip, deflate', //服务器支持的编码
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:28.0) Gecko/20100101 Firefox/28.0',
			//CURLOPT_HTTPHEADER =>$this->httpheader,
			CURLOPT_TIMEOUT      => 30  //超时时间
	    );*/
		
		
		//跟URl地址
		$url = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4";
		
		/********/
		$curlArr = array();
		$urlArr  = array();
		
		//创建CURL资源，并分离坐标
		foreach($arr as $k=>$v) {
		   list($x,$y)  = explode(',', $v);
		    $lngArr[$k] = array('x'=>$x,'y'=>$y);
			
			
		}
		
		// 创建批处理cURL句柄
		$mh = curl_multi_init();
		
		
		//拼接所有的URL地址    设置URL和相应的选项
		foreach($lngArr as $k=>$v) {
		
		   $urlArr[$k]  = $url.'&x='.$v['x'].'&y='.$v['y'];
		   $curlArr[$k] =  curl_init();
		   curl_setopt_array($curlArr[$k],$httpHeader);
		   
		   curl_setopt($curlArr[$k],CURLOPT_URL,$urlArr[$k]);
		   curl_setopt($curlArr[$k],CURLOPT_HTTPGET,true);
		   curl_setopt($curlArr[$k],CURLOPT_AUTOREFERER,false);
		   curl_multi_add_handle($mh, $curlArr[$k]);
		   
		}
		
		
		
		/*
		curl_setopt($ch1, CURLOPT_URL, "http://www.example.com/");
		curl_setopt($ch1, CURLOPT_HEADER, 0);
		curl_setopt($ch2, CURLOPT_URL, "http://www.php.net/");
		curl_setopt($ch2, CURLOPT_HEADER, 0);
		*/
		
		
		   $active = null;
		   $mrc    = '';
			// 执行批处理句柄
			do {
			    $mrc = curl_multi_exec($mh, $active);
			} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			while ($active && $mrc == CURLM_OK) {
			    if (curl_multi_select($mh) != -1) {
			        do {
			            $mrc = curl_multi_exec($mh, $active);
			        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
			    }
			}
		
		/**********处理返回数据**********/
		$result = array();
		
		foreach($curlArr as $k=>$v) {
            $html = curl_multi_getcontent($v); // get the content
            curl_multi_remove_handle($mh, $v); // remove the handle (assuming  you are done with it);
			$array = json_decode($html,true);
			if($array['error'] == 0) {
			    $bx = base64_decode($array['x']);
			    $by = base64_decode($array['y']);
			    $GPS_x = 2 * $lngArr[$k]['x'] - $bx;
			    $GPS_y = 2 * $lngArr[$k]['y'] - $by;
			    $result[$k]['x'] = $GPS_x;
				$result[$k]['y'] = $GPS_y;//经度,纬度
			}
			
        }
		curl_multi_close($mh);
	    return $result;
	}
	
	
	/**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    function ajaxReturn($data,$type='JSON',$json_option=0) {
        
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data,$json_option).');');  
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);            
           // default     :
                // 用于扩展其他返回格式数据
                //Hook::listen('ajax_return',$data);
        }
    }
	
	/**
     * POST接口请求
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
	function post($url, $data){//file_get_content
		$postdata = http_build_query(

			$data

		);
        $opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
	/* *************************************************************************************************
		**********************************通信类**********************************************
	************************************************************************************************* */
	/**
	 * 邮件发送函数
	 */
    function sendMail($to, $title, $content) {
		Vendor('PHPMailer.PHPMailerAutoload');     
        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
        $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
        $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
        $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
        $mail->AddAddress($to,"尊敬的客户");
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
        $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $content; //邮件内容
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        return($mail->Send());
    }
	/* 
	*	非速搜邮件模板生成
	*/
	function EmailTemplate($content,$explain){
		//
		$start="<STYLE type=text/css>TABLE{background-color:#fff;} body {width: 100% !important; min-width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; margin: 0; padding: 0;}img {outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; max-width: 100%; float: left; clear: both; display: block;}body {color: #222; font-family: 'Helvetica', 'Arial', sans-serif; padding: 0; margin: 0; font-size: 16px;}</STYLE><TABLE style='FONT-SIZE: 16px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; WIDTH: 100%; BACKGROUND: #fff; BORDER-COLLAPSE: collapse; COLOR: #222; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px; BORDER-SPACING: 0; PADDING-RIGHT: 0px' bgColor=#fff><TBODY><TR><TD style='FONT-SIZE: 16px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; BORDER-COLLAPSE: collapse !important; COLOR: #222; PADDING-BOTTOM: 0px; TEXT-ALIGN: center; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px; PADDING-RIGHT: 0px; -moz-hyphens: auto; -webkit-hyphens: auto; hyphens: auto' vAlign=top align=center><CENTER><TABLE style='FONT-SIZE: 16px; MAX-WIDTH: 680px; WIDTH: 95%; BORDER-COLLAPSE: collapse; MARGIN: 0px auto; BORDER-SPACING: 0; LINE-HEIGHT: 1.5'><TBODY><TR><TD style='FONT-SIZE: 16px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; BORDER-COLLAPSE: collapse !important; COLOR: #222; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px; PADDING-RIGHT: 0px; -moz-hyphens: auto; -webkit-hyphens: auto; hyphens: auto'><TABLE style='BORDER-COLLAPSE: collapse; BORDER-SPACING: 0; MARGIN-TOP: 30px' width='100%'><TBODY><TR><TD style='FONT-SIZE: 16px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; VERTICAL-ALIGN: bottom; BORDER-COLLAPSE: collapse !important; COLOR: #222; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px; PADDING-RIGHT: 0px; -moz-hyphens: auto; -webkit-hyphens: auto; hyphens: auto'><A style='TEXT-DECORATION: none; COLOR: #2ba6cb' href='http://test.bjsilkroad.com' target='_blank'><IMG style='TEXT-DECORATION: none; MAX-WIDTH: 100%; BORDER-TOP: medium none; HEIGHT: 28PX; BORDER-RIGHT: medium none; WIDTH: auto; BORDER-BOTTOM: medium none; FLOAT: left; OUTLINE-WIDTH: medium; OUTLINE-STYLE: none; CLEAR: both; BORDER-LEFT: medium none; DISPLAY: block; OUTLINE-COLOR: invert; -MS-INTERPOLATION-MODE: bicubic;' src='http://test.bjsilkroad.com/Public/Home/images/logo2314564852.png'></A></TD></TR></TBODY></TABLE><HR style='BORDER-TOP: medium none; HEIGHT: 1px; BORDER-RIGHT: medium none; BACKGROUND: #ddd; BORDER-BOTTOM: medium none; COLOR: #ddd; MARGIN: 20px 0px 30px; BORDER-LEFT: medium none'><TABLE style='BORDER-COLLAPSE: collapse; BORDER-SPACING: 0' width='100%'><TBODY><TR><TD style='FONT-SIZE: 16px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; BORDER-COLLAPSE: collapse !important; COLOR: #222; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px; PADDING-RIGHT: 0px; -moz-hyphens: auto; -webkit-hyphens: auto; hyphens: auto'><DIV>";
		$footer="</P></DIV></TD></TR></TBODY></TABLE><HR style='BORDER-TOP: medium none; HEIGHT: 1px; BORDER-RIGHT: medium none; BACKGROUND: #ddd; BORDER-BOTTOM: medium none; COLOR: #ddd; MARGIN: 20px 0px; BORDER-LEFT: medium none'><TABLE style='BORDER-COLLAPSE: collapse; BORDER-SPACING: 0' width='100%'><TBODY><TR><TD style='FONT-SIZE: 16px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; BORDER-COLLAPSE: collapse !important; COLOR: #222; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px; PADDING-RIGHT: 0px; -moz-hyphens: auto; -webkit-hyphens: auto; hyphens: auto' colSpan=2 align=center><P style='FONT-SIZE: 12px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; COLOR: #999999; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px 0px 10px; PADDING-RIGHT: 0px'>";
		$footer2="</P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></CENTER></TD></TR></TBODY></TABLE>";
		$contentZ=$start.$content;
		$contentZ.="<P style='FONT-SIZE: 12px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; COLOR: #999999; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px 0px 10px; PADDING-RIGHT: 0px'>";
		//底部提示
		$contentZ.=$explain;
		//底部版权
		$contentZ.=$footer."官方电话：400-6688-733".$footer2;
		return $contentZ;
	}
	/* 
	*	非速搜优米模板段落生成
	*/
	function EmailTemplateP($content){
		$contentZ="<P style='FONT-SIZE: 16px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; COLOR: #222; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px 0px 10px; PADDING-RIGHT: 0px'>";
		$contentZ.=$content;
		$contentZ.="</P>";
		return $contentZ;
	}
	/* 
	*	非速搜优米模板小段落生成
	*/
	function EmailTemplateSP($content){
		$contentZ="<P style='FONT-SIZE: 12px; FONT-FAMILY: 'Helvetica', 'Arial', sans-serif; COLOR: #999999; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; MARGIN: 0px 0px 10px; PADDING-RIGHT: 0px'>";
		$contentZ.=$content;
		$contentZ.="</P>";
		return $contentZ;
	}
	/* 
	*	非速搜优米模板段落生成中的A链接生成
	*/
	function EmailTemplatePA($href,$title){
		$contentZ="<A style='TEXT-DECORATION: none; BACKGROUND: #236393; WORD-BREAK: break-all; COLOR: #fff; PADDING-BOTTOM: 8px; PADDING-TOP: 8px; PADDING-LEFT: 16px; PADDING-RIGHT: 16px; border-radius: 4px' href='".$href."' target=_blank>".$title."</A>";
		return $contentZ;
	}
	/**
	*发送短信(创世华信)
	*@param $mobile接收短信的手机号
	*@param $content短信内容（内容需要登录短信平台报备）
	*/
	function sendSms($mobile,$content){
		//企业ID $smsuserid
		$smsuserid = C('SMS_ID');
		//用户账号 $account
		$smsaccount = C('SMS_USER');
		//用户密码 $password
		$smspassword = C('SMS_PASS');
		$contentcode = urlencode($content);
		//发送短信（其他方法相同）
		$gateway = "http://dx.ipyy.net/sms.aspx?action=send&userid={$smsuserid}&account={$smsaccount}&password={$smspassword}&mobile={$mobile}&content={$contentcode}&sendTime=";
		$result = file_get_contents($gateway);
		/* $xml = simplexml_load_string($result);
		echo "内容为：".$contentcode."<br>";
		echo "返回状态为：".$xml->returnstatus."<br>";
		echo "返回信息：".$xml->message."<br>";
		echo "返回余额：".$xml->remainpoint."<br>";
		echo "返回本次任务ID：".$xml->taskID."<br>";
		echo "返回成功短信数：".$xml->successCounts."<br>";
		echo "<br>";
		echo "<br>"; */
	}
	
	/**
	*发送短信(创世华信)【校验版本，成功返回1失败返回2】
	*@param $mobile接收短信的手机号
	*@param $content短信内容（内容需要登录短信平台报备）
	*/
	function sendSmsCheck($mobile,$content){
		//企业ID $smsuserid
		$smsuserid = C('SMS_ID');
		//用户账号 $account
		$smsaccount = C('SMS_USER');
		//用户密码 $password
		$smspassword = C('SMS_PASS');
		$contentcode = urlencode($content);
		//发送短信（其他方法相同）
		$gateway = "http://dx.ipyy.net/sms.aspx?action=send&userid={$smsuserid}&account={$smsaccount}&password={$smspassword}&mobile={$mobile}&content={$contentcode}&sendTime=";
		$result = file_get_contents($gateway);
		$xml = simplexml_load_string($result);
		$status = $xml->returnstatus;
		//返回发送短信状态码
		return $status;
		/* $xml = simplexml_load_string($result);
		echo "内容为：".$contentcode."<br>";
		echo "返回状态为：".$xml->returnstatus."<br>";
		echo "返回信息：".$xml->message."<br>";
		echo "返回余额：".$xml->remainpoint."<br>";
		echo "返回本次任务ID：".$xml->taskID."<br>";
		echo "返回成功短信数：".$xml->successCounts."<br>";
		echo "<br>";
		echo "<br>"; */
	}
	/* *************************************************************************************************
		**********************************坐标类**********************************************
	************************************************************************************************* */	
	
	/**
	 * 计算两个坐标之间的距离(米)
	 * @param float $fP1Lat 起点(纬度)
	 * @param float $fP1Lon 起点(经度)
	 * @param float $fP2Lat 终点(纬度) 
	 * @param float $fP2Lon 终点(经度)
	 * @return int
	 */
	function distanceBetweens($fP1Lat, $fP1Lon, $fP2Lat, $fP2Lon){
		$fEARTH_RADIUS = 6378137;
		//角度换算成弧度
		$fRadLon1 = deg2rad($fP1Lon);
		$fRadLon2 = deg2rad($fP2Lon);
		$fRadLat1 = deg2rad($fP1Lat);
		$fRadLat2 = deg2rad($fP2Lat);
		//计算经纬度的差值
		$fD1 = abs($fRadLat1 - $fRadLat2);
		$fD2 = abs($fRadLon1 - $fRadLon2);
		//距离计算
		$fP = pow(sin($fD1/2), 2) +
			  cos($fRadLat1) * cos($fRadLat2) * pow(sin($fD2/2), 2);
		return intval($fEARTH_RADIUS * 2 * asin(sqrt($fP)) + 0.5);
	}
	
	
	/**
	 * 百度坐标系转换成标准GPS坐系
	 * @param float $lnglat 坐标(如:106.426, 29.553404)
	 * @return string 转换后的标准GPS值:
	 */
	function BD09LLtoWGS84s($lnglat){ // 经度,纬度
		$lnglat = explode(',', $lnglat);
		list($x,$y) = $lnglat;
		$Baidu_Server = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x={$x}&y={$y}";
		$result = @file_get_contents($Baidu_Server);
		$json = json_decode($result);
		if($json->error == 0){
			$bx = base64_decode($json->x);
			$by = base64_decode($json->y);
			$GPS_x = 2 * $x - $bx;
			$GPS_y = 2 * $y - $by;
			return $GPS_x.','.$GPS_y;//经度,纬度
		}else
			return $lnglat;
	}
	
	/* *************************************************************************************************
		**********************************排序类**********************************************
	************************************************************************************************* */	
	
	
	/**
	 * 二维数组排序
	 * @param $arr待排序数组
	 * @param $keys排序字段 如age
	 * @param $type排序类型 asc升序（默认） desc降序
	 * @return $new_array 排序好的新数组
	 */
	function array_sort($arr,$keys,$type='asc'){ 
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc'){
			asort($keysvalue);
		}else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		$new_array=array_values($new_array);
		return $new_array; 
	}
	/* *************************************************************************************************
		**********************************转换类**********************************************
	************************************************************************************************* */
	/**
	 * 二维数组排序
	 * @param $arr待排序数组
	 * @param $keys排序字段 如age
	 * @param $type排序类型 asc升序（默认） desc降序
	 * @return $new_array 排序好的新数组
	 */
	function timetodate($time,$type){
		if($time="today"){
			$time=time();
		}
		$time=date('Y-m-d',$time);
		if($type==1){
			$time=strtotime($time);
		}
		return $time; 
	}
	
	/* *************************************************************************************************
		**********************************查询类**********************************************
	************************************************************************************************* */
	
	
	/**
	 * 身份证查询
	 * @param $str $idcard 待查询身份证
	 * @return array( 返回信息数组
			{
				'errNum'=>状态码,
				'retMsg'=>状态提示,
				'retData'=array(
						'address'=>地区,
						'sex'=>性别,（M：男性；F：女性）
						'birthday'=>出生年月,
					)
			} 
			errNum = {
				0：信息获取成功；
				-1：身份证号码不合法!
			}
	 */
	function find_idcard_info($idcard){ 
		$ch = curl_init();
		$url = 'http://apis.baidu.com/apistore/idservice/id?id='.$idcard;
		$header = array(
			'apikey: '.C('BAIDU_API_CODE'),
		);
		// 添加apikey到header
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 执行HTTP请求
		curl_setopt($ch , CURLOPT_URL , $url);
		$res = curl_exec($ch);
		return json_decode($res);
	}
	
	
	/**
	 * IP信息查询
	 * @param $str $ip 待查询IP
	 * @return array( 排序好的新数组
			{
				'errNum'=>状态码,
				'retMsg'=>状态提示,
				'retData'=array(
						'ip'=>IP,
						'country'=>国家,（为空None）
						'province'=>省份,（为空None）
						'city'=>城市,（为空None）
						'district'=>区域,（为空None）
						'carrier'=>运营商,（为空None）
					)
			} 
			errNum = {
				0：信息获取成功；
				1：无效的IP地址；
				200201：IP地址为空；
			}
	 */
	function find_ip_info($ip){ 
		$ch = curl_init();
		$url = 'http://apis.baidu.com/apistore/iplookupservice/iplookup?ip='.$ip;
		$header = array(
			'apikey: '.C('BAIDU_API_CODE'),
		);
		// 添加apikey到header
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 执行HTTP请求
		curl_setopt($ch , CURLOPT_URL , $url);
		$res = curl_exec($ch);
		return json_decode($res);
	}
	
	
	/**
	 * 电话信息查询
	 * @param $str $tel 待查询电话
	 * @return array( 排序好的新数组
			{
				'errNum'=>状态码,
				'retMsg'=>状态提示,
				'retData'=array(
						'telString'=>电话号码,
						'province'=>省份,（为空None）
						'carrier'=>运营商,（为空None）
					)
			} 
			errNum = {
				0：信息获取成功；
				-1：无效的手机号码！
			}
	 */
	function find_phone_info($tel){ 
		$ch = curl_init();
		$url = 'http://apis.baidu.com/apistore/mobilephoneservice/mobilephone?tel='.$tel;
		$header = array(
			'apikey: '.C('BAIDU_API_CODE'),
		);
		// 添加apikey到header
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 执行HTTP请求
		curl_setopt($ch , CURLOPT_URL , $url);
		$res = curl_exec($ch);
		return json_decode($res);
	}
	
	
	/**
	 * 城市天气信息查询
	 * @param $str 待查询电话
	 * @return array( 排序好的新数组
			{
				'errNum'=>状态码,
				'retMsg'=>状态提示,
				'retData'=array(
						'telString'=>电话号码,
						'province'=>省份,（为空None）
						'carrier'=>运营商,（为空None）
					)
			} 
			errNum = {
				0：信息获取成功；
				-1：无效的手机号码！
			}
	 */
	function find_cityname_weatherinfo($cityname){ 
		$ch = curl_init();
		$url = 'http://apis.baidu.com/apistore/weatherservice/cityname?cityname='.$cityname;
		$header = array(
			'apikey: '.C('BAIDU_API_CODE'),
		);
		// 添加apikey到header
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 执行HTTP请求
		curl_setopt($ch , CURLOPT_URL , $url);
		$res = curl_exec($ch);
		return json_decode($res);
	}
	
	/**
	 * 网站访问者信息查询
	 * @param 无
	 * @return array( 排序好的新数组
			{
				'errNum'=>状态码,
				'retMsg'=>状态提示,
				'retData'=array(
						'telString'=>电话号码,
						'province'=>省份,（为空None）
						'carrier'=>运营商,（为空None）
					)
			} 
			errNum = {
				0：信息获取成功；
				-1：无效的手机号码！
			}
	 */
	function find_visitor_info(){
		$Agent=$_SERVER['HTTP_USER_AGENT'];
		$browserplatform=='';
		if (eregi('win',$Agent) && strpos($Agent, '95')) {
		$browserplatform="Windows 95";
		}
		elseif (eregi('win 9x',$Agent) && strpos($Agent, '4.90')) {
		$browserplatform="Windows ME";
		}
		elseif (eregi('win',$Agent) && ereg('98',$Agent)) {
		$browserplatform="Windows 98";
		}
		elseif (eregi('win',$Agent) && eregi('nt 5.0',$Agent)) {
		$browserplatform="Windows 2000";
		}
		elseif (eregi('win',$Agent) && eregi('nt 5.1',$Agent)) {
		$browserplatform="Windows XP";
		}
		elseif (eregi('win',$Agent) && eregi('nt 6.0',$Agent)) {
		$browserplatform="Windows Vista";
		}
		elseif (eregi('win',$Agent) && eregi('nt 6.1',$Agent)) {
		$browserplatform="Windows 7";
		}
		elseif (eregi('win',$Agent) && ereg('32',$Agent)) {
		$browserplatform="Windows 32";
		}
		elseif (eregi('win',$Agent) && eregi('nt',$Agent)) {
		$browserplatform="Windows NT";
		}elseif (eregi('Mac OS',$Agent)) {
		$browserplatform="Mac OS";
		}
		elseif (eregi('linux',$Agent)) {
		$browserplatform="Linux";
		}
		elseif (eregi('unix',$Agent)) {
		$browserplatform="Unix";
		}
		elseif (eregi('sun',$Agent) && eregi('os',$Agent)) {
		$browserplatform="SunOS";
		}
		elseif (eregi('ibm',$Agent) && eregi('os',$Agent)) {
		$browserplatform="IBM OS/2";
		}
		elseif (eregi('Mac',$Agent) && eregi('PC',$Agent)) {
		$browserplatform="Macintosh";
		}
		elseif (eregi('PowerPC',$Agent)) {
		$browserplatform="PowerPC";
		}
		elseif (eregi('AIX',$Agent)) {
		$browserplatform="AIX";
		}
		elseif (eregi('HPUX',$Agent)) {
		$browserplatform="HPUX";
		}
		elseif (eregi('NetBSD',$Agent)) {
		$browserplatform="NetBSD";
		}
		elseif (eregi('BSD',$Agent)) {
		$browserplatform="BSD";
		}
		elseif (ereg('OSF1',$Agent)) {
		$browserplatform="OSF1";
		}
		elseif (ereg('IRIX',$Agent)) {
		$browserplatform="IRIX";
		}
		elseif (eregi('FreeBSD',$Agent)) {
		$browserplatform="FreeBSD";
		}
		if ($browserplatform=='') {$browserplatform = "Unknown"; }
		$browseragent="";   //浏览器
		$browserversion=""; //浏览器的版本
		if (ereg('MSIE ([0-9].[0-9]{1,2})',$Agent,$version)) {
		 $browserversion=$version[1];
		 $browseragent="Internet Explorer";
		} else if (ereg( 'Opera/([0-9]{1,2}.[0-9]{1,2})',$Agent,$version)) {
		 $browserversion=$version[1];
		 $browseragent="Opera";
		} else if (ereg( 'Firefox/([0-9.]{1,5})',$Agent,$version)) {
		 $browserversion=$version[1];
		 $browseragent="Firefox";
		}else if (ereg( 'Chrome/([0-9.]{1,3})',$Agent,$version)) {
		 $browserversion=$version[1];
		 $browseragent="Chrome";
		}
		else if (ereg( 'Safari/([0-9.]{1,3})',$Agent,$version)) {
		 $browseragent="Safari";
		 $browserversion="";
		}
		else {
		$browserversion="";
		$browseragent="Unknown";
		}
		//获取浏览器标示
		$browsevers['browseragent']=$browseragent;
		//获取浏览器版本信息
		$browsevers['browserversion']=$browserversion;
		//获取用户操作系统
		$browsevers['browserplatform']=$browserplatform;
		//获取IP地域信息
		$browsevers['ip']=$_SERVER['REMOTE_ADDR'];
		$browsevers['ipinfo']=find_ip_info($browsevers['ip']);
		//获取国家
		$browsevers['country']=$browsevers['ipinfo']->retData->country;
		if(empty($browsevers['country']) or $browsevers['country']=="None"){
			$browsevers['country']="未知";
		}
		//获取省份
		$browsevers['province']=$browsevers['ipinfo']->retData->province;
		if(empty($browsevers['province']) or $browsevers['province']=="None"){
			$browsevers['province']="未知";
		}
		//获取城市
		$browsevers['city']=$browsevers['ipinfo']->retData->city;
		if(empty($browsevers['city']) or $browsevers['city']=="None"){
			$browsevers['city']="未知";
		}
		//获取区域
		$browsevers['district']=$browsevers['ipinfo']->retData->district;
		if(empty($browsevers['district']) or $browsevers['district']=="None"){
			$browsevers['district']="未知";
		}
		//获取运营商
		$browsevers['carrier']=$browsevers['ipinfo']->retData->carrier;
		if(empty($browsevers['carrier']) or $browsevers['carrier']=="None"){
			$browsevers['carrier']="未知";
		}
		unset($browsevers['ipinfo']);
		return $browsevers;
	}
	
	/* *************************************************************************************************
		**********************************生成类**********************************************
	************************************************************************************************* */
	
	/**
     * 生成随机数
     * @access public
     * @param int $length 生成长度
     * @param int $type 随机数类型 1数字 2小写字母 3大写字母 4数字和小写字母 5小写和大写字母 6数字和小大写字母 7中文     
     * @return str $code 生成的随机数
     */
	function rand_num($length,$type){
		$str="0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNIOPQRSTUVWXYZ";//数字大小写字母库
		$zhongwen=array("今","年","的","当","天","创","下","超","亿","交","易","额","度","已","经","成","为","全","民","的","购","物","狂","欢","节","而","注","册","了","的","商","标","让","人","觉","得","这","个","节","日","好","像","被","偷","走","了","光","棍","节","不","同","于","传","统","的","中","秋","节","元","宵","节","等","也","不","同","于","我","们","的","国","庆","节","建","军","节","他","是","中","国","年","轻","人","的","娱","乐","性","节","日","广","泛","认","为","光","棍","节","最","早","起","源","于","南","京","大","学","一","个","叫","木","光","昆","的","男","同","学","大","二","时","和","一","个","女","同","学","相","恋","但","女","友","被","查","出","患","有","绝","症","最","终","离","世","大","四","时","舍","友","在","月","日","他","生","日","那","天","为","他","庆","祝","的","生","日","毕","业","以","后","木","光","昆","的","爱","情","故","事","被","流","传","开","来","并","且","南","大","同","学","将","定","为","光","棍","节","随","着","学","生","毕","业","也","被","带","到","了","社","会","上","并","且","被","越","来","越","多","的","人","们","接","受","发","展","成","为","单","身","们","的","派","对","子","由","于","光","棍","节","的","话","题","性","很","多","商","家","拿","这","天","来","组","织","活","动","促","销","等","渐","渐","的","这","个","日","子","的","商","业","气","味","也","越","来","越","重","年","开","始","将","光","棍","节","发","扬","光","大","天","猫","组","织","了","其","平","台","上","的","各","路","商","家","在","这","天","搞","大","型","促","销","活","动","渐","渐","的","这","个","活","动","已","经","带","动","许","多","其","他","电","商","一","起","举","办","活","动","光","棍","节","的","原","本","的","凄","凉","感","觉","也","被","购","物","的","热","情","取","代","年","开","始","每","一","年","的","的","交","易","额","也","是","由","最","初","的","亿","元","到","今","年","的","亿","元","不","是","双","一","光","棍","节","的","最","初","提","出","者","但","绝","对","是","的","开","拓","者","毕","加","索","曾","经","有","句","话","说","好","的","艺","术","家","只","是","照","抄","而","伟","大","的","艺","术","家","是","窃","取","灵","感","无","疑","是","伟","大","的","艺","术","家","用","窃","取","来","的","灵","感","创","造","了","电","商","销","售","的","神","话","集","团","申","请","注","册","了","商","标","成","功","取","得","商","标","这","个","消","息","被","大","众","所","知","后","引","起","一","些","人","和","相","关","企","业","的","不","满","虽","然","在","创","造","了","很","多","世","纪","记","录","但","将","一","个","日","子","占","为","己","有","的","做","法","多","少","让","人","觉","得","有","些","奇","怪");//中文库数组
		if($type<=6){//当$type(类型)小于6时执行
			if($type==1){
				//纯数字
				$start=0;
				$len=9;
			}else if($type==2){
				$start=10;
				$len=34;
			}else if($type==3){
				$start=35;
				$len=61;
			}else if($type==4){
				$start=0;
				$len=34;
			}else if($type==5){
				$start=10;
				$len=61;
			}else{
				$start=0;
				$len=61;
			}
			$code="";
			for($i=1;$i<=$length;$i++){//循环$length次
				$code.=$str[rand($start,$len)];//从字符串$str的通过随机到0到$len个数字下标来抽取一个字符链接赋给$code
				}
			return $code;//返回字符串$code
		}else{
				$len=518;//需要使用的中文库长度
			for($i=0;$i<$length;$i++){//循环$length次

				$code=array_slice($zhongwen,rand(0,$len),1);//从中文库数组$zhongwen的通过随机到0到$len个数字下标来抽取一个汉字元素赋给$code
				
				foreach($code as $v){//对数组$code的值进行循环遍历
					$zwjianyan[$i]=$code[0];//把数组$code[0]的值依次赋给数组$zwjianyan
				}
				
			}

			return $zwjianyan;//返回数组$zwjianyan
		}
	}
	
	function callback($redis,$channel,$msg){
		echo $channel.':'.$msg;
		die();
	}
	
	//测试中文转拼音
	function ChineseToPinyin($content,$type){
		import('ORG.Util.ChinesePinyin');
		$Pinyin = new \Org\Util\ChinesePinyin();
		$punctuation = array('!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', ',', ' ', '-', '?', '，', '。', '.', '（', '）', '·');
		$strings = str_replace($punctuation,'',$content);
		if($type == 1){
			//转成带有声调的汉语拼音
			$result = $Pinyin->TransformWithTone($strings);
		}else if($type == 2){
			//转成带无声调的汉语拼音
			$result = $Pinyin->TransformWithoutTone($strings,'');
		}else if($type == 3){
			//转成汉语拼音首字母
			$result = $Pinyin->TransformUcwords($strings);
		}else if($type == 4){
			//取出中文字符
			$chstrE = preg_replace("/[^A-Za-z0-9\.\-]/","",$strings);
			preg_match_all("/[\x{4e00}-\x{9fa5}]+/u",$strings,$matches);
			$cB = count($matches[0]);
			$chestrC = null;
			for($o=0;$o<$cB;$o++){
				$chestrC .= ChineseToPinyin($matches[0][$o],2);
			}
			//转成汉语拼音首字母
			$result = $chstrE.$chestrC;
		}
		return $result;
	}
	
	//公共提取关键字并加链接
	function keyWordsHref($type,$content,$title){
		//关键词字典
		$disList = ['艾滋病','脑瘤','鼻咽癌','眼部肿瘤','舌癌','牙龈癌','宫颈癌','颊癌','垂体瘤','脑膜瘤','胶质瘤','视网膜母细胞瘤','甲状腺肿瘤','喉癌','扁桃腺癌','室管膜肿瘤','下咽癌','脑血管疾病','脑动脉粥样硬化','血栓形成','血栓狭窄','血栓闭塞','脑动脉炎','脑动脉损伤','颅内血管畸形','脑动静脉瘘','脑中风后遗症','脑炎后遗症或脑膜炎后遗症','重型颅脑损伤','双耳失聪','双目失明','癫痫','胃癌','乳腺癌','胸腺瘤','肝癌','肾癌','胰腺癌','结直肠癌','食管癌','胆囊癌','胆管癌','腹膜癌','肺癌','终末期肾病','急性重症肝炎','急性心肌梗塞','原发性心肌病','子宫内膜癌','前列腺癌','卵巢癌','膀胱癌','睾丸癌','阴茎癌','输卵管癌','黑色素瘤','扁平上皮癌','鳞状上皮细胞癌','皮肤癌','横纹肌肉瘤','儿童白血病','儿童神经母细胞瘤','儿童肝母细胞瘤','儿童软组织肉瘤','儿童肾母细胞瘤','儿童髓母细胞瘤','儿童生殖细胞肿瘤','白血病','肌肉瘤','脂肪肉瘤','骨肿瘤','神经母细胞瘤','PNET','霍奇金淋巴瘤','肉瘤','骨肉瘤','尤文氏肉瘤','炎性肌纤维母细胞瘤','帕金森','冠心病','先天性心脏病','瓣膜病','心肌病','心律失常','脑瘫','其他','口腔癌','外阴癌','胎盘癌','肛门癌','小肠癌','肺源性心心脏病','感染性心内膜炎','心力衰竭','心绞痛','中耳癌','扁桃体癌','唇癌','口底癌','唾液腺癌','支气管癌','腮腺癌','阴道癌'];
		$content_two = $content;
		$content_three = array();
		$dc = count($disList);
		for($d=0;$d<$dc;$d++){
			$match = array();
			$content_three[$disList[$d]] = preg_match_all('/'.$disList[$d].'/u',$content_two,$match);
			if($match[0]){
				foreach($match[0] as $k=>$v){
					$newK = null;
					$pid = M('hope_disease_type')->where('name="'.$match[0][$k].'"')->getField('id');
					$newK = '<a href="http://www.superdoctor.cn/diseaseList/'.$pid.'" target="_blank">'.$v.'</a>';
					//更换文章中的关键词为词条链接
					$content_two = str_replace($match[0][$k],$newK,$content_two);
				}
			}
		}
		if($type == 1){
			$data['content'] = $content_two;
		}else if($type == 2){
			//返回当前数组出现次数最多的关键字
			$content_threePro = $content_three;
			rsort($content_threePro);
			if($content_threePro[0] != 0){
				$maxDis = array_search($content_threePro[0],$content_three);
				//给新标题赋值
				if($maxDis == '其他'){
					if($content_threePro[1] != 0){
						$maxDis = array_search($content_threePro[1],$content_three);
					}
				}
				preg_match_all('/'.$maxDis.'/',$title,$mm);
				if(!$mm[0]){
					$data['title'] = $maxDis.$title;
				}else{
					$data['title'] = $title;
				}
				$data['content'] = $content_two;
			}
		}
		return $data;
	}