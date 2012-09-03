<?php
if (!isset($_SESSION['auth'])) {
    session_unset();
    header("Location:index.php");
    exit;
}
if (isset($_GET['uid'])) {
    if (trim(clean($_GET['uid'])) != "") {
        $id = clean($_GET['uid']);
    } else {
        $id = $_SESSION['auth']['id'];
    }

    $sql = "SELECT p.id,p.dateJoined,  p.firstname, p.lastname, p.gender, p.dob,p.relationship_status,p.phone,p.url,p.bio,p.favquote,p.location,p.likes,p.dislikes,p.works,uc.community_id,c.name,c.category FROM user_personal_info AS p LEFT JOIN user_comm as uc on p.id = uc.user_id LEFT JOIN community as c on c.id = uc.community_id WHERE p.id = $id";
} else {
    $id = $_SESSION['auth']['id'];
    $sql = "SELECT p.id,p.dateJoined,  p.firstname, p.lastname, p.gender, p.dob,p.relationship_status,p.phone,p.url,p.bio,p.favquote,p.location,p.likes,p.dislikes,p.works,uc.community_id,c.name,c.category FROM user_personal_info AS p LEFT JOIN user_comm as uc on p.id = uc.user_id LEFT JOIN community as c on c.id = uc.community_id WHERE p.id = $id";
}
$result = mysql_query($sql);
if (mysql_num_rows($result) > 0) {
    $row = mysql_fetch_array($result);
}
$image = getUserPixSet($id);
$fullname = $row['firstname'] . " " . $row['lastname'];
$location = $row['location'];
$gender = $row['gender'];
$id = $row['id'];
$my_friends = getUserFriends($_SESSION['auth']['id'], true);
$userFriends = getUserFriends($id, true);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <?php include_once("head.php"); ?>

    </head>
    <body>
        <?php include_once("nav.php") ?>
        <div class="inner-container">
            <?php include_once("left.php"); ?>
            <div class="content" id="#style-2">

                <div id="profile">
                    <?php
                    if (mysql_num_rows($result) > 0) {//expects this all time
                        ?>
                        <div id="profile_pic">
                            <img src="<?php echo $image['image100x100'] ?>"/>
                        </div>
                        <div id="profile_details">
                            <p><span class="desc">Name: </span><?php echo $fullname ?></p>
                            <p><span class="desc">Location: </span><?php echo $location ?> </p>
                            <p><span class="desc">Gender: </span><?php
                    if ($gender == "M") {
                        echo " Male";
                    } else {
                        echo "Gender: Female";
                    }
                        ?></p> 
    <!--                         <p>Gossips: 20</p> -->
                        </div>                                       
                        <div id="profile_actions">
                            <?php
                            if ($id != $_SESSION['auth']['id']) {
                                ?>
                                <input type="submit" value="Tweak" id="tweak" <?php
                        $arr = canTweakWink($_SESSION['auth']['id'], $id, "T");
                        if (!$arr['status']) {
                            echo 'disabled=""';
                        }
                                ?> onclick="tweakwink(<?php echo $id; ?>, 'T')">
                                    <br>
                                        <input type="submit" value="Wink" id="wink" <?php
                               $arr = canTweakWink($_SESSION['auth']['id'], $id, "W");
                               if (!$arr['status']) {
                                   echo 'disabled=""';
                               }
                                ?> onclick="tweakwink(<?php echo $id; ?>, 'W')">
                                            <br>
                                                <?php
                                                if (array_key_exists($id, $my_friends)) {
                                                    ?>
                                                    <input type="submit" value="Quick Message"  onclick="showMessageModelDialog('dialog','<?php echo "To: $fullname" ?>','<?php echo $id ?>')" />
                                                    <br />
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input type="submit" value="<?php $frqstatus = checkFrqStatus($_SESSION['auth']['id'],$id); if($frqstatus['status']=="pending"){echo "Cancel Request";}else{echo "Send Friend Request";}?>" onclick="sendFriendRequest('<?php echo $id ?>')" id="sfr"/>
                                                    <br />
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </div>
                                            <div class="clear"></div>
                                            <?php
                                        } else {//This is suspecious...it should be a rare case but precaution
                                            ?>
                                            <div id="profile_pic">
                                                <img src="<?php echo $_SESSION['auth']['image100x100'] ?>"/>
                                            </div>
                                            <div id="profile_details">
                                                <p><?php echo "Name: $fullname" ?></p>
                                                <p><?php echo "Location: $location" ?> </p>
                                                <p><?php
                                        if ($gender == "M") {
                                            echo "Gender: Male";
                                        } else {
                                            echo "Gender: Female";
                                        }
                                            ?></p> 
                        <!--                         <p>Gossips: 20</p> -->
                                            </div>                                       
                                            <div id="profile_actions">
                        <!--                            <input type="submit" value="Tweak" class="ash_gradient" />
                                                <br />
                                                <input type="submit" value="Wink" class="ash_gradient" />
                                                <br />
                                                <input type="submit" value="Send Message" class="ash_gradient" />
                                                <br />
                                                <input type="submit" value="See Friends" class="ash_gradient" />-->
                                            </div>
                                            <div class="clear"></div>
                                            <?php
                                        }
                                        ?>
                                        </div>
                                        <div class="p">
                                            <div id="tabs">
                                                <ul class="tabs">
                                                    <li class="lefttab"><a href="userTimeline.php">Updates </a></li>
                                                    <?php if (array_key_exists($id, $my_friends) || $id == $_SESSION['auth']['id']) { ?>
                                                        <li><a href="userPhotos.php"> Photos </a></li>
                                                        <!--                                                <li><a href="gossoutpages/userVideos.php"> Videos </a></li>-->
                                                        <li ><a href="userPersonalInfo.php"> Info </a></li>
                                                        <li class="righttab"><a href="friends.php"> Friends (<?php echo count($userFriends);?>)</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>

                                        </div>
                                        <?php ?>
                                        </div>

                                        <?php include_once("right.php"); ?>
                                        </div>
                                        <div id="dialog"></div>
                                        </body>
                                        </html>
