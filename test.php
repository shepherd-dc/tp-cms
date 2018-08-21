<?php 
header('content-type:text/html;charset=utf-8');

var_dump($_SESSION);exit;

$password = md5( md5('admin1234').'test' );
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];
$sql = "INSERT INTO imooc_admin(username,password,encrypt,logintime,loginip) VALUES('admin','{$password}','test','{$time}','{$ip}')";
echo $sql;

?>