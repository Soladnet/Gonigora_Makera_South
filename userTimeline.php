<?php

session_start();
include 'executecommand.php';
connect();
$str = $_SERVER['HTTP_REFERER'];
$str = clean(strstr($str, "uid="));
if (trim($str) != "") {
    $arr = explode("&", $str);
    $arr = explode("=", $arr[0]);
    if (trim($arr[1]) != "") {
        $id = trim($arr[1]);
    } else {
        $id = $_SESSION['auth']['id'];
    }
}else{
    $id = $_SESSION['auth']['id'];
}

echo '<div class="posts">' . showPostAndComment($id,1) . '</div>';
?>
