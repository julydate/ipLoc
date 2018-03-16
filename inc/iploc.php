<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}
$gpsCgetgps = @file_get_contents("https://mall.ipplus360.com/ip/locate/api?key=".$gpskey."&ip=".$ip."&coordsys=BD09");
$gpsC = json_decode($gpsCgetgps,true);
$gpsC = array_column($gpsC,'multiAreas');
if($gpsC) {
	$lat = $gpsC[0][0]['lat'];
	$lng = $gpsC[0][0]['lng'];
	if($lat) {
		$gpsC = $lat.','.$lng;
	} else {
		$gpsC = "N";
	}
} else {
	$gpsC = "N";
}
//百度地图Geocoding API（GPS转地址）
if($gpsC != "N") {
	$gpsCget = @file_get_contents("http://api.map.baidu.com/geocoder/v2/?location=".$gpsC."&output=json&pois=1&ak=".$iplocak);
	$gpsC = json_decode($gpsCget,true);
	$address = array_column($gpsC,'formatted_address');
	$descript = array_column($gpsC,'location_description');
	if($address) {
		foreach ($address as $tempA){$addr = $tempA;}
		if($descript) {
			foreach ($descript as $tempB){$desc = $tempB;}
			$gpsC = $addr.$desc;
		} else {
			$gpsC = $addr;
		}
	} else {
		$gpsC = "N";
	}
}
?>