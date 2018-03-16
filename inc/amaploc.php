<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}
//高德高精度IP定位（自用）

$amapak = '';
$amaptoken = md5($ip.$amapak);
$gpsDget = @file_get_contents("/amap/amap.php?ip=".$ip."&token=".$amaptoken);
$gpsD = json_decode($gpsDget,true);
$address = array_column($gpsD,'desc');
$descript = array_column($gpsD,'pos');
if($address) {
	foreach ($address as $tempA){$addr = $tempA;}
	if($descript) {
		foreach ($descript as $tempB){$desc = $tempB;}
		$gpsD = $addr.$desc;
	} else {
		$gpsD = $addr;
	}
} else {
	$gpsD = "N";
}
?>