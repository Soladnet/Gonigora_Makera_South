<?php
session_start();
if (isset($_SESSION['auth'])) {
    header("Location: page.php?view=home");
    exit();
}
if (isset($_POST['login'])) {
    include("executecommand.php");
    connect();
    if(isset($_POST['rem'])){
        login(clean($_POST['email']), clean($_POST['password']),true);
    }else{
        login(clean($_POST['email']), clean($_POST['password']));
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gossout</title><title>
    <?php
    if (isset($_GET['view'])) {
        echo toSentenceCase($_GET['view']);
    } else {
        echo "Gossout";
    }
    ?>
</title>
<link rel="shortcut icon" href="favicon.ico" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />  
<!--- http://bit.ly/NfpVMY -->
    <!--[if IE]>
    <link rel="stylesheet" href="css/main.css" />
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" media="screen and (min-device-width: 1024px)" href="css/main.css" />
    <link rel='stylesheet' media="screen and (max-device-width: 1023px)" href="css/medium.css" />


    <?php
    if (isset($_GET['view'])) {
        if ($_GET['view'] == 'profile') {

        }
    }
    ?>
</head>
<body>
    <div class="container">

        <div id="nav2" class="nav2_gradient">
            <span id="logo">
                <a href="http://gossout.com" title="Logo"><img src="images/logo_text_s.png" alt="Logo"></a>
            </span>

            <div class="login-box">
                <span >New around here? &nbsp;&nbsp;&nbsp; </span>
                <a href="index.php"><input type="button" value="Sign-Up!" /></a>
            </div>

        </div>

        <div class="center_div width800">
            <div class="inner_wrappper box_shadow8 center_div">
                <div id="column1" class="login-page-column">
                </div>

                <div id="column2">
                    <div id="signup_form" class="box_shadow1 box_round_5">
                        <h2>Welcome Back!</h2>   
                        <div>
                            We missed ya!
                            <?php
                            if (isset($_SESSION['err']) && isset($_SESSION['err']["status"])) {
                                echo '<div class="ui-widget">
                                <div class="error" >
                                <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                                ' . $_SESSION['err']['status'] . '</p>
                                </div>
                                </div>';
                            }
                            ?>
                        </div>                    
                        <hr />
                        <form id="loginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class"box_round_5">
                          <ul id="body">
                            <li>
                                <label class="desc" for="email">Email Address</label>
                                <input type="text" name="email" id="email" />
                            </li>
                            <li>
                                <label class="desc" for="password">Password</label>
                                <input type="password" name="password" id="password" />
                            </li>
                            <hr />
                            <input type="submit" id="login" value="Sign in" name="login"/>
                            <label for="checkbox"><input type="checkbox" id="checkbox" name="rem"/>Remember me</label>
                        </ul>
                        <span><a href="#">Forgot your password?</a></span>
                    </form>
                    <p  class="info">By clicking Sign Up, you agree to our Terms and that you have read and understand our Data Use Policy.</p>
                    
                </div>

            </div>
            <?php include_once("footer.php"); ?>
        </div>
    </div>
</div>

</body>
</html>
<?php
unset($_SESSION['err']);
?>