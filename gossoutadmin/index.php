<?php
session_start();
if (isset($_SESSION['auth'])) {
    header("Location: page.php?view=home");
    exit();
}
if (isset($_POST['login'])) {
    include("executecommand.php");
    connect();
    if (isset($_POST['rememberme'])) {
        login(clean($_POST['admin_username']), clean($_POST['admin_password']), true);
    } else {
        login(clean($_POST['admin_username']), clean($_POST['admin_password']));
    }
}
?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Powered By Soladnet Softwares</title>
        <style>
            body.login{background:#fbfbfb;min-width:0;}
            #login_error,.login .message{margin:0 0 16px 8px;padding:12px;color: #b81900;}
            #login_error,.login .message{-webkit-border-radius:3px;border-radius:3px;border-width:1px;border-style:solid;}
            /*            .login{-webkit-border-radius:3px;border-radius:3px;border-width:1px;border-style:solid;}*/
            .login form .input{font-family:'Segoe UI',sans-serif}
            .login *{margin:0;padding:0;}
            .login form{margin-left:8px;padding:26px 24px 46px;font-weight:normal;background:#fff;border:1px solid #e5e5e5;-moz-box-shadow:rgba(200,200,200,0.7) 0 4px 10px -1px;-webkit-box-shadow:rgba(200,200,200,0.7) 0 4px 10px -1px;box-shadow:rgba(200,200,200,0.7) 0 4px 10px -1px;}
            .login form .forgetmenot{font-weight:normal;float:left;margin-bottom:0;}
            .login .button-primary{font-size:13px!important;line-height:16px;padding:3px 10px;float:right;}
            #login form p{margin-bottom:0;}
            #login form p.submit{padding:0;}
            .login label{color:#777;font-size:14px;}
            .login form .forgetmenot label{font-size:12px;line-height:19px;}
            .login form p{margin-bottom:24px;}
            #login{width:320px;padding:114px 0 0;margin:auto;}
            .login #nav,.login #backtoblog{text-shadow:#fff 0 1px 0;margin:0 0 0 16px;padding:16px 16px 0;}
            #backtoblog{padding:12px 16px 0;}
            .login form .input{font-weight:200;font-size:24px;line-height:1;width:100%;padding:3px;margin-top:2px;margin-right:6px;margin-bottom:16px;border:1px solid #e5e5e5;background:#fbfbfb;outline:none;-moz-box-shadow:inset 1px 1px 2px rgba(200,200,200,0.2);-webkit-box-shadow:inset 1px 1px 2px rgba(200,200,200,0.2);box-shadow:inset 1px 1px 2px rgba(200,200,200,0.2);}
            .login input{color:#555;}
            .login #pass-strength-result{width:250px;font-weight:bold;border-style:solid;border-width:1px;margin:12px 0 6px;padding:6px 5px;text-align:center;}

        </style>
        <!--        <link rel='stylesheet' id='wp-admin-css'  href='http://localhost/wordpress/wp-admin/css/wp-admin.css?ver=20111208' type='text/css' media='all' />
                <link rel='stylesheet' id='colors-fresh-css'  href='http://localhost/wordpress/wp-admin/css/colors-fresh.css?ver=20111206' type='text/css' media='all' />-->
        <meta name='robots' content='noindex,nofollow' />
    </head>
    <body class="login">
        <div id="login"><h1><img src="../images/logo_image_text.png" height="100" /></h1>
            <?php
            if (isset($_SESSION['err'])) {
                ?>
            <div id="login_error"><strong>Error:</strong>: <?php echo $_SESSION['err']['status'] ?></div>
                <?php
            }
            ?>
            <form id="loginform" action="index.php" method="post">
                <p>
                    <label for="user_login">Username<br />
                        <input type="text" name="admin_username" id="user_login" class="input" value="" size="20" tabindex="10" /></label>
                </p>
                <p>
                    <label for="user_pass">Password<br />
                        <input type="password" name="admin_password" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
                </p>
                <p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> Remember Me</label></p>
                <p class="submit">
                    <input type="submit" name="login" id="wp-submit" class="button-primary" value="Log In" tabindex="100" />
                    <input type="hidden" name="redirect_to" value="http://localhost/wordpress/wp-admin/" />
                    <input type="hidden" name="testcookie" value="1" />
                </p>
            </form>

            <p id="nav">
                <!--                <a href="http://localhost/wordpress/wp-login.php?action=lostpassword" title="Password Lost and Found">Lost your password?</a>-->
            </p>

<!--            <script type="text/javascript">
                function wp_attempt_focus(){
                    setTimeout( function(){ try{
                            d = document.getElementById('user_login');
                            d.focus();
                            d.select();
                        } catch(e){}
                    }, 200);
                }

                wp_attempt_focus();
                if(typeof wpOnload=='function')wpOnload();
            </script>-->

            <p id="backtoblog"><a href="http://gossout.com/" title="Are you lost?">Back to Gossout.com</a></p>
        </div>
        <div class="clear"></div>
    </body>
</html>
<?php
unset($_SESSION['err']);
?>