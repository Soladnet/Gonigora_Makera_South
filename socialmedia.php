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
                $fb_post_id = sendToFacbook($conn_arr['facebook_obj'], '/me/feed', 'POST', array(
                    'name' => 'Gossout.com',
                    'link' => 'www.gossout.com',
                    'message' => 'I just joined the latest gossip community on Gossout. You can also come check it out!',
                    'caption' => 'Have a new feeling of social networking by getting lattest information from community of your choices',
                    'picture' => 'http://gossout.com/images/logo75x75.png'));
                $arr['status'] = "success";
                $arr['fb_post_id'] = $fb_post_id;
            } catch (FacebookApiException $e) {
                $user = null;
                $arr['fbstatus'] = "failed";
                $arr['fbmsg'] = "Opps! We cannot connect you to facebook now...try again later";
            }
        } else if ($user && isset($_GET['post'])) {
            $sql = "SELECT p.id,p.post,c.name,p.community_id,p.sender_id,p.time,concat(s.`firstname`,s.`lastname`) as fullname,if(cp.`100x100` IS NULL,'images/logo75x75.png',cp.`100x100`) as image FROM `post` as p JOIN user_personal_info as s on p.sender_id=s.id JOIN community as c on p.`community_id`=c.id LEFT JOIN community_pix AS cp ON p.id = cp.post_id WHERE p.id=" . clean($_GET['post']);
            $result = mysql_query($sql);
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                if (!strpos($row['post'], "<span>")) {
                    if ($row['image'] == "images/logo75x75.png") {
                        try {
                            $fb_post_id = sendToFacbook($conn_arr['facebook_obj'], '/me/feed', "POST", array('message' => $row['post']));
                            $arr['status'] = "success";
                            $arr['fb_post_id'] = $fb_post_id;
                            $arr['fbmsg'] = "Post shared with facebook successfull!";
                        } catch (FacebookApiException $e) {
                            $arr['fbstatus'] = "failed";
                            $arr['fbmsg'] = "Opps! We cannot connect you to facebook now...try again later";
                        }
                    } else {
                        try {
                            $fb_post_id = sendToFacbook($conn_arr['facebook_obj'], '/me/feed', "POST", array(
                                'name' => toSentenceCase($row['fullname']) . ' shared a gossip from ' . toSentenceCase($row['name']) . ' on Gossout',
                                'link' => 'www.gossout.com/page.php?view=notification&open=' . $row['id'],
                                'message' => $row['post'],
                                'caption' => $row['post'],
                                'picture' => 'http://gossout.com/' . $row['image']
                                    ));
                            $arr['status'] = "success";
                            $ar['fb_post_id'] = $fb_post_id;
                            $arr['fbmsg'] = "Shared with facebook successfull!";
                        } catch (FacebookApiException $e) {
                            $arr['fbstatus'] = "failed";
                            $arr['fbmsg'] = "Use the button bellow (when it appears) to authorise Gossout to share post on your wall.";
                        }
                    }
                } else {
                    $arr['fbstatus'] = "failed";
                    $arr['fbmsg'] = "This post cannot be sent to facebook at this time";
                }
            } else {
                $arr['status'] = "err";
                $arr['fbmsg'] = "Post does not exist or have been removed or flagged inappropriate";
            }
        } else if($user){
            $arr['status'] = "success";
            $arr['fbmsg'] = "You have successfully completed facebook authorization proccess.";
        }else {
            $arr['status'] = "failed";
            $arr['fbmsg'] = "Use the button bellow (when it appears) to authorise Gossout to share post on your wall.";
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

                        if (($arr['status'] == "success" && $user) || ($arr['status'] == "err" && $user)) {
                            echo '<table style="text-align: center; width: 90%;height: 300px">
                                <tr>
                                    <td>' . $arr['fbmsg'] . '</td>
                                </tr>
                                
                            </table><script>setTimeout("delayer()", 5000)</script>';
                        } else {
                            if ($arr['fbmsg'] != "Use the button bellow (when it appears) to authorise Gossout to share post on your wall.") {
                                ?>
                                <table style="text-align: center; width: 90%;height: 300px">
                                    <tr>
                                        <td> <?php echo $arr['fbmsg']; ?>.</td>
                                    </tr>
                                    <script>setTimeout("delayer()", 5000)</script>
            <!--                                    <tr>
                                        <td><fb:login-button data-scope="publish_stream user_birthday user_location user_website user_work_history"><img src="images/load.gif"/></fb:login-button></td>
                                    </tr>-->
                                </table>
                                <?php
                            } else {
                                ?>
                                <table style="text-align: center; width: 90%;height: 300px">
                                    <tr>
                                        <td><?php echo $arr['fbmsg']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><fb:login-button data-scope="publish_stream user_birthday user_location user_website user_work_history"><img src="images/load.gif"/></fb:login-button></td>
                                    </tr>
                                </table>


                                <?php
                            }
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
