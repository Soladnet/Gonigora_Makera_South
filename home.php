<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php include_once("head.php"); ?>
        <script type="text/javascript" src="js/home_ajaxfileupload.js"></script> 
        <style>
            #status_community{display: none;font-size: .7em;color:#999;}
        </style>

    </head>
    <body>

        <div id="page">
            <?php include_once("nav.php") ?>
            <div class="inner-container" >
                <?php
                include_once("left.php");
                ?>

                <div class="content">
                    <div id="tabs">
                        <ul>
                            <li class="lefttab"><a href="timeline.php" >Timeline</a></li>
                            <li class="righttab"><a href="gossout_hottest.php" >Trending</a></li>
                            <li class="righttab"><a href="communityfeeds.php" >Community Feeds</a></li>
                        </ul>
                        

                    </div>					
                    <div class="clear"></div>
                </div>
                <div id="dialog"></div>
                <?php include_once("right.php"); ?>


            </div>

        </div>

    </body>
</html>
