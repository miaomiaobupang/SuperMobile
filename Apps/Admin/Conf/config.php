<?php
return array(
	//'配置项'=>'配置值'
	
	'SHOW_PAGE_TRACE'        =>true,   // 显示页面Trace信息
	'WEBURL'        =>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php',   // 地址
	'ADMINWEBURL'        =>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/admin.php',   // 地址
	'SUPERID'        =>'1',   // 后台超级管理员
	'DOCUMENTS'=>$_SERVER['DOCUMENT_ROOT'].__ROOT__,  //系统地址
	/* 模板相关配置 */
	'TMPL_PARSE_STRING'=>array(
		'__JS__' => __ROOT__.'/Public/Admin/js',
		'__CSS__'=>__ROOT__.'/Public/Admin/css',
		'__IMG__'=>__ROOT__.'/Public/Admin/images',
		'__ACC__'=>__ROOT__.'/Public/Admin/accessory',
		'__MODULE__'=>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/admin.php',
		'__PLUG__'=>__ROOT__.'/Public/Plug',
		'__PLUGS__'=>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/Public/Plug/ueditor',
		'__FACECARD__'				=>__ROOT__.'/Public/Uploads/facecard',		//身份证正面路径
		'__BACKCARD__'				=>__ROOT__.'/Public/Uploads/backcard',		//身份证反面路径
		'__BUSINESSAUTH__'			=>__ROOT__.'/Public/Uploadssinessauth',		//企业营业执照路径
		'__BUSINESSBOOKAUTH__'		=>__ROOT__.'/Public/Uploadssinessbookauth',		//企业授权书路径
		'__WEBURL__'=>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/admin.php',
		'__URL__'=>'http://'.$_SERVER['HTTP_HOST'].__ROOT__,
		'__BIGIMG__'=>__ROOT__.'/Public/Uploads/exhibitionImg/exhibition/exhibitionBig',
		'__PUBLICS__'=>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/Public/Theme/Fashion/assets/js',
		'__SMALLIMG__'=>__ROOT__.'/Public/Uploads/exhibitionImg/exhibition/exhibitionSmall',
		'__EXHIBITION__'=>__ROOT__.'/Public/Uploads/exhibitionImg/booth/boothPic',
		'__PAVILIONBIG__'=>__ROOT__.'/Public/Uploads/exhibitionImg/gallery/galleryBig',
		'__PAVILIONSMALL__'=>__ROOT__.'/Public/Uploads/exhibitionImg/gallery/gallerySmall',
		'__MESSAGEIMG__'=>__ROOT__.'/Public/Uploads/slideImg/message',
		'__ARTICLEIMG__'=>__ROOT__.'/Public/Uploads/slideImg/article',
		'__PLANIMG__'=>__ROOT__.'/Public/Uploads/exhibitionImg/gallery/galleryPlan',
	),
);