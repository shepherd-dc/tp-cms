<?php
namespace Manage\Controller;

class IndexController extends CommonController {
    public function index(){
    	$this->assign('software',$_SERVER['SERVER_SOFTWARE']);
    	$this->assign('os',PHP_OS);
       	$this->assign('environment_upload',ini_get('file_uploads') ? ini_get('upload_max_filesize') : '不支持文件上传');
       
        $this->display();
    }

    public function getParentCate(){
    	$count = D('CategoryView')->where('pid=0')->count();
    	$list = D('CategoryView')->field('category.id as cid,category.name as cname,tablename')->where('pid=0')->order('category.sort desc,category.id')->select();
    	if( empty($list) ){
    		$list = array();
    	}
    	$listmenu = array('count'=>$count);
    	foreach($list as $v){
    		$listmenu['list'][] = array(
    			'id' => $v['cid'],
    			'name' => $v['cname'],
    			'url' => U( ucfirst($v['tablename']).'/index',array('id'=>$v['cid']) )
    		);
    	}
    	$this->ajaxReturn($listmenu);//把数组转换成json对象
    }
}