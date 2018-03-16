<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}

//RTBAsia高精度IP定位

$params = array(
	'ip' => "$ip",
	'm' => '0',
	'appkey' => ''
);
$url = 'https://way.jd.com/RTBAsia/ip_location';
$gpsEgetgps = @wx_http_request($url, $params );
$gpsE = json_decode($gpsEgetgps,true);
$gpsE = $gpsE['result'];
if($gpsE) {
	$lat = $gpsE['location']['latitude'];
	$lng = $gpsE['location']['longitude'];
	if($lat) {
		$gpsE = $lat.','.$lng;
	} else {
		$gpsE = "N";
	}
} else {
	$gpsE = "N";
}

//百度地图Geocoding API（GPS转地址）

$iplocak = "";//百度开放API平台ak
if($gpsE != "N") {
	$gpsEget = @file_get_contents("http://api.map.baidu.com/geocoder/v2/?location=".$gpsE."&output=json&pois=1&ak=".$iplocak);
	$gpsE = json_decode($gpsEget,true);
	$address = array_column($gpsE,'formatted_address');
	$descript = array_column($gpsE,'location_description');
	if($address) {
		foreach ($address as $tempA){$addr = $tempA;}
		if($descript) {
			foreach ($descript as $tempB){$desc = $tempB;}
			$gpsE = $addr.$desc;
		} else {
			$gpsE = $addr;
		}
	} else {
		$gpsE = "N";
	}
}

//RTBAsia高精度IP定位SDK

function wx_http_request($url, $params, $body="", $isPost=false, $isImage=false ) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url."?".http_build_query($params));
    if($isPost){
        if($isImage){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: multipart/form-data;',
                    "Content-Length: ".strlen($body)
                )
            );
        }else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: text/plain'
                )
            );
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
} 
?>