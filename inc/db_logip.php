<?php 
if(!defined('INDEX')) {exit('禁止访问');}
$conn = mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database) or die ('Not connected : ' . mysqli_error());
$sql = "REPLACE INTO `iploc` (`ip` ,`info` ) VALUES('".$ip."', '".json_encode($gpsCget,JSON_UNESCAPED_UNICODE)."');";
mysqli_set_charset ($conn,"utf8");
$query = mysqli_query($conn,$sql);
mysqli_close($conn);
?>