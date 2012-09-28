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
            var laststate= 0;
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
                jQuery(
                function($)
                {
                    $('.content').bind('scroll', function()
                    {
                        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
                            //alert(laststate);
                            laststate += 5;
                            showMorePost(laststate);
                            //                            alert(res);
                        }
                    })
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
                            <li class="righttab"><a href="gossout_hottest.php" >Hottest Gossip </a></li>
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
                            </div>
                            <span id="posts_loading"></span>
                        </div>
                        <div class="view-more ">
                            <p class="notice" style="text-align: center;"><a href="#" >Load more ...</a></p>
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
