<?php
if (!isset($_SESSION['auth'])) {
    session_unset();
    header("Location:index.php");
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php include_once("head.php"); ?>
        
    </head>
    <body>
        <div id="page">
            <?php include_once("nav.php") ?>
            <div class="inner-container">
                <?php include_once("left.php"); ?>


                <div class="content">
                    <div id="tabs">
                        <ul>
                            <?php
                            if (isset($_GET['open'])) {
                                echo '<li class="lefttab"><a href="#conversation" >Conversation</a></li><li class="righttab"><a href="gossoutpages/inbox.php" >Inbox</a></li>';
                            } else {
                                echo '<li class="lefttab"><a href="gossoutpages/inbox.php"  >Inbox</a></li>';
                            }
                            ?>

                            <!--                            <li> <a href="#sent" > Sent Messages</a></li>
                                                        <li><a href="#drafts" class="righttab"> Drafts</a></li>-->
                        </ul>
                        <?php
                        if (isset($_GET['open'])) {
                            echo '<div id="conversation" ><div class="posts">';
                            $contactId = clean($_GET['open']);
                            echo getInboxMessage($contactId, $_SESSION['auth']['id']);
                            echo "</div></div>
                                <script>
                                    setTimeout(getConversationUpdate,5000,$contactId);
                                        
                                </script>";
                        }
                        ?>

<!--                        <div id="inbox">
                            
                            <?php
//                            if (!isset($_GET['open'])){
//                                echo "<script>getInbox();</script>";
//                            }else{
//                                echo "<div><img src='images/loading.gif' id='messagesgloading' /></div><script>setTimeout(getInbox,5000);</script>";
//                            }
                            
                            ?>

                        </div>-->

                        <!--                        <div id="sent">
                        
                                                </div>
                                                <div id="drafts" >
                        
                                                </div>-->
                    </div>
                </div>



                <?php include_once("right.php"); ?>
            </div>
        </div>
    </div>
</div>
</div> 
<div id="404" title="Error 404!">Page not found!!!</div>
</body>
</html>
