<?php
namespace Common\Model;
use Think\Model\ViewModel;
class ModelViewModel extends ViewModel{
	protected $viewFields = array(
		'category' => array('modelid','name','_type'=>'RIGHT'),
		'model'    => array(
			'id'=>'mid',
			'name'=>'modelname',
			'_on'=>'category.modelid=model.id'
		),
	);
}