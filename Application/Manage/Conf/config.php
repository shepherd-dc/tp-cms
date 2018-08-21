<?php
return array(
	//修改模板替换路径
	'TMPL_PARSE_STRING' =>  array(
		'__PUBLIC__' => __ROOT__.ltrim(APP_PATH,'.').MODULE_NAME.'/View/Public',
		'__DATA__'   => __ROOT__.'/Data',
		'__UPLOADS__'=> __ROOT__.'/Uploads/'
	),
	
	'USER_AUTH_KEY'     =>  'adm_uid', //用户认证识别编号

	//设置静态缓存
	'HTML_CACHE_ON'     =>  false,
	'HTML_CACHE_RULES'  =>  array(
		'*'  =>  array('{$_SERVER.REQUEST_URI|md5}'),
	),
	'HTML_CACHE_TIME'   =>  '60',
);