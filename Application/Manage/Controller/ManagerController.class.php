<?php
namespace Manage\Controller;

class ManagerController extends CommonController {
    public function index(){
    	$id = session( C('USER_AUTH_KEY') );
    	$admin = M('admin')->find($id);
    	$this->assign('admin',$admin);
        $this->display();
    }

    public function editInfo(){
    	$id = session( C('USER_AUTH_KEY') );
    	$admin = M('admin')->find($id);
    	$this->assign('admin',$admin);
        $this->display();
    }
   
    public function update($id){
    	if(IS_POST){
    		$admin = M('admin');
    		$admin->create();
    		if( $admin->save() ){
    			$this->success('修改成功',U('Manager/index'));
    		}else{
    			$this->error('修改失败');
    		}
    	}else{
    		$this->error('提交数据方式有误');
    	}
    }

    public function password(){
    	if(IS_POST){
    		$id = session( C('USER_AUTH_KEY') );
    		$oldpassword = I('oldpassword','');
    		$newpassword = I('newpassword','');
    		$repassword = I('repassword','');
    		if( empty($oldpassword) || empty($newpassword) || empty($repassword) ){
    			$this->error('密码不能为空');
    		}
    		if( strcmp($newpassword, $repassword) !== 0 ){
    			$this->error('两次输入的密码不一致');
    		}

    		$admin = M('admin')->field('email,password,encrypt')->where(array('id'=>$id))->find();
    		if( getPassword($oldpassword,$admin['encrypt']) !== $admin['password'] ){
    			$this->error('旧密码错误');
    		}

    		$passwordarr = getPassword($newpassword);
    		$data = array(
    			'id' => $id,
    			'password' => $passwordarr['password'],
    			'encrypt' => $passwordarr['encrypt'],
    			);
    		if( M('admin')->save($data) ){
    			$this->success('修改成功', U('Manager/index'));
    		}else{
    			$this->error('修改失败');
    		}		
    	}else{
    		$this->error('提交数据方式有误');
    	}
    }
    
    public function insert(){
    	if(IS_POST){
    		$admin = D('Admin');
    		$admin->create();
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