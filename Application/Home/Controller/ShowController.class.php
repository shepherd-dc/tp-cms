<?php
namespace Home\Controller;
use Think\Controller;
use Common\Lib\Category;
class ShowController extends Controller {
	function index(){
		$cid = I('cid',0,'intval');
		$id = I('id',0,'intval');
		if(!$cid || !$id){
			$this->error('参数错误');
		}
		$cate = getCategory(1);
		$self = Category::getSelf($cate,$cid);
		if(empty($self)){
			$this->error('栏目不存在');
		}
		$template_show = substr( $self['template_show'], 0, strpos($self['template_show'],'.') );

		$content = M($self['tablename'])->where(array('status'=>0,'id'=>$id))->find();
		$content['content'] = strip_tags( html_entity_decode($content['content']) );
		if(empty($content)){
			$this->error('内容不存在');
		}

		//得到指定分类下的所有子栏目，只保留一级子栏目
		$sonList = Category::getChildren($cate,$cid);
		foreach ($sonList as $key => $value) {
			if($value['pid']!=$cid){
				unset($sonList[$key]);
			}else{
				$sonList[$key]['url'] = get_list_url($value);
			}
		}

		$this->assign('cateInfo',$self);
		$this->assign('sonList',$sonList);
		$this->assign('content',$content);

		$this->display($template_show);
	}
}