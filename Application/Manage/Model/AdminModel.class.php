<?php 
namespace Manage\Model;
use Think\Model;
class AdminModel extends Model{

	//自动验证
	protected $patchValidate = true;
	protected $_validate = array(
		array('username','require','请输入用户名'),
		array('username','','用户名已存在',0,'unique',1),
		array('password','6,50','密码长度至少6位',2,'length',3),
		array('repassword','password','两次输入的密码不一致',0,'confirm',3)
		);

	//自动完成
	protected $_auto = array(
		array('logintime','time',1,'function'),
		);
}
?>