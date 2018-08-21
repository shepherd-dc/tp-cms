<?php
namespace Home\Controller;
use Think\Controller;
use Common\Lib\Category;
class ListController extends Controller {
	function index(){
		$cid = I('cid',0,'intval');
		$cate = getCategory(1);
		$self = Category::getSelf($cate,$cid);
		if(empty($self)){
			$this->error('栏目不存在');
		}
		$template_list = substr( $self['template_list'], 0, strpos($self['template_list'],'.') );
		if(empty($template_list)){
			$this->error('模板不存在');
		}

		switch ($self['tablename']) {
			case 'article': 
				//得到指定分类下的所有子栏目，只保留一级子栏目
				$sonList = Category::getChildren($cate,$cid);
				foreach ($sonList as $key => $value) {
					if($value['pid']!=$cid){
						unset($sonList[$key]);
					}else{
						$sonList[$key]['url'] = get_list_url($value);
					}
				}
				$this->assign('sonList',$sonList);
				//得到当前分类下的新闻列表	
				if($cid>0){
					$ids = Category::getChildrenId($cate,$cid,1);
					$where = array('status'=>0,'cid'=>array('in',$ids));
				}else{
					$where = array('status'=>0);
				}
				$articleList = M('article')->field('id,cid,title,publishtime')->where($where)->order('id desc')->select();
				foreach ($articleList as $key => $value) {
					$articleList[$key]['url'] = get_content_url($value);
				}
				$this->assign('articleList',$articleList);
				break;
			
			case 'page': //得到指定单页的内容
				$content = M('category')->field('name,content,description')->where(array('status'=>1,'id'=>$cid))->find();
				$content['content'] = strip_tags( html_entity_decode($content['content']) );
				if(empty($content)){
					$this->error('内容不存在');
				}
				$this->assign('content',$content);				
				break;
		}

		$this->assign('cateInfo',$self);
		$this->display($template_list);
	}
}