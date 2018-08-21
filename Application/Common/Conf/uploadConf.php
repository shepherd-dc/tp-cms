<?php 
return array(
	'CFG_UPLOAD_IMG_EXT'  => 'jpeg,jpg,png,gif', //允许的上传文件类型
	'CFG_UPLOAD_ROOTPATH' => './Uploads/',        //上传根目录
	'CFG_UPLOAD_MAXSIZE'  => '2097152',          //允许上传文件的大小 2M
	'CFG_IMGTHUMB_SIZE'   => '300*300',          //缩略图的尺寸
	'CFG_IMGTHUMB_TYPE'   => 0,                  //0-原图等比例缩放 1-固定大小截取
);