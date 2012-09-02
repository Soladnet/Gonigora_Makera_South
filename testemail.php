<?php
session_start();
include 'executecommand.php';;
connect();
//
//sendGetExternalData("http://9gist.com/gossoutFile/gossoutFunction.php", $post_data);
////sendEmail("solanet@gmail.com", "info@gossout.com", "Gossout Community", "Welcome to Gossout Community", $message);
?>
<!DOCTYPE HTML>
<html> 
    <head> 
        <title>Loader</title> 
        <style type="text/css">
            div#container {
                width:500px;
                height:500px;
                overflow:auto;
            }
        </style> 
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script> 
        <script type="text/javascript">
 
            $(document).ready(function(){
                $.ajax({
                    url: 'index.php',
                    success: function(data){
                        $('#load').html(data); // Load data into a <div> as HTML
//                        alert('The page was loaded!');
                    }
                });
            });
        </script>
    </head>
    <body>
        <form action="do_ajaxfileupload_post.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload"/>
            <input type="submit"/>
        </form>
        <?php
        $my_friends = getUserFriends($_SESSION['auth']['id'],true);
        if(array_key_exists(50, $my_friends)){
            echo 1;
        }else{
            echo 0;
        }
        echo json_encode($my_friends);
//        $arr = search("Ah");
//        if(isset($arr['people']))
//        foreach ($arr['people'] as $x){
//            echo "<img src='".$x['img']['image3535']."' />".$x['fullname']."<br/>";
//        }
//        if(isset($arr['community']))
//        foreach ($arr['community'] as $x){
//            echo "<img src='images/community_35x35.png' />".shortenStr($x['name'],50)."<br/>";
//        }
//        print_r($arr);
        ?>
<!--        <form action="do_ajaxfileupload_post.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload"/><input type="submit"/>
        </form>
        <div id="load">
        </div>-->
    </body>
</html>