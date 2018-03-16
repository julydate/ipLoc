<?php 
define('INDEX',TRUE);
require_once('config.php');
$ip = @$_GET['ip'];
if(empty(@$_GET['ip'])){exit('IP为空');}
//校验是否合法IPv4地址
if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE)) {exit('IP非法');}
//API查询IP
if(empty(@$_GET['admin'])) {
	$appkey = @$_GET['ak'];
	$key = md5($ip.$salt);
	if($key === $appkey) {
	include('inc/viewip.php');
	} else {
		exit('AppKey有误');
	}
}
//手动查询IP
if(!empty(@$_GET['admin'])) {
	$administrator = @$_GET['admin'];
	if($admin === $administrator) {
		include('inc/viewip.php');
	} else {
		exit('管理员私钥有误');
	}
}
?>