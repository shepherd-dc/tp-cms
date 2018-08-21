<?php
namespace Manage\Controller;

class ModelController extends CommonController {
	function index(){
		$list = M('model')->order('id')->select();
		$this->assign('list',$list);
		$this->display();
	}

	function add(){
		$styleListList = get_file_folder_list('./Public/Home/default',2,'List_*');
		$styleShowList = get_file_folder_list('./Public/Home/default',2,'Show_*');
		$this->assign('styleListList',$styleListList);
		$this->assign('styleShowList',$styleShowList);
		$this->display();
	}

	function insert(){
		//表单自动验证
		$_validate = array(
			array('name','require','模型名称必须填写'),
			array('name','','模型名称不能重复',0,'unique',1),
			array('tablename','require','附加表名必须填写'),
			array('tablename','','附加表名不能重复',0,'unique',1),
			array('template_list','require','请选择列表模板'),
			array('template_show','require','请选择内容页模板'),
		);
		$model = M('Model');
		$result = $model->validate($_validate)->create();
		if(!$result){
			$this->error( $model->getError() );
		}else{
			if( $model->add() ){
				$this->success('模型添加成功',U('Model/index'));
			}else{
				$this->error('模型添加失败');
			}
		}
	}

	function edit(){

		$id = I('id');
		$modelList = M('model')->find($id);
		$this->assign('modelList',$modelList);

		$styleListList = get_file_folder_list('./Public/Home/default',2,'List_*');
		$styleShowList = get_file_folder_list('./Public/Home/default',2,'Show_*');
		$this->assign('styleListList',$styleListList);
		$this->assign('styleShowList',$styleShowList);

		$this->display();
	}

	function update(){
		if(IS_POST){
			//表单自动验证
			$_validate = array(
				array('name','require','模型名称必须填写'),
				array('name','','模型名称不能重复',0,'unique',2),
				array('tablename','require','附加表名必须填写'),
				array('tablename','','附加表名不能重复',0,'unique',2),
				array('template_list','require','请选择列表模板'),
				array('template_show','require','请选择内容页模板'),
			);
			$model = M('Model');
			$result = $model->validate($_validate)->create();
			if(!$result){
				$this->error( $model->getError() );
			}else{
				if( $model->save() ) {
					$this->success('修改成功',U('Model/index'));
				}else{
					$this->error('修改失败');
				}
			}
		}
	}

	function delete(){
		$id = I('id');
		$data = D('ModelView')->where("model.id={$id}")->count('modelid');
		if( $data ){
			$this->error('此模型正在被使用，不能删除');
		}
		if( M('model')->delete($id) ){
			$this->success('删除成功',U('Model/index'));
		}else{
			$this->error('删除失败');
		}
	}
	

}