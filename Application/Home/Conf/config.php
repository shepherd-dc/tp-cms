<?php
return array(
	//URL访问模式： 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
	'URL_MODEL'         => 1,
	// PATHINFO模式下，各参数之间的分割符号
	'URL_PATHINFO_DEPR' => '/',
	
	//改变Home模块的模板文件目录
	'VIEW_PATH'         => './Public/'.MODULE_NAME.'/',
	// 默认模板主题名称
	'DEFAULT_THEME'     => 'default',

	//修改模板替换路径
	'TMPL_PARSE_STRING' => array(
		'__PUBLIC__' => __ROOT__.'/Public/'.MODULE_NAME.'/default/Public',
		'__DATA__'   => __ROOT__.'/Data',
		'__UPLOADS__'=> __ROOT__.'/Uploads/'
	),

	//预加载自定义标签库
	'TAGLIB_PRE_LOAD'   => 'Common\TagLib\Shepherd',
);