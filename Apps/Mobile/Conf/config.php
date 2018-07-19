<?php
	return array(
		//'配置项'=>'配置值'
		'DEFAULT_THEME'    =>    'default',
		'SHOW_PAGE_TRACE'        =>true,   // 显示页面Trace信息
		//默认错误跳转对应的模板文件
		'TMPL_ACTION_ERROR' => MODULE_PATH.'/View/default/Public/error.html',
		/* 模板相关配置 */
		'TMPL_PARSE_STRING'=>array(
			'__JS__' 					=> __ROOT__.'/Public/Mobile/js',
			'__CSS__'					=> __ROOT__.'/Public/Mobile/css',
			'__IMG__'					=> __ROOT__.'/Public/Mobile/image',
		),
		// 开启路由
		'URL_ROUTER_ON'   => true, 
		'URL_ROUTE_RULES'=>array(
		),
	);


