<?php
session_start();
//foreach($_SESSION as $x=>$val){
//    echo "$x $val<br/>";
//}
//
//exit;
include 'executecommand.php';
require_once('recaptchalib.php');
$publickey = "6LdHXdcSAAAAACbe9rMM6tUNIgbmV5hFc918iyWk";
$privatekey = "6LdHXdcSAAAAAF4KB-31N39Vwb8yRM1I6-uftN-P";
connect();
if (isset($_GET['verify'])) {
    include 'encryptionClass.php';
    $encrypt = new Encryption();
    $id = "";
    $arr = array();
    $res = array();
    $verify = clean($_GET['verify']);
    if ($verify != "") {
        $value = $encrypt->safe_b64decode($verify);
        $arr = explode(" ", $value);

        if (count($arr) == 4) {
            $sql = "SELECT `id`, `user_id`, `token`, `time`, `responded` FROM `password_recovery` WHERE `user_id`='$arr[0]' AND `token`='$verify' AND responded ='N'";
            $result = mysql_query($sql);
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_array($result)) {
                    $res = array("userId" => $row['user_id'], "id" => $row['id'], "token" => $row['token'], "time" => $row['time']);
                }
                $sql = "SELECT TIMESTAMPDIFF(SECOND,'" . $res['time'] . "',NOW()) as sec,TIMESTAMPDIFF(MINUTE,'" . $res['time'] . "',NOW()) as min,TIMESTAMPDIFF(HOUR,'" . $res['time'] . "',NOW()) as hour,TIMESTAMPDIFF(DAY,'" . $res['time'] . "',NOW()) as day,TIMESTAMPDIFF(MONTH,'" . $res['time'] . "',NOW()) as month,TIMESTAMPDIFF(YEAR,'" . $res['time'] . "',NOW()) as year";
                $result = mysql_query($sql);
                if (mysql_num_rows($result) > 0) {
                    $row = mysql_fetch_array($result);
                    if ($row['day'] > 0) {
                        $id = "EXPIRED";
                    } else {
                        $id = $res['userId'];
                        $res['email'] = $arr[1];
                        $_SESSION['password_change_details'] = $res;
                    }
                } else {
                    $id = "UNKNOWN";
                }
            } else {
                $id = "EXPIRED";
            }
        } else {
            $id = "UNKNOWN";
        }
    } else {
        $id = "UNKNOWN";
    }
    ?>
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">
            <style>
                /**********************************************/
                body{ font-family: 'Segoe UI',sans-serif; background-color: #f9f9f9; color: #717171;}
                a { color: #62a70f; text-decoration: none; }
                a:hover { color: #000;}
                a:active , a:focus { color: green;}
                h2 { color: #252525; font-weight: normal; padding: 3px; margin: 0;}
                ol,ul { list-style: none; }
                p {margin: 3px;}
                hr { margin: .3em 0;    width: 100%;    height: 1px;    border-width:0;    color: #ddd;    background-color: #ddd;}
                img { border: none; padding: .2em; margin: .5em; max-width: 100%;}
                /*********************************************/
                .container {max-width: 800px; margin: 0 auto; background-color: #fff; border: 1px solid #f2f2f2; padding: 10px}
                .header {background: url(images/logo_text_s.png) no-repeat right top!important;}  
                .header .time {font-size: .7em;}
                .content { background-color: #fff; padding: 1em;}
                .content p { font-size: .9em;}
                .content span { font-size: .8em;}
                .footer { background-color: #f9f9f9; padding: 10px; font-size: .8em;}

                /*********************************************/
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <br>
                    <h2>Welcome back :)</h2>
                    <!-- Sample Notification types -->
                    <p><span class="user-name"><?php echo $id == "EXPIRED" ? "Sorry, since you did not respond to the mail within 24hours, your password request session have expired. Please request for a new <a href='password_recovery_request.php'>password request link</a>" : $id == "UNKNOWN" ? "Your verification link was not recorgnised or have expired. Please request for a new password verification link" : "Your email verification was successfull. Please proceed to changing your password below."  ?></span></p>

                    <hr>      
                </div>
                <div class="content">
                    <?php
                    if ($id != "EXPIRED" && $id != "UNKNOWN") {

                        $resp = null;
                        $error = null;
                        ?>
                        <p>Choose a new secure password using the fields below. The password length must be at least 6 characters long.</p>
                        <form method="POST" action="password_recovery_request.php">
                            <table align="center">
                                <tr>
                                    <td>
                                        <?php
                                        if (isset($_SESSION['pass_err'])) {
                                            foreach ($_SESSION['pass_err'] as $err) {
                                                echo "<span style='color:red;'>" . $err . "</span><br/>";
                                            }
                                            unset($_SESSION['pass_err']);
                                        } else if (isset($_SESSION['pass_msg'])) {
                                            echo "<span style='color:red;'>" . $_SESSION['pass_msg'] . "</span>";
                                            unset($_SESSION['pass_msg']);
                                        }
                                        ?>
                                    </td><td></td>
                                </tr>
                                <tr>
                                    <td>New Password:</td><td><input type="password" name="pass" id="pass" required=""/></td>
                                </tr>
                                <tr>
                                    <td>Confirm Password:</td><td><input type="password" name="cpass" id="cpass" required=""/></td>
                                </tr>
                                <tr>
                                    <td>
                                        <script type="text/javascript">
                                            var RecaptchaOptions = {
                                                theme : 'white'
                                            };
                                        </script>
                                        <?php
                                        echo recaptcha_get_html($publickey, $error);
                                        ?>
                                    </td><td></td>
                                </tr>
                                <tr>
                                    <td><input type="hidden" value="<?php echo $res['token']; ?>" name="verify"/></td><td><input type="submit" value="Change Password"/></td>
                                </tr>
                            </table>
                        </form>
                        <?php
                    } else {
                        if ($id == "UNKNOWN" || $id == "EXPIRED") {
                            echo "<span><a href='password_recovery_request.php'>Request for another link</a> | <a href='login.php'>No thanks</a></span>";
                        }
                    }
                    ?>

                </div>
                <hr>
                <div class="footer">                
                    For more information on your privacy, please check our <a href="page.php?view=privacy">privacy</a> page
                    <hr>
                    <table cellspacing="5px">
                        <tr>        
                            <td> <a href="page.php?view=about">About</a> </td>
                            <td> <a href="page.php?view=terms">Terms</a> </td>
                            <td> <a href="page.php?view=privacy">Privacy</a> </td>
                        </tr>
                        <tr >
                            <td colspan="3"> &copy; <?php echo date("Y"); ?> <a href="http://www.gossout.com">Gossout</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </body>
    </html>
    <?php
} else if (isset($_POST['pass']) && isset($_POST['cpass'])) {

    if (isset($_POST["recaptcha_response_field"])) {
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if (!($resp->is_valid)) {
            $_SESSION['pass_err'][] = $resp->error;
        }
    } else {
        $_SESSION['pass_err'][] = "We cannot guarantee your true existence. We recomment you use a computer to access this page if you continue getting this error";
    }
    if ($_POST['pass'] == $_POST['cpass'] && !isset($_SESSION['pass_err'])) {

        $pass = md5(clean($_POST['pass']));
        $sql = "UPDATE `user_login_details` SET `password`='$pass' WHERE `id`='" . $_SESSION['password_change_details']['userId'] . "'";
        mysql_query($sql) or die(mysql_error());

        if (mysql_affected_rows() > 0 || mysql_affected_rows() == 0) {
            $html = '<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
    /**********************************************/
    body{ font-family: "Segoe UI",sans-serif; background-color: #f9f9f9; color: #717171;line-height: 2em;}
    a { color: #62a70f; text-decoration: none; }
    a:hover { color: #000;}
    a:active , a:focus { color: green;}
    h2 { color: #252525; font-weight: normal; padding: 3px; margin: 0;}
    ol,ul { list-style: none; }
    p {margin: 3px;}
    hr { margin: .3em 0;    width: 100%;    height: 1px;    border-width:0;    color: #ddd;    background-color: #ddd;}
    img { border: none; padding: .2em; margin: .5em; max-width: 100%;}
    /*********************************************/
    .container {max-width: 800px; margin: 0 auto; background-color: #fff; border: 1px solid #f2f2f2; padding: 10px}
    .header {background: url(images/logo_text_s.png) no-repeat right top!important;}  
    .header .time {font-size: .7em;}
    .content { background-color: #fff; padding: 1em;}
    .content p { font-size: .9em;}
    .content span { font-size: .8em;}
    .footer { background-color: #f9f9f9; padding: 10px; font-size: .8em;}

    /*********************************************/
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <br>
            <h2>Password reset successfull, </h2>

            <!-- Sample Notification types -->
            <hr>      
        </div>
        <div class="content">
            <p><span class="user-name">You have changed your password successfully.</p>
        </div>
        <hr>
        <div class="footer">                
            For more information about your <a href="http://gossout.com/page.php?view=privacy">privacy</a> check our <a href="http://gossout.com/page.php?view=privacy">privacy page.</a> 

            <br>If you believe this process was not initiated by you or this message was not meant for you, kindly take <strong>NO ACTION</strong>
            
            <hr>
            <table cellspacing="5px">
                <tr>        
                    <td> <a href="http://gossout.com/page.php?view=about">About</a> </td>
                    <td> <a href="http://gossout.com/page.php?view=terms">Terms</a> </td>
                    <td> <a href="http://gossout.com/page.php?view=privacy">Privacy</a> </td>
                </tr>
                <tr >
                    <td colspan="3"> &copy; <?php echo date("Y");?> <a href="http://www.gossout.com">Gossout</a></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
';
            $email = trim(strip_tags("password_request@gossout.com"));
            $full_name = 'Gossout Password';
            $from_mail = $full_name . '<' . $email . '>';
            $from_mail2 = $full_name . '<feedback@gossout.com>';
            $subject = "Password Changed Successfully";
            $headers = "Reply-To: $from_mail2 \r\n" . "From:" . $from_mail . "\r\n" . "X-Mailer: PHP/" . phpversion();
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            @mail($_SESSION['password_change_details']['email'], $subject, $html, $headers);
            $_SESSION['pass_msg'] = "Password changed successfully. <a href='login.php'>Click here to login</a>";
            $sql = "UPDATE `password_recovery` SET `responded`='Y' WHERE `user_id`='".$_SESSION['password_change_details']['userId']."' AND `token`='".$_POST['verify']."' AND `responded`='N'";
            mysql_query($sql);
            header("Location: password_recovery_request.php?verify=" . $_POST['verify']);
        } else {
            $_SESSION['pass_err'] = "Password was not changed successfully.";
            header("Location: password_recovery_request.php?verify=" . $_POST['verify']);
        }
    } else {
        if (isset($_SESSION['pass_err']) && $_POST['pass'] != $_POST['cpass']) {
            $_SESSION['pass_err'][] = "Password does not match";
        }

        header("Location: password_recovery_request.php?verify=" . $_POST['verify']);
    }
} else {
    ?>
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">
            <style>
                /**********************************************/
                body{ font-family: 'Segoe UI',sans-serif; background-color: #f9f9f9; color: #717171;}
                a { color: #62a70f; text-decoration: none; }
                a:hover { color: #000;}
                a:active , a:focus { color: green;}
                h2 { color: #252525; font-weight: normal; padding: 3px; margin: 0;}
                ol,ul { list-style: none; }
                p {margin: 3px;}
                hr { margin: .3em 0;    width: 100%;    height: 1px;    border-width:0;    color: #ddd;    background-color: #ddd;}
                img { border: none; padding: .2em; margin: .5em; max-width: 100%;}
                /*********************************************/
                .container {max-width: 800px; margin: 0 auto; background-color: #fff; border: 1px solid #f2f2f2; padding: 10px}
                .header {background: url(images/logo_text_s.png) no-repeat right top!important;}  
                .header .time {font-size: .7em;}
                .content { background-color: #fff; padding: 1em;}
                .content p { font-size: .9em;}
                .content span { font-size: .8em;}
                .footer { background-color: #f9f9f9; padding: 10px; font-size: .8em;}

                /*********************************************/
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <br>
                    <h2>Oops!, it looks like you've forgotten your password... :(</h2>
                    <!-- Sample Notification types -->
                    <p><span class="user-name">Please note:</span></p>
                    <!-- Sample Notification types -->
        <!--            <p class="time" align="right">Time: 8:00am 29-12-2012</p>-->
                    <hr>      
                </div>
                <div class="content">
        <!--            <img src="images/anony.png" align="left">-->
        <!--            <span class="user-name"><a href="">Sample Name </a></span>-->
                    <p>In order to keep your Gossout account secure, you'll have to confirm your <strong>email address</strong> attached to the account. Please provide your email address in the field below.</p>
                    <form method="POST" action="password_recovery_sent.php" onsubmit="">
                        <p><input type="text" name="email" id="email" required=""/> <input type="submit" value="Send Verification link"/></p>
                        <p style="color: red"><?php
    echo isset($_SESSION['email_err']) ? $_SESSION['email_err'] : "";
    unset($_SESSION['email_err'])
    ?></p>
                    </form>
                </div>
                <hr>
                <div class="footer">                
                    For more information on your privacy, please check our <a href="page.php?view=privacy">privacy</a> page
                    <hr>
                    <table cellspacing="5px">
                        <tr>        
                            <td> <a href="page.php?view=about">About</a> </td>
                            <td> <a href="page.php?view=terms">Terms</a> </td>
                            <td> <a href="page.php?view=privacy">Privacy</a> </td>
                        </tr>
                        <tr >
                            <td colspan="3"> &copy; <?php echo date("Y"); ?> <a href="http://www.gossout.com">Gossout</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </body>
    </html>
    <?php
}
?>