<?php
$configarr=require APP_PATH.'Common/Common/webconfig.php';
$dbarr = array(
	//'配置项'=>'配置值'
		//临时数据库配置
		'DB_DSN' => 'mysql://root:@127.0.0.1:3306/hopethree',
		// 'DB_DSN' => 'mysql://hopenoah_web:Hopenoahweb123@rdsnufga77geu1ywqi3xuo.mysql.rds.aliyuncs.com:3306/hopethree',
		'DB_TYPE' =>'mysqli',
		// URL地址不区分大小写
		'URL_CASE_INSENSITIVE' => true,
		'URL_MODEL' => '3',
		'MODULE_ALLOW_LIST' => array('Home','Admin','Mobile'),
		'DEFAULT_MODULE' =>  'Home',
	/* 控制器地址 */
		'APPURL' =>  __APP__.'/index.php',
		
	//验证码过期时间
		'VERIFY_SMS_USER_REG_TIME' => 18000,
		'VERIFY_SMS_USER_FINDPASS_TIME' => 18000,
		'VERIFY_EMAIL_USER_REG_TIME' => 18000,
		'VERIFY_EMAIL_USER_FINDPASS_TIME' => 18000,
);

return array_merge($configarr,$dbarr);
