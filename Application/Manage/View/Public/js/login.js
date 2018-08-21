$(function(){
	var $msg = $('.msg');
	var username = $("input[name='username']");
	var password = $("input[name='password']");
	var verify = $("input[name='verify']");

	$('#login').submit(function(){
		if( username.val()=='' ){
			$msg.html('<span>用户名不能为空</span>');
			username.focus();
			return false;
		}else if( password.val()=='' ){
			$msg.html('<span>密码不能为空</span>');
			password.focus();
			return false;
		}else if( verify.val()=='' ){
			$msg.html('<span>验证码不能为空</span>');
			verify.focus();
			return false;
		}else{
			$msg.html('');
			return true;
		}
	});
	
});