<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
    /**********************************************/
    body{ font-family: 'Segoe UI',sans-serif; background-color: #f9f9f9; color: #717171;line-height: 2em;}
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
            <p><span class="user-name">Hi, <a href="">Sample Name</a></span>, we received a password reset request on your behalf. If you initiated this process, please click on the confirmation link below to continue, else ignore this email as we will stop the process if we do not hear from you in the next 24hour.</p>
            <!-- Sample Notification types -->
            <hr>      
        </div>
        <div class="content">
<!--            <img src="images/anony.png" align="left">-->
            <span class="user-name"><strong>Confirmation Link:</strong></span>
            <p>link</p>
            <p>If your're finding it difficult to click the above link, simply copy it and paste it onto your browser address bar.</p>
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
