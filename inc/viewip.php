<?php 
if(!defined('INDEX')) {
	exit('禁止访问'); 
}

//IPIP.NET普通IP定位
include('ipipnetloc.php');//gpsA

//百度普通IP定位
include('baiduloc.php');//gpsB

//埃文科技高精度IP定位
include('ipplusloc.php');//gpsC

//高德高精度IP定位（自用）
include('amaploc.php');//gpsD

//RTBAsia高精度IP定位
include('wxloc.php');//gpsE

//搜狗高精度IP定位
include('gomaploc.php');//gpsF
 
//组合定位
$gps = "IPIP-NET:\n".$gpsA."\n百度:\n".$gpsB."\nIPPLUS360:\n".$gpsC."\nRTBAsia:\n".$gpsE."\n高德:\n".$gpsD."\n搜狗:\n".$gpsF;
echo $gps;

//记录文件
$time = gmdate("H:i:s",time()+8*3600);
$timelong = gmdate("Y-m-d H:i:s",time()+8*3600);;
$file = "ip.dat" ;
$fp = fopen ("ip.dat","a")  ;
$dat= "$ip"."---"."$time"."---"."$gps"."\n";
$datlong = "$ip"."---"."$timelong"."---"."$gps"."\n";;
fputs($fp,$datlong);

//当文件大于100MB时进行删除，防止文件过大
if(filesize($file) > 104857600) {
	unlink($file);
}
?>