<?php 
define('INDEX',TRUE);
/*认证token*/
$ak = '';
/*检测是否有请求IP*/
if (isset($_GET['ip'])) {
	$ip = trim($_GET['ip']);
} else {
	$ip = "125.71.200.5";
}
/*检测是否带有token*/
if(isset($_GET['token'])) {
	$token = trim($_GET['token']);
	$verfy = md5($ip.$ak);
	if($token === $verfy) {
		$access = 1;
	} else {
		$access = 0;
	}
} else {
	$access = 0;
}
/*检测是否强制刷新*/
if(isset($_GET['fresh'])){
	$fresh = trim($_GET['fresh']);
	if($fresh == 1) {
		$fresh = 1;
	} else {
		$fresh = 0;
	}
} else {
	$fresh = 0;
}
/*认证未通过返回信息*/
if($access === 0) {;
	exit('验证未通过');
} else {
    set_time_limit(10);
	$filePath = "./cache/".md5($ip).'.dat';
	/*检测是否有缓存*/
    if (is_file($filePath)) {
		$getgps = @file_get_contents($filePath);
		if($getgps == "NULL"||$fresh === 1){
			$getgps = gomapGetGps($ip);
			$fp = fopen ($filePath,"w");
			fputs($fp,$getgps);
		}
	} else {
		$getgps = gomapGetGps($ip);
		$fp = fopen ($filePath,"w");
		fputs($fp,$getgps);
	}
}
/*输出返回信息*/
if($getgps == "NULL") {
	header('Content-type:text/json;charset=utf-8');
	echo '{"status":"0"}';
} else {
	/*从URL中提取出经纬度并返回数组*/
	$getgps = mb_convert_encoding($getgps, "utf8", "gbk");
	header('Content-type:text/json;charset=utf-8');
	echo $getgps;
}

//传送并提取获得输出搜狗地图数据
function gomapGetGps($ip){
	$headers['X-FORWARDED-FOR'] = $ip;
	$headers['REMOTE-ADDR'] = $ip;
	$headers['Host'] = 'lspengine.go2map.com';
	$headers['Cookie'] = 'IPLOC=CN5100';
	$headers['Upgrade-Insecure-Requests'] = '1';
	$headerArr = array();
	foreach( $headers as $n => $v ) { 
		$headerArr[] = $n .':' . $v;  
	}
	$headers = $headerArr;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,"------");
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt ($ch,CURLOPT_REFERER,'http://map.sogou.com/');
	curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36");//模拟电脑端访问
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);//构造HTTP头
	$getgps = curl_exec($ch);
	curl_close($ch);
	$getgps = preg_replace("#^.*?\((.*?)\).*?$#us", "$1", $getgps);//取()内的JSON
	$gps = json_decode($getgps,true);
	if($gps['error'] === 0) {
		$lng = $gps['lon'];
		$lat = $gps['lat'];
		$locget = @file_get_contents("------".$lng.",".$lat."&format=pb&pois=1&radius=100&startIndex=0&endIndex=1&isaroundbus=1");
		return $locget;
	} else {
		return 'NULL';
	}
}
?>