<?php
session_start();
include 'executecommand.php';
include 'encryptionClass.php';
connect();
$user_email = clean($_POST['email']);
$sql = "SELECT l.`id`,concat(u.firstname,' ',u.lastname)as fullname, l.`email`, l.`password`, l.`token` FROM `user_login_details` as l JOIN user_personal_info as u ON l.id = u.id WHERE l.email ='$user_email'";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 0) {
    $encrypt = new Encryption();
    $rand = rand(0, 1000);
    $row = mysql_fetch_array($result);
    $veri = $encrypt->safe_b64encode("$row[id] $row[email] $row[token] $rand");
    $sql = "INSERT INTO `password_recovery`(`user_id`, `token`) VALUES ('$row[id]','$veri')";
    mysql_query($sql);
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
            <h2>Password reset request, </h2>
            <!-- Sample Notification types -->
            <p><span class="user-name">Hi, <strong>'.toSentenceCase($row['fullname']).'</strong></span>, we received a password reset request on your behalf. If you initiated this process, please click on the confirmation link below to continue, else ignore this email as we will stop the process if we do not hear from you in the next 24hour.</p>
            <!-- Sample Notification types -->
            <hr>      
        </div>
        <div class="content">
<!--            <img src="images/anony.png" align="left">-->
            <span class="user-name"><strong>Confirmation Link:</strong></span>
            <p><a href="http://gossout.com/password_recovery_request.php?verify='.$veri.'">Click here to confirm now</a></p>
<p>If your\'re finding it difficult to click the above link, simply copy the link below and paste it onto your browser address bar.</p>            
<p>http://gossout.com/password_recovery_request.php?verify='.$veri.'</p>
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
        $subject = "Password Request";
        $headers = "Reply-To: $from_mail2 \r\n" . "From:" . $from_mail . "\r\n" . "X-Mailer: PHP/" . phpversion();
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        @mail($row['email'], $subject, $html, $headers);
} else {
    $_SESSION['email_err'] = "The email you entered is not attached to any account.";
    header("Location: password_recovery_request.php");
    exit;
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
                <h2>Confirm email.</h2>
                
                <!-- Sample Notification types -->
    <!--            <p class="time" align="right">Time: 8:00am 29-12-2012</p>-->
                <hr>      
            </div>
            <div class="content">
    <!--            <img src="images/anony.png" align="left">-->
    <!--            <span class="user-name"><a href="">Sample Name </a></span>-->
                <span class="user-name"><p>A confirmation link have been sent to <strong><?php echo $user_email;?></strong>. Login to your email client to continue.</p>
                <p>In order to keep your Gossout account secure, you'll have to confirm that <strong><?php echo $user_email;?></strong> is attached to your account.</p></span>
                
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
