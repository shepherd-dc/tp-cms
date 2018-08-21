<?php
namespace Manage\Controller;

class PublicController extends CommonController {
    public function main(){
    	$this->assign('software',$_SERVER['SERVER_SOFTWARE']);
    	$this->assign('os',PHP_OS);
       	$this->assign('environment_upload',ini_get('file_uploads') ? ini_get('upload_max_filesize') : '不支持文件上传');
       	$this->display();
    }

    public function upload(){
    	if( empty($_FILES) ){
    		echo json_encode(array(
    			'url' => '',
    			'name' => '',
    			'original' => '',
    			'state' => '请选择要上传的文件',
    		));
    	}else{
    		$info = $this->_uploadPicture(); //图片上传缩略，并返回结果信息

    		if( isset($info) && is_array($info) ){
    			foreach($info as $k=>$v){
    				$v['url'] = $v['savepath'].$v['savename'];
    				$v['turl'] = $v['savepath'].'thumb_'.$v['savename'];
    				$v['size'] = round($v['size']/1024,2);
    				$new_info[] = $v;
    			}
    			echo json_encode( array('state'=>'SUCCESS','info'=>$new_info) );
    		}else{
    			echo json_encode( array(
					'url' => '',
	    			'name' => '',
	    			'original' => '',
	    			'state' => '上传失败'.$info
    			) );
    		}
    	}
    }

    /**
     * 文件上传 图片缩略处理函数
     */
    public function _uploadPicture(){
    	
		//文件上传配置
    	$upload = new \Think\Upload();
    	$upload->maxSize = C('CFG_UPLOAD_MAXSIZE'); //允许上传文件的大小（2M）
    	$upload->ext = explode( ',',C('CFG_UPLOAD_IMG_EXT') ); //允许的上传文件类型
    	$upload->rootPath = C('CFG_UPLOAD_ROOTPATH'); //上传根目录
    	$upload->savePath = 'img/'; //上传子目录
    	$upload->saveName = array('uniqid', ''); //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    	$upload->replace =ture; //重名覆盖

    	//文件上传
    	$info = $upload->upload();

    	//读取缩略图的配置
    	$t_size = explode( '*', C('CFG_IMGTHUMB_SIZE') ); //分割300*300，返回数组
    	$t_array = array( 'w'=>intval($t_size[0]), 'h'=>intval($t_size[1]) );
        $thumb_type = C('CFG_IMGTHUMB_TYPE') ? 3 : 1;

		//生成缩略图
    	if( $info ){
    		if( !empty($t_array) ){
    			$thinkImage = new \Think\Image();
    			foreach($info as $k => $files){
    				$save_path = $upload->rootPath.$files['savepath'];// /icms/Uploads/img/2017-11-12/
    				$save_name = $files['savename']; //5a07c65ad4393.jpg
    				$thinkImage->open($save_path.$save_name)->thumb($t_array['w'], $t_array['h'], $thumb_type)->save($save_path.'thumb_'.$save_name);
    			}
    		}
    		return $info;
    	}else{
    		return $upload->getError();
    	}
    }

    public function editor($editor_id='content', $width='600', $height='300'){
        $dataPath = __ROOT__.'/Date';
        //实例化编辑器：var ue = UE.getEditor('content');
        $getEditor .= 'UE.getEditor("'.$editor_id.'");'; 
        $str = <<<str
        window.UEDITOR_HOME_URL = "$dataPath/ueditor/";
        window.onload = function(){
            window.UEDITOR_CONFIG.initalFrameWidth = {$width};
            window.UEDITOR_CONFIG.initalFrameHeight = {$height};
            {$getEditor}
        }
        document.write('<script src="$dataPath/ueditor/ueditor.config.js"></script>');
        document.write('<script src="$dataPath/ueditor/ueditor.all.js"></script>');
str;
        echo $str;
        exit;
    }


}