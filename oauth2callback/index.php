<?php
session_start();
if (!isset($_SESSION['auth'])) {
    session_unset();
    header("Location: ../index.php");
    exit;
}
//set_time_limit(60);
if (isset($_SESSION['find'])) {
    if ($_SESSION['find'] == "facebook") {


        require '../facebook.php';

        $facebook = new Facebook(array(
                    'appId' => '347320715345278',
                    'secret' => 'b9fa8ad4aea8f9b56a3b83a6987bc5a2',
                ));

// See if there is a user from a cookie
        $user = $facebook->getUser();

        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
//                $postId = $facebook->api('/me');
                $postId = $facebook->api('/me/feed', 'POST', array(
                    'name' => 'Gossout.com',
                    'link' => 'www.gossout.com',
                    'message' => 'I just joined the latest gossip community on Gossout. You can also come check it out!',
                    'caption' => 'Have a new feeling of social networking by getting lattest information from community of your choices',
                    'picture' => 'http://gossout.com/images/logo.jpg'));
            } catch (FacebookApiException $e) {
                $user = null;
            }
        }
    }
} else {
    header("Location: ../page.php?view=home");
}
?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <?php include '../head.php' ?>
        <script type="text/javascript">
            <!--
            function delayer(){
                window.location = "../page.php?view=join"
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
                        if ($user) {
                            echo '<table style="text-align: center; width: 90%;height: 300px">
                                <tr>
                                    <td>Done! Its that simple!!!. Your friends on facebook would get your invitation soon. Meanwhile, we would be taking you back to your account preference </td>
                                </tr>
                                
                            </table><script>setTimeout("delayer()", 5000)</script>';
                        } else {
                            ?>
                            <table style="text-align: center; width: 90%;height: 300px">
                                <tr>
                                    <td>Use the facebook button bellow (when it appears) to login to your facebook account.</td>
                                </tr>
                                <tr>
                                    <td><fb:login-button data-scope="publish_stream"></fb:login-button></td>
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
                                appId: '<?php echo $facebook->getAppID() ?>',
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
