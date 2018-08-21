<?php 
/**
 * 检测验证码
 * @param     string $code
 * @param     integer $id
 * @return    boolean
 */
function checkVerify( $code, $id = 1 ){
	$verify = new \Think\Verify();
	return $verify->check( $code, $id );
}

/**
 *用户密码加密
 * @param     string $password
 * @param     string $encrypt
 * @return    multitype:string Ambigous <string, string>
 */
function getPassword( $password, $encrypt = '' ){
	$pwd = array();
	$pwd['encrypt'] = $encrypt ? $encrypt : getRandomstr();
	$pwd['password'] = md5( md5($password).$pwd['encrypt'] );
	return $encrypt ? $pwd['password'] : $pwd;
}

/**
 * 默认生成4位随机字符串
 * @param     integer $length
 * @return    string
 */
function getRandomstr( $length = 4 ){
	$string = join( '',array_merge( range(0,9),range('a','z'),range('A','Z') ) );
	return substr( str_shuffle($string),0,$length );
}

/**
 * 获取模板目录和文件
 * @param     [type]      $pathname       路径
 * @param     integer     $fileFlage      默认0代表所有的目录和文件，1代表目录，2代表文件
 * @param     string      $patterns       * 所有内容
 * @return    Ambigous    <null, multitype:string>
 */
function get_file_folder_list( $pathname, $fileFlage = 0, $patterns = '*' ){
	$fileArr = array();
	$pathname = rtrim($pathname,'/').'/';
	$list = glob($pathname.$patterns); 
	foreach ($list as $file) {
		switch ($fileFlage) {
			case 0:
				$fileArr[] = basename($file);
				break;
			
			case 1:
				if( is_dir($file) ){
					$fileArr[] = basename($file);
				}
				break;

			case 2:
				if( is_file($file) ){
					$fileArr[] = basename($file);
				}
				break;
		}
	}
	if( empty($fileArr) ){
		$fileArr = null;
	}
	return $fileArr;
}


/**
 * 按照条件得到所有分类
 * @param     integer     $status [description]
 * @return    Ambigous    <multitype:, mixed, void, boolean, null>
 */
function getCategory( $status = 0 ){
	$cate_sname = 'fCategory_'.$status;
	$cate_arr = S($cate_sname);
	if(!$cate_arr){
		if($status==1){
			$cate_arr = D('CategoryView')->where('category.status=1')->order('category.sort,category.id')->select();
		}else if($status==2){
			$cate_arr = D('CategoryView')->order('category.sort,category.id')->select();
		}else if($status==3){
			$cate_arr = D('CategoryView')->where('category.modelid=1')->order('category.sort,category.id')->select();
		}else{
			$cate_arr = D('CategoryView')->order('category.sort,category.id')->select();
		}
		if( !isset($cate_arr) ){
			$cate_arr = array();
		}
		F($cate_sname,$cate_arr);
	}
	return $cate_arr;
}

/**
 * 生成指定格式的图片
 * @param     string      $str    [description]
 * @param     integer     $width  [description]
 * @param     integer     $height [description]
 * @param     boolean     $rnd    [description]
 * @return    [type]              [description]
 */
function getPicture($str, $width=0, $height=0, $rnd=false){
	$ext = 'jpg';
	$ext_dest ='jpg';
	
	//-300*300.jpg"
	if( !empty($str) ){
		$str = preg_replace('/-(\d)*(\d)'.$ext_dest.'$/i', '', $str);
		$ext = explode('.',$str);
		$ext = end($ext);
	}
	if( empty($ext) || !in_array(strtolower($ext), array('jpeg','jpg','png','gif')) ){
		$str = '';
	}
	if($width==0){
		return $str;
	}
	$height = $height==0 ? '' : $height;
	$rndstr = $rnd ? '?random='.time() : '';
	return $str.$rndstr.'-'.$width.'*'.$height.'.'.$ext_dest;

}


/**
 * 删除所有上传图片
 * @param     string      $filename
 * @return    [type]      [description]
 */
function delUploadImg($filename){
	if( $filename ){

		$fileInfo = pathinfo($filename);
		$dirname = $fileInfo['dirname'];
		$basename = $fileInfo['basename'];
		$arr = explode('_',$basename);
		$orgnl_basename = $arr[1];
		$orgnl_pic = C('CFG_UPLOAD_ROOTPATH').$dirname.'/'.$orgnl_basename;
		$thumb_pic = C('CFG_UPLOAD_ROOTPATH').$filename;

		unlink($orgnl_pic);
		unlink($thumb_pic);
	}
}

/**
 * 跳转到链接地址
 * @param     string      $weburl [网址或者U方法的参数]
 * @param     integer     $rnd    [是否添加随机数]
 * @param     integer     $flag   [是否转换index.php]
 * @return    string              [description]
 */
function go_link($weburl, $rnd=0, $flag=1){
	$weburl = U($weburl);
	if($flag){
		$search = $_SERVER['SCRIPT_NAME']; //string(15) "/icms/imooc.php"
		$replace = rtrim( dirname($search),"\\/" ).'/index.php';
		$weburl = str_replace($search, $replace, $weburl);
	}
	if($rnd){
		$weburl .='#'.rand(1000,time());
	}
	return $weburl;
}

/**
 * 查看指定分类下的指定新闻
 * @param     array       $article [description]
 * @param     string      $act     [description]
 * @return    [type]               [description]
 */
function view_url($article,$act='Show/index'){
	$url = go_link( C('DEFAULT_MODULE').'/'.$act.'?cid='.$article['cid'].'&id='.$article['id'], 1 );
	return $url;
}

/**
 * 得到指定栏目的URL(列表页)
 * @param     array       $cate [description]
 * @param     integer     $id   [description]
 * @return    [type]            [description]
 */
function get_list_url($category){
	$url = '';
	if(empty($category)){
		return $url;
	}
	$url = U('List/index',array('cid'=>$category['id']));
	return $url;
}

/**
 * 得到指定文章的URL(内容页)
 * @param     array       $cate [description]
 * @param     integer     $id   [description]
 * @return    [type]            [description]
 */
function get_content_url($article){
	$url = '';
	if(empty($article)){
		return $url;
	}
	$url = U('Show/index',array('cid'=>$article['cid'],'id'=>$article['id']));
	return $url;
}



?>