<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}
//百度普通IP定位

//API控制台申请得到的ak（此处ak值仅供验证参考使用）
$ak = '';
//应用类型为for server, 请求校验方式为sn校验方式时，系统会自动生成sk，可以在应用配置-设置中选择Security Key显示进行查看（此处sk值仅供验证参考使用）
$sk = '';
//以Geocoding服务为例，地理编码的请求url，参数待填
$url = "http://api.map.baidu.com/location/ip?ip=%s&coor=%s&ak=%s&sn=%s";
//get请求uri前缀
$uri = '/location/ip';
//地理编码的请求中address参数
//$ip = $ip;
//地理编码的请求output参数
$coor = 'bd09ll';
//构造请求串数组
$querystring_arrays = array (
	'ip' => $ip,
	'coor' => $coor,
	'ak' => $ak
);
//调用sn计算函数，默认get请求
$sn = caculateAKSN($ak, $sk, $uri, $querystring_arrays);
//请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应
$target = sprintf($url, urlencode($ip), $coor, $ak, $sn);
function caculateAKSN($ak, $sk, $url, $querystring_arrays, $method = 'GET')
{
    if ($method === 'POST'){  
        ksort($querystring_arrays);  
    }  
    $querystring = http_build_query($querystring_arrays);  
    return md5(urlencode($url.'?'.$querystring.$sk));  
}
$gpsB = @file_get_contents($target);
$gpsB = json_decode($gpsB,true);
$address = array_column($gpsB,'address');
$point = array_column($gpsB,'point');
$gpsx = array_column($point,'x');
$gpsy = array_column($point,'y');
if($address&&$gpsx&&$gpsy) {
	foreach ($address as $tempA){$addr = $tempA;}
	foreach ($gpsx as $tempB){$addrx = $tempB;}
	foreach ($gpsy as $tempC){$addry = $tempC;}
	$gpsB = $addr;
	/*$gpsB = $addr.",经度:".$addrx.",纬度:".$addry;*/
} else {
	$gpsB = "N";
}
?>