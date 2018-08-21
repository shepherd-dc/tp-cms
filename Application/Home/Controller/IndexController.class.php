<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$category = M('category')->field('id,name')->where('pid=0 and status=1')->order('sort,id')->select();
    	$this->assign('category',$category);

    	$article = M('article');
    	$where = array(
    		'cid' => $category[0]['id'],
    		'status' => 0,
    		'litpic' => array('neq',''),
    	);
    	$news_img = $article->where($where)->order('id desc')->find();
    	$news_img['content'] = strip_tags( html_entity_decode($news_img['content']) );    	
    	$news_list = $article->field('id,cid,title,publishtime')->where("cid={$category[0]['id']} and status=0")->order('id desc')->limit(5)->select();
    	$this->assign('news_img',$news_img);
    	$this->assign('news_list',$news_list);

    	$where1 = array(
    		'cid' => $category[1]['id'],
    		'status' => 0,
    		'litpic' => array('neq',''),
    	);
    	$course_img = $article->where($where1)->order('id desc')->limit(4)->select();

    	foreach ($course_img as $key => $value) {
    		$course_img[$key]['content'] = strip_tags( html_entity_decode($value['content']) );
    	}
    	
    	$this->assign('course_img',$course_img);
    	
    	
        $this->display();
    }
}