<?php 
namespace Manage\Controller;
use Think\Controller;
use Think\Verify;
class LoginController extends Controller {
	public function index(){
		$this->display();
	}

	public function verify(){
		$config = array(
			'length'     =>  4,
			'fontSize'   =>  18,
			'imageW'     =>  190,
			'imageH'     =>  34,
			'bg'         =>  array(22,150,215),
			'useCurve'   =>  true,
			'useNoise'   =>  true,
			);
		//实例化验证码对象：不用use,则为$verify = new \Think\Verify($config);
		$verify = new Verify($config);
		//清空输出缓冲区
		ob_clean();
		//输出验证码并把验证码保存值保存到session中
		$verify->entry(1);
	}

	public function login(){
		if(!IS_POST) E('页面不存在');
		$username = I('username','','htmlspecialchars,trim');
		$password = I('password','','htmlspecialchars,trim');
		$verify = I('verify','','htmlspecialchars,trim');
		// var_dump(I(post.));
		//检测用户名和密码
		if( $username == '' || $password == '' ){
			$this->error('用户名和密码不能为空');
		}

		$user = M('admin')->where( array('username'=>$username) )->find();
		if( !$user || $user['password'] != getPassword($password,$user['encrypt']) ){
			$this->error('账号或密码有误');
		}

		if( $user['islock'] != 0 ){
			$this->error('账号被锁定');
		}

		//检测验证码是否正确
		if( !checkVerify($verify) ){
			$this->error('验证码有误');
		}

		$date = array(
			'id' => $user['id'],
			'logintime' => time(),
			'loginip' => get_client_ip()
			);
		//更新数据库
		M('admin')->save($date);

		session( C('USER_AUTH_KEY'),$user['id'] );
		session( 'adm_username',$user['username'] );
		session( 'adm_logintime',date('Y-m-d H:i:s',$user['logintime']) );
		session( 'adm_loginip',$user['loginip'] );

		//跳转到后台首页
		$this->redirect('Index/index');

	}

	public function logout(){
		session_unset();
		session_destroy();
		$this->redirect('Login/index');
	}

}

?>