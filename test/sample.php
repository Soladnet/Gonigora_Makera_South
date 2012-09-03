<?php
session_start();
include '../executecommand.php';
//echo toSentenceCase('ahmadu Bello Uni');
//include '../../Gossout-Project/yahoo-yos-social-php5-0.1-18-gd34814f/yahoo-yos-social-php5-d34814f/lib/Yahoo/';
connect();
//print_r(sendPost('48', '26', "Some Community name", "Hey's", "Soladnet"));
// $key = 'olaray';
//$string = 'string to be encrypted'; // note the spaces
//$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
//$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
//echo $encrypted;
//echo "<br/>";
//echo ".$decrypted.";
//$stringa = "This link should take me to http://www.yahoo.com send an email @ soladnet2006@yahoo.com another url is http://gossout.com";
//
//$pattern = "/([a-z0-9][_a-z0-9.-]+@([0-9a-z][_0-9a-z-]+\.)+[a-z]{2,6})/i";
//$replace = "<a href=\"mailto:\\1\">\\1</a>";
//$text = preg_replace($pattern, $replace, $stringa);
////echo txt2link($text);
//$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
//if(preg_match($reg_exUrl, $text, $url)) {
//
//       // make the urls hyper links
//       echo preg_replace($reg_exUrl, "<a href="{$url[0]}">{$url[0]}</a> ", $text);
//
//} else {
//
//       // if no urls in the text just return the text
//       echo $text;
//
//}
//print_r($_SESSION);
?>
<!DOCTYPE HTML>
<html> 
    <head> 
        <title>Loader</title> 
<!--        <style type="text/css">
            div#container {
                width:500px;
                height:500px;
                overflow:auto;
            }
        </style> 
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script> 
        <script type="text/javascript">
 
            $(document).ready(function(){
                $("#load").load('../index.php');
            });
        </script>-->
    </head>
    <body>
        <div id="load">
        </div>
        <form method="PoST" action="../exec.php">
            <input type="text" name="gossout"/><input type="submit" name="action" value="gossout"/>
        </form>
    </body>
</html>