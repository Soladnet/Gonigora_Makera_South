<?php
session_start();
if (isset($_SESSION['auth'])) {
    header("Location: page.php?view=home");
    exit();
}
if (isset($_POST['saveForm'])) {
    include_once ("executecommand.php");
    connect();
    if ($_POST['saveForm'] == "Login") {

    } else if ($_POST['saveForm'] == "Sign-up") {
        $msg = array();
        if (trim($_POST['first_name']) == "" || trim($_POST['last_name']) == "") {
            $msg["fullname"] = "Full name is required";
        }
        if ($_POST['gender'] == "") {
            $msg["gender"] = "Gender is required";
        }
        if (trim($_POST['dob_day']) == "" || trim($_POST['dob_yr'])=="" || $_POST['dob_month']=="0" && !is_int($_POST['dob_yr']) && is_int($_POST['dob_day'])) {
            $msg["dob"] = "Date format not supported";
        }
        if (!isValidEmail($_POST['email'])) {
            $msg["email"] = "email is not valid or does not come from a valid host";
        }else{
           $sql = "SELECT * FROM user_login_details WHERE email='".clean($_POST['email'])."'";
           $result = mysql_query($sql);
           if(mysql_num_rows($result)>0){
               $msg["email"] = "Email already registered";
           }
            
        }
        if (trim($_POST['password']) == "" || trim($_POST['cpassword']) == "") {
            $msg["pssword"] = "Password cannot be left empty";
        }
        if ($_POST['password'] != $_POST['cpassword']) {
            if(isset($msg["pssword"])){
                $msg1["pssword"] .= " 'Password does not match'";
            }else{
                $msg1["pssword"] = " 'Password does not match'";
            }
            
        } else {
            if (strlen($_POST['password']) < 6) {
                if(isset($msg["pssword"])){
                    $msg["pssword"] .= " 'Password must be atleast 6 character'";
                }else{
                    $msg["pssword"] = " 'Password must be atleast 6 character'";
                }
                
            }
        }
        if (count($msg) > 0) {
            $_SESSION['err'] = $msg;
//            header("Location: " . $_SERVER['PHP_SELF']);
//            exit;
        } else {
            $date = clean($_POST['dob_yr'])."-".  clean($_POST['dob_month'])."-".  clean($_POST['dob_day']);
            $done = registerUser(clean($_POST['first_name']), clean($_POST['last_name']), clean($_POST['gender']), $date, clean($_POST['email']), clean($_POST['password']));
            if ($done == "") {
                header("Location: page.php?view=join");
            } else {
                $arr["status"] = "Opps! something went wrong... We will fix these as soon as possible.".$done;
                $_SESSION['err'] = $arr;
                header("Location: index.php");
            }
            exit;
        }
    }
}
if (isset($_POST['login'])) {
    include_once ("executecommand.php");
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
    <title>Gossout</title>
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
            <a href="http://gossout.com" title="Logo"><img src="images/logo_text_s.png" alt="Gossout"></a>
        </span>

        <div class="login-box">
            <form method="POST" action="index.php" >
                
                    <span >Have an account? &nbsp;&nbsp;&nbsp; </span>
                    <label for="username" class="desc"> e-mail:</label>
                    <input type="text" name="email" id="username" value="" />
                    <label for="password"  class="desc">Password</label>
                    <input type="password" name="password" id="password" />
                    <label for="checkbox"><input type="checkbox" id="checkbox" name="rem"/>Remember me</label>
                    <input type="submit" value="Login" name="login">
                
                <input type="hidden" name="login"/>
            </form>
        </div>
        <div class="clear"></div>
    </div>

    <div class="center_div width800">
        <div class="inner_wrappper box_shadow8 center_div ">
            <div id="column1">
                <div  class="community_index index_fnx" > 
                	<span> <h1 class="fnx">Share With Communities <hr> </h1>
                    <p class="fnx_detail">Subscribe to campus or locality communities relevant to you and start talking.</p>
                     </span>
                </div>
                <div  class="friend_index index_fnx"> 
               	    <span> <h1 class="fnx">Interact With Friends <hr></h1> 
                    <p class="fnx_detail">Have fun with friends: Send private messages, Tweaks and Winks.</p>
                    </span>
                </div>
                <div  class="gossip_index index_fnx"> 
                    <span><h1 class="fnx">Get Hottest Gossip<hr></h1>
                    <p class="fnx_detail">Get the hottest gossip instantly through the Hottest Gossip feature.</p>
                    </span>
                </div>
                
</div>

<div id="column2">
    <div id="signup_form" class="box_shadow1 box_round_5">


        <form id="signup" name="" autocomplete="off" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><!----> 

            <header id="header">
                <h2>Sign-Up now!</h2>   
                <div>
                    Yup, it's this simple!

                    <?php
                    if (isset($_SESSION['err']) && isset($_SESSION['err']["status"])) {
                        echo '<div class="error">                        
                        <p>' . $_SESSION['err']['status'] . '</p>                        
                        </div>';
                    }
                    ?>
                </div>
            </header>

            <hr />


            <ul>

                <li >
                    <label class="desc">
                        Name
                        <span class="req">*</span>
                    </label>
                    <?php
                    if (isset($_SESSION['err']) && isset($_SESSION['err']["fullname"])) {
                        echo "<span class='req'>" . $_SESSION['err']['fullname'] . "</span><br/>";
                    }
                    ?>
                    <ul>
                        <li>
                            <label for="first_name">First:</label>
                            <input  name="first_name" placeholder="" type="text" value=""  tabindex="1"  spellcheck="false" class="red"  required/>
                        </li>
                        <li>
                            <label for="last_name">Last:</label>
                            <input  name="last_name" type="text"  value="" placeholder="" tabindex="2" spellcheck="false"   required/>
                        </li>
                    </ul> </li> <!-- ************************ -->

                    <li >
                        <label class="desc">
                            Gender
                            <span class="req">*</span>
                        </label>
                        <?php
                        if (isset($_SESSION['err']) && isset($_SESSION['err']["gender"])) {
                            echo "<span class='req'>" . $_SESSION['err']['gender'] . "</span><br/>";
                        }
                        ?>
                        <input name="gender" type="radio" class="field radio" value="M" tabindex="3"   
                        required />
                        <label class="choice" for="Field4_0" >
                            Male
                        </label>
                        <input name="gender" type="radio" class="field radio" value="F" tabindex="4" required />
                        <label class="choice" for="Field4_1" >
                            Female
                        </label>
                    </li> <!-- ************************ -->

                    <li>
                        <label class="desc">
                            Date of Birth
                            <span class="req">*</span>
                        </label>
                        <?php
                        if (isset($_SESSION['err']) && isset($_SESSION['err']["dob"])) {
                            echo "<span class='req'>" . $_SESSION['err']['dob'] . "</span><br/>";
                        }
                        ?>
                        <select name="dob_month" >
                            <option value="0"></option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <input type="number" min="1" max="31" name="dob_day" size="2" required placeholder="DD" />
                        <input type="number" max="<?php echo date("Y")-13?>" min="1800" size="4" name="dob_yr" required placeholder="YYYY" />
                        <!--                                        <input name="dob" type="text" readonly="" id="regDate" value="" tabindex="5" required placeholder="YYYY-MM-DD"/>-->

                    </li><!-- ************************ -->

                    <li >
                        <label class="desc" for="Field5">
                            Email
                            <span class="req">*</span>
                        </label>
                        <?php
                        if (isset($_SESSION['err']) && isset($_SESSION['err']["email"])) {
                            echo "<span class='req'>" . $_SESSION['err']['email'] . "</span><br/>";
                        }
                        ?>
                        <input  name="email" type="email" spellcheck="false" placeholder="" class="field text medium" value="" maxlength="50" tabindex="8" required /> 
                    </li>  <!-- ************************ -->

                    <li >
                        <label class="desc" for="Field5">
                            Password
                            <span class="req">*</span>
                        </label>
                        <input  name="password" type="password" placeholder="" spellcheck="false" class="field text medium" value="" min="6" maxlength="255" tabindex="8" required /> 
                    </li>  <!-- ************************ -->

                    <li >
                        <label class="desc" for="Field5">
                            Confirm Password
                            <span class="req">*</span>
                        </label>
                        <input  name="cpassword" type="password" spellcheck="false" placeholder="" class="field text medium" value="" tabindex="8" required /> 
                        <?php
                        if (isset($_SESSION['err']) && isset($_SESSION['err']["pssword"])) {
                            echo "<span class='req'>" . $_SESSION['err']['pssword'] . "</span><br/>";
                        }
                        ?>
                    </li>  
                    <hr />
                    <li>
                        <div>

                            <input name="saveForm" type="submit" value="Sign-up" /><span id="loading"></span>
                        </div>
                    </li> <!-- ************************ -->
                    <li>
                        <p  class="info">By clicking Sign Up, you agree to our <a href="page.php?view=terms">Terms</a> and that you have read and understand our Data Use Policy.</p>
                    </li>
                </ul>
            </form> 
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