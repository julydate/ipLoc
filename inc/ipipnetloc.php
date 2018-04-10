<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}
//IPIP.NET普通IP定位免费接口
/*
$headers = randIp();
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,"http://freeapi.ipip.net/?ip=".$ip);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 ");
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);//构造IP
curl_setopt($ch, CURLOPT_TIMEOUT, 300);//设置超时限制防止死循环 
$getgps = curl_exec($ch);
$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);
if($getgps === FALSE||$httpCode >= "300" ){
	$gpsA = "N";
	} else {
		$gpsA = $getgps;
}
*/

//ip.api.0sm.com/?ip=124.14.30.105&type=json 普通IP定位
$headers = randIp();
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,"http://ip.api.0sm.com/?type=json&ip=".$ip);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 ");
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);//构造IP
curl_setopt($ch, CURLOPT_TIMEOUT, 300);//设置超时限制防止死循环 
$getgps = curl_exec($ch);
$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);
if($getgps === FALSE||$httpCode >= "300" ){
	$gpsA = "N";
	} else {
		$getgps = json_decode($getgps,true);
		if($getgps['code'] === 0) {
			$address = $getgps['data']['country'].$getgps['data']['area'].",".$getgps['data']['region'].",".$getgps['data']['city'].",".$getgps['data']['county'].",".$getgps['data']['isp'];
			$gpsA = $address;
		} else {
			$gpsA = "N";
		}
}

//IPIP.NET普通IP定位
if($gpsA === "N") {
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
}

//生成随机IP
function randIP(){
	$ip_long = array(
		array('607649792', '608174079'), //36.56.0.0-36.63.255.255
		array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
		array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
		array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
		array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
		array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
		array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
		array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
		array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
		array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
	);
	$rand_key = mt_rand(0, 9);
	$ip_rank = long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
	$headers['CLIENT-IP'] = $ip_rank; 
	$headers['X-FORWARDED-FOR'] = $ip_rank; 
	$headerArr = array();
	foreach( $headers as $n => $v ) { 
		$headerArr[] = $n .':' . $v;  
	}
	return $headerArr;
}
?>
