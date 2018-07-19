<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_THEME'    =>    'default',
	'SHOW_PAGE_TRACE'        =>true,   // 显示页面Trace信息
	'DB_DSN' => 'mysql://root:@127.0.0.1:3306/test',
	/* 模板相关配置 */
	'TMPL_PARSE_STRING'=>array(
		'__JS__' => __ROOT__.'/Public/Home/js',
		'__CSS__'=>__ROOT__.'/Public/Home/css',
		'__IMG__'=>__ROOT__.'/Public/Home/img',
		'__ACC__'=>__ROOT__.'/Public/Home/accessory',
	),
);