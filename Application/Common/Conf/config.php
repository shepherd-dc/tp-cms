 <?php
return array(
	'TMPL_FILE_DEPR'        =>  '_',                   //控制器_方法.html
	'TMPL_TEMPLATE_SUFFIX'  =>  '.html',               //模板后缀
	'LOAD_EXT_CONFIG'       =>  'db,uploadConf',       //加载扩展的数据库配置文件
	
	'SHOW_PAGE_TRACE'       =>  true, //页面调试助手开启
	'TMPL_ACTION_ERROR'     =>  './Data/resource/system/jump.html', //错误跳转页面
	'TMPL_ACTION_SUCCESS'   =>  './Data/resource/system/jump.html', //成功跳转页面
	'TMPL_EXCEPTION_FILE'   =>  './Data/resource/system/exception.html', //异常页面

	'URL_MODEL'             =>  2
);