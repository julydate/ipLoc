<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}
//IPIP.NET普通IP定位免费接口
/*
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,"http://freeapi.ipip.net/?ip=".$ip);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 ");
curl_setopt($ch, CURLOPT_TIMEOUT, 300);//设置超时限制防止死循环 
$getgps = curl_exec($ch);
$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
if($getgps === FALSE||$httpCode >= "300" ){
	$gpsA = "N";
	} else {
		$gpsA = $getgps;
}
curl_close($ch);
*/
//IPIP.NET普通IP定位
$ipiptoken = '';//IPIP.NET普通IP定位密钥
$gpsAget = @file_get_contents("https://ipapi.ipip.net/find?addr=".$ip."&token=".$ipiptoken);
$gpsA = json_decode($gpsAget,true);
$address = $gpsA['data'];
$ret = $gpsA['ret'];
if($ret === "ok") {
	$gpsA = $address[0].",".$address[1].",".$address[2].",".$address[3].",".$address[4];
} else {
	$gpsA = "N";
}
?>