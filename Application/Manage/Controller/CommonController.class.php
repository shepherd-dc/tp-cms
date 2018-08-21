<?php 
namespace Manage\Controller;
use Think\Controller;
/**
 * 公共验证控制器
 */
class CommonController extends Controller{
	//检测管理员是否登录
	public function _initialize(){
		$key = session( C('USER_AUTH_KEY') );
		if( !isset($key) ){
			$this->redirect(MODULE_NAME.'/Login/index');
		}
	}
	 
}

?>