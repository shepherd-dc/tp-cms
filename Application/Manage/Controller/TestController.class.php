<?php
namespace Manage\Controller;
use Think\Controller;
class TestController extends Controller {
	public function index(){
		$search = $_SERVER['SCRIPT_NAME'];
		dump($search);
		$this->display();
		// echo '<pre>';
		// print_r(C());
	}
	public function insert(){
		if(IS_POST){
			$admin = M('Admin');
			$user = $admin->create();
			$passwordarr = getPassword($user['password']);
			$data = array(
				'username' => $user['username'],
				'password' => $passwordarr['password'],
				'encrypt'  => $passwordarr['encrypt'],
				);
			if( $admin->add($data) ){
				$this->success('添加成功',U('Manager/index'));
			}else{
				$this->error('添加失败');
			}
		}else{
			$this->error('提交数据方式有误');
		}
	}

	public function insert2(){
		if(IS_POST){
			$admin = D('Admin');
			//自动验证
			if (!$admin->create()){
			    $err = $admin->getError();
			    //遍历错误提示数组
			    $str = '';
			    foreach ($err as $error) {
			    	$str .= $error . '<br>';
			    }
			    $this->error($str,U('add'));
			}else{
				$admin->create();
				$admin->time = time();
				//密码加密算法，返回数组[加密密码,盐值]
				$passwordarr = getPassword($admin->password);
				$admin->password = $passwordarr['password'];
				$admin->encrypt = $passwordarr['encrypt'];

				if( $admin->add() ){
					$this->success('添加成功',U('Manager/index'));
				}else{
					$this->error('添加失败');
				}
			}		
		}else{
			$this->error('提交数据方式有误');
		}
	}

}