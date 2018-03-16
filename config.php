<?php 
if(!defined('INDEX')) {exit('禁止访问');}
$salt = '';//加密生成APPKEY所用密钥，请随机生成
$admin = '';//show查看所有截取数据的管理员私钥
$db_log = 1;
$mysql_server_name=''; //mysql数据库服务器
$mysql_username=''; //mysql数据库用户名
$mysql_password=''; //mysql数据库密码
$mysql_database=''; //mysql数据库名
if($db_log === 1) {
	$contest = mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database) or die ('Not connected : ' . mysqli_error());
	$sqltest = "CREATE TABLE iploc (ip varchar(15) primary key NOT NULL,info longtext CHARACTER SET utf8 COLLATE utf8_general_ci)";
	mysqli_set_charset ($contest,"utf8");
	mysqli_query($contest,"SELECT * FROM iploc") or mysqli_query($contest,$sqltest);
	mysqli_close($contest);
}
?>