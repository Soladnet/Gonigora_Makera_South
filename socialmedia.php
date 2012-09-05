<?php
//set_time_limit(60);
if (isset($_SESSION['find'])) {
    if ($_SESSION['find'] == "facebook") {

// See if there is a user from a cookie
        $user = $conn_arr['facebook_obj']->getUser();
        $arr = array();
        if ($user && isset($_GET['share'])) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
//                $postId = $facebook->api('/me');
                sendToFacbook($conn_arr['facebook_obj'], '/me/feed', 'POST', array(
                    'name' => 'Gossout.com',
                    'link' => 'www.gossout.com',
                    'message' => 'I just joined the latest gossip community on Gossout. You can also come check it out!',
                    'caption' => 'Have a new feeling of social networking by getting lattest information from community of your choices',
                    'picture' => 'http://gossout.com/images/logo75x75.png'));
                $arr['status'] = "success";
            } catch (FacebookApiException $e) {
                $user = null;
                $arr['status'] = "failed";
            }
        }
        if ($user && isset($_GET['post'])) {
            
            $sql = "SELECT p.id,p.post,c.name,p.community_id,p.sender_id,p.time,concat(s.`firstname`,s.`lastname`) as fullname,if(cp.`100x100` IS NULL,'images/logo75x75.png',cp.`100x100`) as image FROM `post` as p JOIN user_personal_info as s on p.sender_id=s.id JOIN community as c on p.`community_id`=c.id LEFT JOIN community_pix AS cp ON p.id = cp.post_id WHERE p.id=" . clean($_GET['post']);
            $result = mysql_query($sql);
            if (mysql_num_rows($result) > 0) {
                
                $row = mysql_fetch_array($result);
                if ($row['image'] == "images/logo75x75.png") {
                   
                    try {
                        sendToFacbook($conn_arr['facebook_obj'], '/me/feed', "POST", array('message' => $row['post']));
                        $arr['status'] = "success";
                        $arr['fbmsg'] = "Shared with facebook successfull!";
                    } catch (FacebookApiException $e) {
                        $arr['status'] = "failed";
                        $arr['fbmsg'] = "$e";
                    }
                    
                } else {
                    try {
                        sendToFacbook($conn_arr['facebook_obj'], '/me/feed', "POST", array(
                            'name' => toSentenceCase($row['fullname']) . ' shared a gossip from ' . toSentenceCase($row['name']) . ' on Gossout',
                            'link' => 'www.gossout.com/page.php?view=notification&open=' . $row['id'],
                            'message' => $row['post'],
                            'caption' => $row['post'],
                            'picture' => 'http://gossout.com/' . $row['image']
                        ));
                        $arr['status'] = "success";
                        $arr['fbmsg'] = "Shared with facebook successfull!";
                    } catch (FacebookApiException $e) {
                        $arr['status'] = "failed";
                        $arr['fbmsg'] = "$e";
                    }
                }
            } else {
                $arr['status'] = "failed";
                $arr['fbmsg'] = "";
            }
        } else {
            $arr['status'] = "failed";
            $arr['fbmsg'] = "";
        }
    }
} else {
    header("Location: page.php?view=home");
}
?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <?php include 'head.php' ?>
        <script type="text/javascript">
            <!--
            function delayer(){
                window.location = "page.php?view=home"
            }
            //-->
        </script>
    </head>
    <body>
        <div id="page">
            <?php include_once("nav.php") ?>
            <div class="inner-container">
                <div class="left"></div>
                <div class="content">
                    <?php
                    if ($_SESSION['find'] == "facebook") {
                        
                        if ($arr['status'] == "success" && $user) {
                            echo '<table style="text-align: center; width: 90%;height: 300px">
                                <tr>
                                    <td>Done! Its that simple!!!. You have successfully shared gosout with your friends on facebook.</td>
                                </tr>
                                
                            </table><script>setTimeout("delayer()", 5000)</script>';
                        } else {
                            ?>
                            <table style="text-align: center; width: 90%;height: 300px">
                                <tr>
                                    <td>Use the facebook button bellow (when it appears) to login to your facebook account <?php echo $arr['status']." $user" ?>.</td>
                                </tr>
                                <tr>
                                    <td><fb:login-button data-scope="publish_stream user_birthday user_location user_website user_work_history"><img src="images/load.gif"/></fb:login-button></td>
                                </tr>
                            </table>


                            <?php
                        }
                    }
                    ?>
                    <div id="fb-root"></div>
                    <script>
                        window.fbAsyncInit = function() {
                            FB.init({
                                appId: '<?php echo $conn_arr['facebook_obj']->getAppID() ?>',
                                cookie: true,
                                xfbml: true,
                                oauth: true
                            });
                            FB.Event.subscribe('auth.login', function(response) {
                                window.location.reload();
                            });
                            FB.Event.subscribe('auth.logout', function(response) {
                                window.location.reload();
                            });
                        };
                        (function() {
                            var e = document.createElement('script'); e.async = true;
                            e.src = document.location.protocol +
                                '//connect.facebook.net/en_US/all.js';
                            document.getElementById('fb-root').appendChild(e);
                        }());
                    </script>
                </div>
                <div class="right"></div>
            </div>
        </div>
    </body>
</html>
