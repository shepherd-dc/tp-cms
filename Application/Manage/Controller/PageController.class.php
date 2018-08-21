<?php
namespace Manage\Controller;
use Common\Lib\Category;
class PageController extends CommonController {
	function index(){
		$id = I('id',0,'intval');
		$page = M('category')->field('id,name,content,description')->find($id);

		$this->assign('page',$page);
		$this->display();
	}

	function doPage(){
		if(IS_POST){
			$id = I('id');
			$data = M('category');
			$data->create();
			if( $data->save() ){
				$this->success('修改成功',U('Page/index',array('id'=>$id)));
			}else{
				$this->error('修改失败');
			}
		}
		

	}

}
