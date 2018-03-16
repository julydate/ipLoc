<?php 
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
if($access === 0) {
	exit('验证未通过');
} else {
    set_time_limit(0);
    $url = "http://ditu.amap.com";
	$filePath = "./cache/".md5($ip).'.dat';
	/*检测是否有缓存*/
    if (is_file($filePath)) {
		$getgps = @file_get_contents($filePath);
		if($getgps == "NULL"||$fresh === 1){
			$command = "phantomjs amap.js {$url} {$ip}";
			exec($command,$output);
			$getgps = "NULL";
			if(isset($output[0]))$getgps = $output[0];
			$fp = fopen ($filePath,"w");
			fputs($fp,$getgps);
		}
	} else {
		$command = "phantomjs amap.js {$url} {$ip}";
		exec($command,$output);
		$getgps = "NULL";
		if(isset($output[0]))$getgps = $output[0];
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
	function getGpsKeyValue($url) {
		$result = array();
		$mr     = preg_match_all('/(\?|&)(.+?)=([^&?]*)/i', $url, $matchs);
		if ($mr !== false) {
			for ($i = 0; $i < $mr; $i++) {
				$result[$matchs[2][$i]] = $matchs[3][$i];
			}
		}
		return $result;
	}
	/*通过已上获取到的经纬度（带URL）从高德经纬度定位接口获取定位JSON*/
	$gpsjson = @file_get_contents($getgps);
	$gpsarr = json_decode($gpsjson,TRUE);
	/*将经纬度信息插入到返回的位置信息JSON解析出来的数组中*/
	$latlngarr = getGpsKeyValue($getgps);
	$gpsarr['data']['latitude'] = $latlngarr['latitude'];
	$gpsarr['data']['longitude'] = $latlngarr['longitude'];
	/*将修改多的数组编码成JSON并输出*/
	$getgps = json_encode($gpsarr,JSON_UNESCAPED_UNICODE);
	header('Content-type:text/json;charset=utf-8');
	echo $getgps;
}
?>