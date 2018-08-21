<?php
namespace Common\Model;
use Think\Model\ViewModel;
class CategoryViewModel extends ViewModel{
	protected $viewFields = array(
		'category' => array('*','_type'=>'LEFT'),
		'model'    => array(
			'name'=>'modelname',
			'tablename'=>'tablename',
			'_on'=>'category.modelid=model.id'
		),
	);
}