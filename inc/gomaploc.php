<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}
//搜狗地图高精度IP定位
$gomapak = '';
$gomaptoken = md5($ip.$gomapak);
$gpsFget = @file_get_contents("/gomap/gomap.php?ip=".$ip."&token=".$gomaptoken);
$gpsF = json_decode($gpsFget,true);
$address = $gpsF['response']['data'][0]['pois'][0]['address'];
$descript = $gpsF['response']['data'][0]['address'];
if($address) {
	if($descript) {
		$gpsF = $address.$descript;
	} else {
		$gpsF = $address;
	}
} else {
	$gpsF = "N";
}
?>