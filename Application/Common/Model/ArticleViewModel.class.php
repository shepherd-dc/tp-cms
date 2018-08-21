<?php
namespace Common\Model;
use Think\Model\ViewModel;
class ArticleViewModel extends ViewModel{
	protected $viewFields = array(
		'article' => array('*','_type'=>'LEFT'),
		'category'    => array(
			'name'=>'catename',
			'_on'=>'article.cid=category.id'
		),
	);
}