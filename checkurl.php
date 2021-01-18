<?php
/*
 * @Author: wkiwi
 * @Email: w_kiwi@163.com
 * @Date: 2020-12-28 10:04:06
 * @LastEditors: wkiwi
 * @LastEditTime: 2021-01-18 11:06:59
 */

// url检测的网址
// type网址类型：为"1"则网址未知（包括腾讯云绿标）,为"2"则网址报毒,为"3"则网址安全（即有付费的绿标）
// beian是否备案：为"1"，则已经备案为"0"，则未备案
// beiancode备案号，未备案则
// beianorg备案主体，未备案则空
// word报毒原因，未报毒则空
// wordtit报毒原因标题，未报毒则空


header('Content-Type:application/json; charset=utf-8');
function doCurl($url, $data=array(), $header=array(), $referer='', $timeout=30){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_REFERER, $referer);
  $response = curl_exec($ch);
  if($error=curl_error($ch)){
    die($error);
  }
  curl_close($ch);
  return $response;
}
// 调用
$url = 'https://cgi.urlsec.qq.com/index.php?m=check&a=check&url='.$_GET["url"];
$data = array();
// 设置IP
$header = array(
  'CLIENT-IP: 192.168.1.100',
  'X-FORWARDED-FOR: 192.168.1.100'
);
    $referer = 'https://urlsec.qq.com/';
    $response = doCurl($url, $data, $header, $referer, 5);
	$data = substr($response, 1, -1);
	$data = json_decode($data, true);
	$url = $data['data']['results']['url'];
	$type = $data['data']['results']['whitetype'];
	$beian = $data['data']['results']['isDomainICPOk'];
	$icpdode = $data['data']['results']['ICPSerial'];
	$icporg = $data['data']['results']['Orgnization'];
	$word = $data['data']['results']['Wording'];
	$wordtit = $data['data']['results']['WordingTitle'];
	$json = [
	    'url' => $url,
	    'type' => $type,
	    'beian' => $beian,
	    'icpdode' => $icpdode,
	    'icporg' => $icporg,
	    'word' => $word,
	    'wordtit' => $wordtit,
	    ];
	exit(json_encode($json));
?>