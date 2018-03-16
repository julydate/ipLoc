<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}
//埃文科技高精度IP定位

//查询数据库
$getlog = mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database) or die ('Not connected : ' . mysqli_error());
$sql = "SELECT `info` FROM `iploc` WHERE `ip` = '$ip'";
mysqli_set_charset ($getlog,"utf8");
$result = mysqli_query($getlog,$sql);
if($result) {
	$result = mysqli_fetch_assoc($result);
	$ip_getlog = $result['info'];
}
mysqli_close($getlog);
if($ip_getlog) {
	$ip_getlog = substr($ip_getlog,1,strlen($ip_getlog)-2);//去除首尾单引号
	$gpsC = json_decode($ip_getlog,true);
	$address = array_column($gpsC,'formatted_address');
	$descript = array_column($gpsC,'location_description');
	$descript_diygod = array_column($gpsC,'sematic_description');
	if($address) {
		foreach ($address as $tempA){$addr = $tempA;}
		if($descript) {
			foreach ($descript as $tempB){$desc = $tempB;}
			$gpsC = $addr.$desc;
		} else if($descript_diygod) {
			foreach ($descript_diygod as $tempB){$desc = $tempB;}
			$gpsC = $addr.$desc;
		} else {
			$gpsC = $addr;
		}
	} else {
		$gpsC = "DBErro";
	}
} else {
//从API获取GPS
	$gpskey = "";//埃文科技定位ak
	$iplocak = "";//百度开放API平台ak
	include('iploc.php');
	if($gpsC != "N"&&$db_log === 1){include('db_logip.php');}
}
?>