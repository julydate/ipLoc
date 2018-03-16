<?php 
define('INDEX',TRUE);
/*Telegram Bot Token*/
$token = "";
/*IP Check Access Key*/
$ak = "";
/*
Telegram Bot Set WebHook:
https://api.telegram.org/bot$token/setWebhook?url=
 */
if(@$_GET['token'] === $token) {
	$msg = @file_get_contents('php://input');
	$msg_json = json_decode($msg,true);
	$chat_id = $msg_json['message']['chat']['id'];
	$chat_username = $msg_json['message']['chat']['username'];
	$chat_text = $msg_json['message']['text'];
	$entities_type_command = $msg_json['message']['entities'][0]['type'];//bot_command
	/*校验是否IP查询请求*/
	if(preg_match('/iploc/i', $chat_text) && $entities_type_command === "bot_command") {
		/*校验会话是否授权*/
		if($chat_username === "scusec") {
			/*正则匹配提取IP*/
			preg_match("/[\d\.]{7,15}/",$chat_text,$getip);
			$ip = $getip[0] ? $getip[0] : '127.0.0.1';
			if($ip === "127.0.0.1"){
				$reply = "IP格式有误";
			} else {
				/*请求查询数据*/
				$ak = md5($ip.$ak);
				$reply = @file_get_contents("/index.php?ip=".$ip."&ak=".$ak);
				$reply = $reply ? $reply : '接口调用失败';
				$reply = urlencode("IP:".$ip."\n".$reply);
			}
		} else {
			$reply = "此群组或用户未授权使用，请联系 t.me/ 获取授权";
		}
		$callback = @file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$reply);
	}
}
?>