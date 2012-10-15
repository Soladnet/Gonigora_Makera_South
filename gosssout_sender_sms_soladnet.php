<?php

$str = file_get_contents("ALLI2.csv");
$nums = explode(',', $str);

$arr = array();
foreach ($nums as $x) {
    if (trim($x) != "") {
        if (strlen($x) == 11) {
            $arr[$x] = $x;
        }
    }
}
$i = 0;
$msg = "Looking for a place on d internet that's worthy of ur time? Tired of all d unreliable rumors? Thirsty for trendy gossips? if yes, then visit www.gossout.com";
//foreach ($arr as $x) {
//    $i++;
//    $num = "234".substr($x,1);
//    echo "$num<br/>";
    sendSMS("23437193800", urlencode($msg), "Gossout");
//}
//echo $i;


//
function sendSMS($num, $msg, $sender) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://107.20.195.151/mcast_ws/?user=9gist&password=soladnet2006romeo1&from=$sender&to=$num&message=$msg");
    //curl_setopt($ch, CURLOPT_HTTPGET, 1);
    $strBuffer = curl_exec($ch);
    curl_close($ch);
}
?>