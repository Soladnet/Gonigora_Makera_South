<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php include_once("head.php"); ?>
    </head>
    <body id="#style">

        <div id="page">
            <?php include_once("nav.php") ?>
            <div class="inner-container" >
                <?php include_once("left.php"); ?>


                <div class="content" id="#style">
                    <div class="tabs">
                        <div class="timeline">
                            <div class="posts">
                                <?php
                                if ($_GET['view']=="notification") {
                                    echo toSentenceCase(clean($_GET['view']));
                                    echo showPostAndComment($_SESSION['auth']['id'], 0, 0, clean($_GET['open']));
                                } else if ($_GET['view']=="tweakwink") {
                                    $sql = "SELECT tw.id,tw.sender_id,tw.type,if(tw.type='T','Tweaked you','Winked you') as naration,tw.time,concat(p.lastname,' ',p.firstname) as sender_fullname FROM `tweakwink` as tw JOIN user_personal_info as p on tw.sender_id=p.id WHERE tw.`receiver_id`=" . $_SESSION['auth']['id'] . " AND tw.id=" . clean($_GET['open']);
                                    $result = mysql_query($sql);
                                    if (mysql_num_rows($result) > 0) {
                                        $row = mysql_fetch_array($result);
                                        $image = getUserPixSet($row['sender_id']);
                                        $option = "";
                                        if($row['type']=="T"){
                                            $option = "<span onclick='tweakwink(\"".$row['sender_id']."\",\"T\")'>Tweak back</span>";
                                        }else if($row['type']=="W"){
                                            $option = "<span onclick='tweakwink(\"".$row['sender_id']."\",\"W\")'>Wink Back</span>";
                                        }
                                        echo '<div class="post"><img class="profile_small" src="'.$image['image50x50'].'"/><p class="name"><a href="page.php?view=profile&uid='.$row['sender_id'].'">'.$row['sender_fullname'].'</p><p class="status">'.$row['naration'].'</p><p class="time">'.agoServer($row['time']).'</p><div class="post_activities"> '.$option.'</div></div>';
                                    }else{
                                        echo "Page Not Found";
                                    }
                                }else if ($_GET['view']=="request") {
                                    $arr = getFriendRequest($_SESSION['auth']['id']);
                                    if(isset($arr['status'])){
                                        if($arr['status']=="success"){
                                            foreach ($arr as $value){
                                                if(isset($value['rowId']) && $value!="success"){
                                                    echo "<div class='post' id='frq$value[rowId]'><img class='profile_small' src='$value[img]'/><p class='name'><a href='page.php?view=profile&uid=$value[id]'>$value[fullname]</a></p><p class='status'>$value[caption]</p><p class='time'>$value[time]</p><span id='requestoption$value[id]'><input type='submit' value='Accept' onclick='acceptOrDeclineFrq(\"$value[id]\",\"acpt\",\"$value[rowId]\")' class='ash_gradient' /><input type='submit' value='Decline' class='ash_gradient' onclick='acceptOrDeclineFrq(\"$value[id]\",\"decl\",\"$value[id]\")' /></span></div>";
                                                }
                                            }
                                        }else{
                                            echo "No request to respond to";
                                        }
                                    }
                                }else{
                                    echo "<p>Page Not found</a>";
                                }
                                ?>
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
