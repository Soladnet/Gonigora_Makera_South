<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php include_once("head.php"); ?>
        <script type="text/javascript" src="js/home_ajaxfileupload.js"></script> 
        <style>
            #status_community{display: none;font-size: .7em;color:#999;}
        </style>
        <script>
            var laststate= 3;
            $(function() {
                $( "#status" ).toggle(
                function() {
                    $( "#status" ).animate({  height: 60  }, 500 );
                    $("#status_community").css("display", "inline");
                },
                function() {
                    $( "#status" ).animate({ height:30 }, 500 );
                    $("#status_community").css("display", "none");
                }
            );
            });
            
            
        </script>
        
    </head>
    <body id="#style">

        <div id="page">
            <?php include_once("nav.php") ?>
            <div class="inner-container" >
                <?php include_once("left.php"); ?>

                <div class="content" id="#style">
                    <div id="tabs">
                        <ul>
                            <li class="lefttab"><a href="#timeline" >Timeline </a></li>
                            <li class="righttab"><a href="gossoutpages/gossout_hottest.php" >Hottest Gossip </a></li>
                        </ul>
                        <div id="timeline">
                            <div id="postbox">
<!--                                <input type="button" value="Share"/> <input type="button" value="Share with image"/>-->
                                <form method="get" id="statusUpdate" onSubmit="return false">
                                    <textarea placeholder="What's happening right now!" class="update_textarea" id="status" ></textarea>
                                    <span id="status_community"><?php
                if ($_SESSION['auth']['community']['name'] != "") {
                    echo "Share with " . $_SESSION['auth']['community']['name'];
                } else {
                    echo "<span class='req'>Join a community before you can share information</span>";
                }
                ?></span><span id="share_loading"></span>
                                    <br/><input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input" ><input type="submit" class="submit" value="Share" id ='postsubmit' onClick="getValue('#status','posts');"/>
                        <!--                                <input type="button" value="Button"/>
                                                        <input type="reset" value="Clear"/>-->
                                </form>
                                <div class="clear">
                                </div>
                            </div>

                            <!--                            Ola, just cut the code below and put it in the appropriate plac then i'll finish the styling
                                                        <div class="post_activities"> Gossout | Share | Comment</div>-->

                            <div class="posts">
                                <?php
//connect();
                                echo showPostAndComment($_SESSION['auth']['id']);
                                ?>

                                <!--                                <div class="post">
                                                                    <img class="profile_small"src="upload/thumbnails/1345716972_48_1749_50x50.jpeg"/>
                                                                        <p class="name">
                                                                            Faridah Idriss
                                                                        </p>
                                                                        <span class="notBold">Shared</span>
                                                                    <div class="post">
                                                                        <img class="profile_small"src="upload/thumbnails/1345716972_48_1749_50x50.jpeg"/>
                                                                        <p class="name">
                                                                            Soladoye Abdulrasheed
                                                                        </p>
                                                                        <p class="status">
                                                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                                                        </p>
                                                                        
                                
                                                                    </div>
                                                                        <p class="time">
                                                                            4 minutes ago
                                                                        </p>
                                                                </div>-->
                                <!--                                                            <div class="post">
                                                                                                <img class="profile_small" src="images/black.jpg"/>
                                                                                                <p class="name">
                                                                                                    Alkali Ibrahim
                                                                                                </p>
                                                                                                <p class="status">
                                                                                                    This is a sample picture update
                                                                                                </p>
                                                                                                <ul class="box">
                                                                                                    <li><img src="images/hero.jpg"/></li>
                                                                                                </ul>
                                                                                                <p class="time">
                                                                                                    8:07 PM Today
                                                                                                </p>
                                                                
                                                                                            </div>-->
                                <!--                            <div class="post">
                                                                <img class="profile_small"src="images/black_1.jpg"/>
                                                                <p class="name">
                                                                    Aminu Alkali
                                                                </p>
                                                                <p class="status">
                                                                    This is a sample Video update
                                                                </p>
                                                                <iframe id="ytplayer" type="text/html" width="640" height="360" src="http://www.youtube.com/embed/Zhawgd0REhA" frameborder="0" allowfullscreen>
                                
                                                                     http://www.youtube.com/dev 
                                
                                                                </iframe>
                                
                                                                <p class="time">
                                                                    12:24 PM Yesterday
                                                                </p>
                                
                                                            </div>-->
                                <!--                            <div class="post">
                                                                <img class="profile_small" src="images/black_2.jpg"/>
                                                                <p class="name">
                                                                    Damilola Oyekanmi
                                                                </p>
                                                                <p class="status">
                                                                    This is a sample Multiple pic update
                                                                </p>
                                                                <ul class="box_small">
                                                                    <li><img src="images/p.jpg"/></li>
                                                                    <li><img src="images/t.jpg"/></li>
                                                                    <li><img src="images/p.jpg"/></li>
                                                                </ul>
                                                                <p class="time">
                                                                    7:50 PM Today
                                                                </p>
                                                            </div>-->
                            </div>
                        </div>

                    </div>					
                    <div class="clear"></div>
                </div>
                <div id="dialog"></div>
                <?php include_once("right.php"); ?>


            </div>

        </div>

    </body>
</html>