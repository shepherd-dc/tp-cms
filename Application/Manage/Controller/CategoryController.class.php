<?php
namespace Manage\Controller;
use Common\Lib\Category;
class CategoryController extends CommonController {
	function index(){
		$cate = D('CategoryView')->order('category.sort,category.id')->select();
		$cate = Category::toLevel($cate,'&nbsp;&nbsp;&nbsp;&nbsp;',0);
		$this->assign('cate',$cate);
		$this->display();
	}

	function add(){
		$pid = I('pid',0,'intval');
		$cate = M('category')->field('id,pid,name')->order('sort')->select();
		$cate = Category::toLevel($cate,'---',0);
		
		$modelList = M('model')->field('id,name')->where('status=1')->select();
		
		$styleListList = get_file_folder_list('./Public/Home/default',2,'List_*');
		$styleShowList = get_file_folder_list('./Public/Home/default',2,'Show_*');

		$addCate = array(
			'pid' => $pid,
			'modelList' => $modelList,
			'cate' => $cate,
			'styleListList' => $styleListList,
			'styleShowList' => $styleShowList,
			);
		$this->assign('addCate',$addCate);
		$this->display();
	}

	function insert(){
		$_validate = array(
			array('name','require','栏目名称必须填写'),
			array('name','','栏目名称不能重复',0,'unique',1),
			array('template_list','require','请选择列表模板'),
			array('template_show','require','请选择内容页模板'),
		);
		$category = M('category');
		if( !$category->validate($_validate)->create() ){
			$this->error( $category->getError() );
		}else{
			if( $category->add() ){
				$this->success('添加成功',U('Category/index'));
			}else{
				$this->error('添加失败');
			}
		}	
	}

	function edit(){
		$id = I('id',0,'intval');
		$data = M('category')->find($id);
		if(!$data){
			$this->error('栏目不存在');
		}

		$cate = M('category')->field('id,pid,name')->order('sort')->select();
		$cate = Category::toLevel($cate,'---',0);
		$modelList = M('model')->field('id,name')->where('status=1')->select();
		$styleListList = get_file_folder_list('./Public/Home/default',2,'List_*');
		$styleShowList = get_file_folder_list('./Public/Home/default',2,'Show_*');

		$this->assign('data',$data);
		$this->assign('cate',$cate);
		$this->assign('modelList',$modelList);
		$this->assign('styleListList',$styleListList);
		$this->assign('styleShowList',$styleShowList);

		$this->display();
	}

	function update(){
		if(!IS_POST) E('数据提交方式有误');
		//表单自动验证
		$_validate = array(
			array('name','require','栏目名称不能为空'),
			array('name','','栏目名称不能重复',0,'unique',2),
			array('modelid','require','请选择内容模型'),
			array('template_list','require','请选择列表模板'),
			array('template_show','require','请选择内容页模板'),
		);
		$category = M('category');
		$result = $category->validate($_validate)->create();
		if(!$result){
			$this->error( $category->getError() );
		}else{
			if( $result['id']==$result['pid'] ){
				$this->error('不能将自己设置为自己的子栏目');
			}
			if( $category->save() ) {
				$this->success('修改成功',U('Category/index'));
			}else{
				$this->error('修改失败');
			}
		}
	}

	function delete(){
		$id = I('id');
		if( M('category')->where("pid={$id}")->count() ){
			$this->error('请先删除本栏目下的子栏目');
		}
		//以后要先删除栏目下的文章
		if( M('category')->delete($id) ){
			$this->success('删除成功',U('Category/index'));
		}else{
			$this->error('删除失败');
		}
	}

	function sort(){
		$sortlist=I('sortlist',array(),'intval');
		foreach ($sortlist as $key => $value) {
			$data = array(
				'id' => $key,
				'sort' => $value
			);
			M('category')->save($data);
		}
		$this->success('更新成功',U('Category/index'));
	}

}