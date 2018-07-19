	<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_THEME'    =>    'default',
	'SHOW_PAGE_TRACE'        =>true,   // 显示页面Trace信息
	'WEBURL'        =>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php',   // 地址
	'WEBURLS'        =>'http://'.$_SERVER['HTTP_HOST'].__ROOT__,   // 地址
	'TEMPIMG'        =>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/Public/Home/tempImg/',   // 地址
	'NEWSURL'					=> 'http://news.superdoctor.cn',
	'WENDA'					=> 'http://wenda.superdoctor.cn',
	//默认错误跳转对应的模板文件
		'TMPL_ACTION_ERROR' => MODULE_PATH.'/View/default/Public/error.html',
	// //默认成功跳转对应的模板文件
		// 'TMPL_ACTION_SUCCESS' => MODULE_PATH.'/View/default/Public/success.html',
		// 'TMPL_EXCEPTION_FILE'   =>  MODULE_PATH.'/View/default/Public/exception.html',// 异常页面的模板文件
		'EBIGIMG'	=> __ROOT__.'/Public/Uploads/',
	/* 模板相关配置 */
	'TMPL_PARSE_STRING'=>array(
		'__JS__' 					=> __ROOT__.'/Public/Home/js',
		'__CSS__'					=> __ROOT__.'/Public/Home/css',
		'__IMG__'					=> __ROOT__.'/Public/Home/images',
		'__IMGS__'					=> __ROOT__.'/Public/Home/foreastImg',
		'__ACC__'					=> __ROOT__.'/Public/Home/accessory',
		'__URLS__'=>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/admin.php',
		
		'__WEBURL__'				=> 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php',
		'__URL__'					=> 'http://'.$_SERVER['HTTP_HOST'].__ROOT__,
		'__COMMON__'					=> 'http://superdoctor.cn/admin.php',
		'__NEWSURL__'					=> 'http://news.superdoctor.cn',
		'__WENDA__'					=> 'http://wenda.superdoctor.cn',
		'__YOYUAN__'					=> 'http://yiyuan.superdoctor.cn',
		'__YISHENG__'					=> 'http://yisheng.superdoctor.cn',
		'__MESSAGEIMG__'			=> 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/Public/Home/images',
	),
	// 开启路由
	'URL_ROUTER_ON'   => true, 
	'URL_ROUTE_RULES'=>array(
		'zhongji/:id\*'    => 'Entry/detail',
		'yisheng/:id\*'    => 'Entry/detail',
		'yiyuan/:id\*'    => 'Entry/detail',
		'yaowu/:id\*'    => 'Entry/detail',
		':id\d'    => 'Message/detail',
		'Hospital/:sync\*'    => 'Hospital/index',
		'Expert/:sync\*'    => 'Expert/index',
		// 'Message/:sync\*'    => 'Message/index',
		'Answer/:qid\d'    => 'Interlocution/questions',
		'diseaseList/:diseaseID\*'    => 'Entry/diseaseListEnv'
	),
);


