<?php
namespace Manage\Controller;
use Common\Lib\Category;
class ArticleController extends CommonController {
	function index(){
		//查询子栏目
		$id = I('id',0,'intval');
		$cate = getCategory(3);
		$cateChild = Category::getChildren($cate, $id);
		$subcate = Category::toDownLayer($cateChild, 'child', $id);
		//查询父栏目
		$cateParent = Category::getParents($cate,$id);

		//查询文章列表,分页显示
		if($id){
			$childrenIdArr = Category::getChildrenId($cate,$id,1);
			$where = array(
				'article.status' => 0,
				'article.cid' => array('in',$childrenIdArr)
			);
		}else{
			$where = array('article.status' => 0);
		}

		//判断是否搜索关键字
		$keyword = I('keyword','','htmlspecialchars,trim');
		if(!empty($keyword)){
			$where['article.title'] = array('LIKE',"%{$keyword}%");
			$parameter = array(
				'keyword' => $keyword,
			); // 分页参数
		}

		$count = D('ArticleView')->where($where)->count(); //总记录数
		$pageSize = 3; //每页显示记录数
		$page = new \Think\Page($count,$pageSize,$parameter);
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

		$articleList = D('ArticleView')->field('id,cid,catename,title,color,publishtime,click,litpic')->where($where)->order('article.id desc')->limit($page->firstRow.','.$page->listRows)->select();

		$this->assign('subcate',$subcate);
		$this->assign('cateParent',$cateParent);
		$this->assign('type','文章列表');
		$this->assign('id',$id);
		$this->assign('articleList',$articleList);
		$this->assign('page',$page->show());
		$this->display();
	}

	function add(){
		$id = I('id',0,'intval');
		$cate = getCategory(3);
		$cate = Category::toLevel($cate,'&nbsp;&nbsp;&nbsp;&nbsp;',0);
		$this->assign('id',$id);
		$this->assign('cate',$cate);
		$this->display();
	}

	function insert(){
		$data = I('post.');
		$data['cid'] = I('cid',0,'intval');
		$data['title'] = I('title','','htmlspecialchars,trim');
		$data['subhead'] = I('subhead','','htmlspecialchars,trim');
		$data['author'] = I('author','','htmlspecialchars,trim');
		$data['copyfrom'] = I('copyfrom','','htmlspecialchars,trim');
		$data['content'] = I('content','','');
		if($data['publishtime']){
			$data['publishtime'] = I('publishtime','','strtotime');
		}else{
			$data['publishtime'] = time();
		}
		$data['aid'] = session( C('USER_AUTH_KEY') );

		$_validate = array(
			array('title','require','标题不能为空'),
			array('title','','标题名称不能重复',0,'unique',1),
			array('cid','require','请选择所属栏目'),
			array('author','require','请填写作者'),
			array('content','require','内容不能为空'),
		);
		$article = M('article');
		if( !$article->validate($_validate)->create() ){
			$this->error( $article->getError() );
		}else{
			if( $article->add($data) ){
				$this->success('添加成功',U( 'article/index',array('id'=>$data['cid']) ));
			}else{
				if($data['litpic']){
					//删除所有上传图片
					delUploadImg($data['litpic']);
				}
				$this->error('添加失败');
			}
		}
	}

	function edit(){
		$id = I('id');
		$cid = I('cid');
		$article = M('article')->find($id);
		$cate = getCategory(3);
		$cate = Category::toLevel($cate,'&nbsp;&nbsp;&nbsp;&nbsp;',0);
		$this->assign('id',$id);
		$this->assign('cid',$cid);
		$this->assign('article',$article);
		$this->assign('cate',$cate);
		$this->display();
	}

	function update(){
		$data = I('post.');
		$data['title'] = I('title','','htmlspecialchars,trim');
		$data['subhead'] = I('subhead','','htmlspecialchars,trim');
		$data['author'] = I('author','','htmlspecialchars,trim');
		$data['copyfrom'] = I('copyfrom','','htmlspecialchars,trim');
		$data['publishtime'] = I('publishtime','','strtotime');
		$data['updatetime'] = time();

		$_validate = array(
			array('title','require','标题不能为空'),
			array('title','','标题名称不能重复',0,'unique',1),
			array('cid','require','请选择所属栏目'),
			array('author','require','请填写作者'),
			array('content','require','内容不能为空'),
		);
		$article = M('article');
		if( !$article->validate($_validate)->create() ){
			$this->error( $article->getError() );
		}else{
			if( $article->save($data) ){
				if($data['litpic']!=$data['orgnl_pic'] || $data['litpic']==''){
					//删除修改前的原图片
					delUploadImg($data['orgnl_pic']);
				}
				$this->success('修改成功',U( 'Article/index',array('id'=>$data['cid']) ));
			}else{
				if($data['litpic']){
					//删除所有上传图片
					delUploadImg($data['litpic']);
				}
				$this->error('添加失败');
			}
		}
	}

	function delete(){
		$id = I('id');
		$cid = I('cid');
		$flag = I('flag');
		if(!$flag){
			$result = M('article')->where("id={$id}")->setField('status',1);
			if($result){
				$this->success('删除成功',U( 'Article/index',array('id'=>$cid) ));
			}else{
				$this->error('删除失败');
			}
		}else{
			$this->delBatch();
		}
	}

	function delBatch(){
		$idArr = I('key');
		$cid = I('id');
		if(!is_array($idArr)){
			$this->error('请选择要删除的文章');
		}
		$result = M('article')->where(array('id'=>array('in',$idArr)))->setField('status',1);
		if($result){
			$this->success('删除成功',U( 'Article/index',array('id'=>$cid) ));
		}else{
			$this->error('删除失败');
		}
	}

	function recycle(){

		$id = I('id',0,'intval');
		$cate = getCategory(3);
		if($id){
			$childrenIdArr = Category::getChildrenId($cate,$id,1);
			$where = array(
				'article.status' => 1,
				'article.cid' => array('in',$childrenIdArr)
			);
		}else{
			$where = array('article.status' => 1);
		}

		$count = D('ArticleView')->where($where)->count(); //总记录数
		$pageSize = 3; //每页显示记录数
		$page = new \Think\Page($count,$pageSize);
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

		$articleList = D('ArticleView')->field('id,cid,catename,title,color,publishtime,click,litpic')->where($where)->order('article.id desc')->limit($page->firstRow.','.$page->listRows)->select();

		$this->assign('type','回收站列表');
		$this->assign('id',$id);
		$this->assign('articleList',$articleList);
		$this->assign('page',$page->show());
		$this->display('index');
	}

	function restore(){
		$id = I('id');
		$cid = I('cid');
		$flag = I('get.flag');
		if(!$flag){
			$result = M('article')->where("id={$id}")->setField('status',0);
			if($result){
				$this->success('还原成功',U( 'Article/index',array('id'=>$cid) ));
			}else{
				$this->error('还原失败');
			}
		}else{
			$this->restoreBatch();
		}
	}

	function restoreBatch(){
		$idArr = I('key');
		$cid = I('get.id');
		if(!is_array($idArr)){
			$this->error('请选择要还原的文章');
		}
		$result = M('article')->where(array('id'=>array('in',$idArr)))->setField('status',0);
		if($result){
			$this->success('还原成功',U( 'Article/index',array('id'=>$cid) ));
		}else{
			$this->error('还原失败');
		}
	}

	function clear(){
		$id = I('id');
		$cid = I('cid');
		$flag = I('flag');
		$pic = M('article')->where("id={$id}")->getField('litpic');
		if(!$flag){
			$result = M('article')->where("id={$id}")->delete();
			if($result){
				if($pic){
					delUploadImg($pic);
				}
				$this->success('彻底删除成功',U( 'Article/recycle',array('id'=>$cid) ));
			}else{
				$this->error('彻底删除失败');
			}
		}else{
			$this->clearBatch();
		}
	}

	function clearBatch(){
		$idArr = I('key');
		$cid = I('id');
		$pic = M('article')->field('litpic')->where(array('id'=>array('in',$idArr)))->select();
		if(!is_array($idArr)){
			$this->error('请选择要删除的文章');
		}
		$result = M('article')->where(array('id'=>array('in',$idArr)))->delete();
		if($result){
			// 循环遍历删除已上传图片
			foreach($pic as $val){
				if($val['litpic']){
					delUploadImg($val['litpic']);
				}
			}	
			$this->success('彻底删除成功',U( 'Article/recycle',array('id'=>$cid) ));
		}else{
			$this->error('彻底删除失败');
		}
	}

	function move(){
		$idArr = I('key');
		$cid = I('id');
		$cate = getCategory(3);
		$cate = Category::toLevel($cate,'&nbsp;&nbsp;&nbsp;&nbsp;',0);
		foreach ($idArr as $ids) {
			$id .= $ids.',';
		}
		$this->assign('id',rtrim($id,','));
		$this->assign('cid',$cid);
		$this->assign('cate',$cate);
		$this->display();
	}

	function doMove(){
		$cid = I('cid');
		$id = explode(',',I('id'));
		if(!$cid){
			$this->error('请选择栏目');
		}
		if(!is_array($id)){
			$this->error('请选择要移动的文章');
		}
		$result = M('article')->where(array('id'=>array('in',$id)))->setField('cid',$cid);
		if($result){
			$this->success('移动成功',U( 'Article/index',array('id'=>$cid) ));
		}else{
			$this->error('移动失败');
		}
	}

}
