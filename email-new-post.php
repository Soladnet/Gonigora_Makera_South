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
            <h2>Reciever Name, </h2>
            <!-- Sample Notification types -->
            <p><span class="user-name"><a href="">Sample Name</a></span> posted in <span><a href="">Sample Community Name</a></span></p>
            <!-- Sample Notification types -->
            <p class="time" align="right">Time: 8:00am 29-12-2012</p>
            <hr>      
        </div>
        <div class="content">
            <img src="images/anony.png" align="left">
            <span class="user-name"><a href="">Sample Name </a></span>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <span><a href="">Comment on post</a></span>
        </div>
        <hr>
        <div class="footer">                
            This email was intended for <span class="user-name"><a href="">Reciever Name </a></span> 
            (<span class="user-location">Sample Location</span>).
            <br>If you believe 
            <span class="user-name"><a href="">Sample Name </a></span>
            is engaging in abusive behavior on
            <span><a href="http://www.gossout.com">Gossout</a></span>, you may <a href="">report 
            <span class="user-name"><a href="">Sample Name </a></span>
            for spam.</a> 
            <br>Forgot your 
            <span><a href="http://www.gossout.com">Gossout</a></span> password? 
            <a href="">Get instructions on how to reset it.</a>
            <br>You can also 
            <a href="">unsubscribe to these emails.</a>
            <br>If you received this message in error and did not sign up for <span><a href="http://www.gossout.com">Gossout</a></span>
            , click <a href="">not my account. </a>
            <br>
            <hr>
            <table cellspacing="5px">
                <tr>        
                    <td> <a href="page.php?view=about">About</a> </td>
                    <td> <a href="page.php?view=terms">Terms</a> </td>
                    <td> <a href="page.php?view=privacy">Privacy</a> </td>
                </tr>
                <tr >
                    <td colspan="3"> &copy; <?php echo date("Y");?> <a href="http://www.gossout.com">Gossout</a></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
