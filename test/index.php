<?php
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  require_once('../facebook.php');

if(date("m")=="08" && date("d")>22){
    echo "Now";
}
  $config = array(
    'appId' => '347320715345278',
    'secret' => 'b9fa8ad4aea8f9b56a3b83a6987bc5a2',
  );

  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
?>
<html>
  <head></head>
  <body>

  <?php
    if($user_id) {

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
//      try {
//        $ret_obj = $facebook->api('/me/feed', 'POST',
//                                    array(
//                                      'link' => 'www.gossout.com',
//                                      'message' => 'Posting an update from my code...Gossout...comming!!'
//                                 ));
//        echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';
//
//      } catch(FacebookApiException $e) {
//        // If the user is logged out, you can have a 
//        // user ID even though the access token is invalid.
//        // In this case, we'll get an exception, so we'll
//        // just ask the user to login again here.
//        $login_url = $facebook->getLoginUrl( array(
//                       'scope' => 'publish_stream'
//                       )); 
//        echo 'Please <a href="' . $login_url . '">login.</a>';
//        error_log($e->getType());
//        error_log($e->getMessage());
//      }   
      // Give the user a logout link 
      echo '<br /><a href="' . $facebook->getLogoutUrl() . '">logout</a>';
    } else {

      // No user, so print a link for the user to login
      // To post to a user's wall, we need publish_stream permission
      // We'll use the current URL as the redirect_uri, so we don't
      // need to specify it here.
      $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream' ) );
      echo 'Please <a href="' . $login_url . '">login.</a>';

    } 

  ?>      
<h3>PHP Session</h3>
    <pre><?php print_r($_SESSION);echo "<br/>".$facebook->getAccessToken();;  ?></pre>
  </body> 
</html>  