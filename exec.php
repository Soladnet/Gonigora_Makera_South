<?php

session_start();
include 'executecommand.php';
include 'encryptionClass.php';
$encrypt = new Encryption();
if (isset($_POST['action'])) {
    $conn_arr = connect();
    if (isset($_POST['count'])) {
        $arr = getUpdateCount($_SESSION['auth']['id']);
        echo json_encode($arr);
    } else if (isset($_POST['flcikr'])) {
        if ($_POST['action'] == "GossBag") {
            $arr = getGossbag($_SESSION['auth']['id']);
            echo json_encode($arr);
        } else if ($_POST['action'] == "Messages") {
            $arr = showInbox($_SESSION['auth']['id']);
//            $arr = array_reverse($arr);
            echo json_encode($arr);
        } else if ($_POST['action'] == "Friend Requests") {
            $arr = getFriendRequest($_SESSION['auth']['id']);
            echo json_encode($arr);
        }
    } else if (isset($_POST['posts'])) {
        if ($_POST['action'] == "posts") {
            $text = $_POST['posts'];
            $senderFullname = $_SESSION['auth']['fullname'];
            $userId = $_SESSION['auth']['id'];
            $communityId = $_SESSION['auth']['community']['id'];
            if (isset($_POST['status'])) {
                $status = $_POST['status'];
            } else {
                $status = "false";
            }

            if ($communityId == NULL) {
                $arr['status'] = "failed";
                $arr['message'] = "Please join a community before you can send a post";
            } else {
                $arr = sendPost($userId, $_SESSION['auth']['community']['id'], $_SESSION['auth']['community']['name'], $text, $senderFullname, $status);
            }
            echo json_encode($arr);
        } else if ($_POST['action'] == "commentsPost") {
            $text = $_POST['posts'];
            $senderFullname = $_SESSION['auth']['fullname'];
            $userId = $_SESSION['auth']['id'];
            $postId = $_POST['sourceId'];
            $arr = sendComment($userId, $postId, $text, $senderFullname);
            echo json_encode($arr);
        } else if ($_POST['action'] == "commentConver") {
            $text = $_POST['posts'];
            $senderFullname = $_SESSION['auth']['last_name'] . ' ' . $_SESSION['auth']['first_name'];
            $userId = $_SESSION['auth']['id'];
            $postId = $_POST['sourceId'];
            $arr = sendPirvateMessage($userId, $postId, $text, $senderFullname);
            echo json_encode($arr);
        } else if ($_POST['action'] == "morePost") {
            $userId = $_SESSION['auth']['id'];
            if($_POST['tab']=="FTL"){
                echo showPostAndCommentFTL($userId, $_POST['posts']);
            }else if($_POST['tab']=="TRD"){
                echo showPostAndCommentTR($userId, 80, $_POST['posts']);
            }else if($_POST['tab']=="CF"){
                echo showPostAndCommentCF($userId, 80, $_POST['posts']);
            }
        }
    } else if (isset($_POST['inbox'])) {
        $userId = $_SESSION['auth']['id'];
        if ($_POST['action'] == "inbox") {
            echo showInbox($userId, 0, 20, false);
        } else if ($_POST['action'] == "conver") {
            $contactId = clean($_POST['inbox']);
            $arr = getConversationUpdate($contactId, $userId);
            echo json_encode($arr);
        }
    } else if (isset($_POST['phn']) && isset($_POST['urls']) && isset($_POST['rels']) && $_POST['action'] == 'update') {
        $sql = "UPDATE  `user_personal_info` SET  `phone` =  '" . clean(htmlspecialchars($_POST['phn'])) . "',`url` =  '" . clean(htmlspecialchars($_POST['urls'])) . "',`relationship_status` =  '" . clean(htmlspecialchars($_POST['rels'])) . "' WHERE `id` =" . $_SESSION['auth']['id'];
        mysql_query($sql);
        $arr = array();
        if (mysql_affected_rows() > 0) {
            $arr['status'] = "Your update was Successfull!";
            $_SESSION['auth']['relationship_status'] = $_POST['rels'];
            $_SESSION['auth']['phone'] = $_POST['phn'];
            $_SESSION['auth']['url'] = $_POST['urls'];
            echo json_encode($arr);
        } else {
            $arr['status'] = "No changes was made!";
            echo json_encode($arr);
        }
    } else if (isset($_POST['bio']) && $_POST['action'] == 'update') {
        $sql = "UPDATE  `user_personal_info` SET  `bio` =  '" . clean(htmlspecialchars($_POST['bio'])) . "' WHERE `id` =" . $_SESSION['auth']['id'];
        mysql_query($sql);
        $arr = array();
        if (mysql_affected_rows() > 0) {
            $arr['status'] = "Your update was Successfull!";
            $_SESSION['auth']['bio'] = $_POST['bio'];
            echo json_encode($arr);
        } else {
            $arr['status'] = "No changes was made!";
            echo json_encode($arr);
        }
    } else if (isset($_POST['quote']) && $_POST['action'] == 'update') {
        $sql = "UPDATE  `user_personal_info` SET  `favquote` =  '" . clean(htmlspecialchars($_POST['quote'])) . "' WHERE `id` =" . $_SESSION['auth']['id'];
        mysql_query($sql);
        $arr = array();
        if (mysql_affected_rows() > 0) {
            $arr['status'] = "Your update was Successfull!";
            $_SESSION['auth']['quote'] = $_POST['quote'];
            echo json_encode($arr);
        } else {
            $arr['status'] = "No changes was made!";
            echo json_encode($arr);
        }
    } else if (isset($_POST['location']) && $_POST['action'] == 'update') {
        $sql = "UPDATE  `user_personal_info` SET  `location` =  '" . clean(htmlspecialchars($_POST['location'])) . "' WHERE `id` =" . $_SESSION['auth']['id'];
        mysql_query($sql);
        $arr = array();
        if (mysql_affected_rows() > 0) {
            $arr['status'] = "Your update was Successfull!";
            $_SESSION['auth']['location'] = $_POST['location'];
            echo json_encode($arr);
        } else {
            $arr['status'] = "No changes was made!";
            echo json_encode($arr);
        }
    } else if (isset($_POST['likes']) && $_POST['action'] == 'update') {
        $sql = "UPDATE  `user_personal_info` SET  `likes` =  '" . clean(htmlspecialchars($_POST['likes'])) . "' WHERE `id` =" . $_SESSION['auth']['id'];
        mysql_query($sql);
        $arr = array();
        if (mysql_affected_rows() > 0) {
            $arr['status'] = "Your update was Successfull!";
            $_SESSION['auth']['likes'] = $_POST['likes'];
            echo json_encode($arr);
        } else {
            $arr['status'] = "No changes was made!";
            echo json_encode($arr);
        }
    } else if (isset($_POST['dislikes']) && $_POST['action'] == 'update') {
        $sql = "UPDATE  `user_personal_info` SET  `dislikes` =  '" . clean(htmlspecialchars($_POST['dislikes'])) . "' WHERE `id` =" . $_SESSION['auth']['id'];
        mysql_query($sql);
        $arr = array();
        if (mysql_affected_rows() > 0) {
            $arr['status'] = "Your update was Successfull!";
            $_SESSION['auth']['dislikes'] = $_POST['dislikes'];
            echo json_encode($arr);
        } else {
            $arr['status'] = "No changes was made!";
            echo json_encode($arr);
        }
    } else if (isset($_POST['wrk']) && $_POST['action'] == 'update') {
        $sql = "UPDATE  `user_personal_info` SET  `works` =  '" . clean(htmlspecialchars($_POST['wrk'])) . "' WHERE `id` =" . $_SESSION['auth']['id'];
        mysql_query($sql);
        $arr = array();
        if (mysql_affected_rows() > 0) {
            $arr['status'] = "Your update was Successfull!";
            $_SESSION['auth']['works'] = $_POST['wrk'];
            echo json_encode($arr);
        } else {
            $arr['status'] = "No changes was made!";
            echo json_encode($arr);
        }
    } else if (isset($_POST['com']) && $_POST['action'] == 'update') {
        $arr = subscribeToCommunity($_SESSION['auth']['id'], $_POST['com'], $_POST['comcat']);
        echo json_encode($arr);
    } else if (isset($_POST['search'])) {
        $arr = search($_POST['search']);
        echo json_encode($arr);
    } else if (isset($_POST['ppix'])) {
        $sql1 = "INSERT INTO `user_profile_pix` (`username`, `pix_id`) VALUES ('" . $_SESSION['auth']['id'] . "', '" . $encrypt->safe_b64decode($_POST['ppix']) . "')";
        mysql_query($sql1);
        $arr = array();
        if (mysql_affected_rows() > 0) {
            $sql = "SELECT pp.`pix_id`,pu.`100x100`,pu.`50x50`,pu.`35x35` FROM `user_profile_pix` as pp JOIN pictureuploads as pu on pu.id = pp.`pix_id` WHERE pp.`username` = " . $_SESSION['auth']['id'] . " order by date desc limit 1";
            $result = mysql_query($sql);
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                $_SESSION['auth']['image50x50'] = $row['50x50'];
                $_SESSION['auth']['image35x35'] = $row['35x35'];
                $_SESSION['auth']['image100x100'] = $row['100x100'];
                $arr['status'] = "success";
                $arr['message'] = "Your update was Successfull!";
            } else {
                $arr['status'] = "failed";
                $arr['message'] = "Opps! something unexpected happened...try again";
            }

            echo json_encode($arr);
        } else {
            $arr['status'] = "No changes was made!";
            echo json_encode($arr);
        }
    } else if (isset($_POST['album'])) {
        if ($_POST['album'] == "new") {

            $sql = "SELECT * FROM album WHERE username=" . $_SESSION['auth']['id'] . " AND album='" . clean(htmlspecialchars($_POST['name'])) . "'";
            $result = mysql_query($sql);
            if (mysql_num_rows($result) > 0) {
                $status = true;
            } else {
                $sql = "INSERT INTO `album`(`username`, `album`) VALUES ('" . $_SESSION['auth']['id'] . "','" . clean(htmlspecialchars($_POST['name'])) . "')";
                mysql_query($sql);
                $status = false;
                if (mysql_affected_rows() > 0) {
                    $status = true;
                }
            }

//            $arr = getAlbum($_SESSION['auth']['id']);
            $arr['status'] = $status;

            echo json_encode($arr);
        } else if ($_POST['album'] == "getImg") {
            $sql = "SELECT id , 35x35 as img3535,50x50 as img5050,100x100 as img100100,date_added,comment FROM pictureuploads WHERE album_id=" . $_POST['action'];
            $pix = mysql_query($sql);
            $img = array();
            if (mysql_num_rows($pix) > 0) {
                while ($pixRow = mysql_fetch_array($pix)) {
                    $img[] = $pixRow;
                }
            }
            echo json_encode($img);
        } else {
            $arr = getAlbum($_POST['action']);
            echo json_encode($arr);
        }
    } else if (isset($_POST['timeUpdate'])) {
        $arr['time'] = agoServer($_POST['timeUpdate']);
        echo json_encode($arr);
    } else if (isset($_POST['frq'])) {
        $arr = sendFrq($_SESSION['auth']['id'], $_POST['frq'], $_SESSION['auth']['fullname']);
        echo json_encode($arr);
    } else if (isset($_POST['cfrq'])) {
        $arr = cancelFrq($_SESSION['auth']['id'], $_POST['cfrq']);
        echo json_encode($arr);
    } else if (isset($_POST['acceptfrq'])) {
        if ($_POST['action'] == "acpt") {
            $arr = acceptFrq($_SESSION['auth']['id'], $_POST['acceptfrq'], $_POST['key']);
            echo json_encode($arr);
        } else if ($_POST['action'] == "decl") {
            $arr = declineFrq($_SESSION['auth']['id'], $_POST['acceptfrq'], $_POST['key']);
            echo json_encode($arr);
        }
    } else if (isset($_POST['join'])) {
        $arr = joinCommunity($_SESSION['auth']['id'], $_POST['join']);
        echo json_encode($arr);
    } else if (isset($_POST['unsub'])) {
        $arr = unsubscribe($_SESSION['auth']['id'], $_POST['unsub']);
        echo json_encode($arr);
    } else if (isset($_POST['sub'])) {
        $arr = subscribe($_SESSION['auth']['id'], $_POST['sub']);
        echo json_encode($arr);
    } else if (isset($_POST['sendmsg'])) {
        $arr = sendPrivateMessage($_SESSION['auth']['id'], $_POST['sub']);
        echo json_encode($arr);
    } else if (isset($_POST['tweakwink'])) {
        $userId = $_SESSION['auth']['id'];
        $receiver_id = $_POST['action'];
        $tweakwink = $_POST['tweakwink'];
        $arr = sendTweakWink($userId, $receiver_id, $tweakwink);
        echo json_encode($arr);
    } else if ($_POST['action'] == "gossout") {
        $userid = $_SESSION['auth']['id'];
        $community_id = $_SESSION['auth']['community']['id'];
        $community_name = $_SESSION['auth']['community']['name'];
        $senderFullname = $_SESSION['auth']['fullname'];
        $arr = array();
        if (isset($_POST['gossout'])) {
            $arr = gossout($userid, $_POST['gossout'], $community_id, $community_name, $senderFullname);
        }
        if (isset($_POST['facebook'])) {
            $fbUser = $conn_arr['facebook_obj']->getUser();
            if (isset($_POST['gossout'])) {
                if ($fbUser) {
                    $sql = "SELECT p.id,p.post,c.name,p.community_id,p.sender_id,p.time,concat(s.`firstname`,' ',s.`lastname`) as fullname,if(cp.`100x100` IS NULL,'images/logo75x75.png',cp.`100x100`) as image FROM `post` as p JOIN user_personal_info as s on p.sender_id=s.id JOIN community as c on p.`community_id`=c.id LEFT JOIN community_pix AS cp ON p.id = cp.post_id WHERE p.id=" . $_POST['facebook'];
                    $result = mysql_query($sql);
                    $row = mysql_fetch_array($result);
                    if (!strpos($row['post'], "<span>")) {
                        if ($row['image'] == "images/logo75x75.png") {
                            $str = "";
                            if ($row['sender_id'] != $_SESSION['auth']['id']) {
                                $str = $row['fullname'] . " says: ";
                            }
                            try {
                                $fbPostId = sendToFacbook($conn_arr['facebook_obj'], '/me/feed', "POST", array('message' => $str . $row['post']));
                                $arr['fbstatus'] = "success";
                                $arr['fb_post_id'] = $fbPostId;
                                $arr['fbmsg'] = "Shared with facebook";
                            } catch (FacebookApiException $e) {
                                $arr['fbstatus'] = "failed";
                                $arr['fbmsg'] = "Opps! We cannot connect you to facebook now...Make sure you are connected to internet";
                            }
                        } else {
                            try {
                                $str = "";
                                if ($row['sender_id'] == $_SESSION['auth']['id']) {
                                    $str = "I just shared a picture on Gossout";
                                } else {
                                    $str = toSentenceCase($row['fullname']) . ' shared a picture of ' . toSentenceCase($row['name']) . ' community on Gossout';
                                }
                                $fbPostId = sendToFacbook($conn_arr['facebook_obj'], '/me/feed', "POST", array(
                                    'name' => $str,
                                    'link' => 'www.gossout.com/page.php?view=notification&open=' . $row['id'],
                                    'message' => $row['post'],
                                    'caption' => $row['post'],
                                    'picture' => 'http://gossout.com/' . $row['image']
                                        ));
                                $arr['fbstatus'] = "success";
                                $arr['fb_post_id'] = $fbPostId;
                                $arr['fbmsg'] = "Shared with facebook";
                            } catch (FacebookApiException $e) {
                                $arr['fbstatus'] = "failed";
                                $arr['fbmsg'] = "Opps! We cannot connect you to facebook now...try again later";
                            }
                        }
                    } else {
                        $arr['fbstatus'] = "failed";
                        $arr['fbmsg'] = "This post cannot be sent to facebook at this time";
                    }
                } else {
                    $arr['fbstatus'] = "failed";
                    $arr['fbmsg'] = "Facebook authentication failed";
                }
            } else {
                if ($fbUser) {
                    $sql = "SELECT p.id,p.post,c.name,p.community_id,p.sender_id,p.time,concat(s.`firstname`,' ',s.`lastname`) as fullname,if(cp.`100x100` IS NULL,'images/logo75x75.png',cp.`100x100`) as image FROM `post` as p JOIN user_personal_info as s on p.sender_id=s.id JOIN community as c on p.`community_id`=c.id LEFT JOIN community_pix AS cp ON p.id = cp.post_id WHERE p.id=" . $_POST['facebook'];
                    $result = mysql_query($sql);
                    $row = mysql_fetch_array($result);
                    if (!strpos($row['post'], "<span>")) {
                        if ($row['image'] == "images/logo75x75.png") {
                            $str = "";
                            if ($row['sender_id'] != $_SESSION['auth']['id']) {
                                $str = $row['fullname'] . " says: ";
                            }
                            try {
                                $fbPostId = sendToFacbook($conn_arr['facebook_obj'], '/me/feed', "POST", array('message' => $str . $row['post']));
                                $arr['fbstatus'] = "success";
                                $arr['fb_post_id'] = $fbPostId;
                                $arr['fbmsg'] = "Shared with facebook";
                            } catch (FacebookApiException $e) {
                                $arr['fbstatus'] = "failed";
                                $arr['fbmsg'] = "Opps! We cannot connect you to facebook now...try again later";
                            }
                        } else {
                            try {
                                $str = "";
                                if ($row['sender_id'] == $_SESSION['auth']['id']) {
                                    $str = "I just shared a picture on Gossout";
                                } else {
                                    $str = toSentenceCase($row['fullname']) . ' shared a picture of ' . toSentenceCase($row['name']) . ' community on Gossout';
                                }
                                $fbPostId = sendToFacbook($conn_arr['facebook_obj'], '/me/feed', "POST", array(
                                    'name' => $str,
                                    'link' => 'www.gossout.com/page.php?view=notification&open=' . $row['id'],
                                    'message' => $row['post'],
                                    'caption' => $row['post'],
                                    'picture' => 'http://gossout.com/' . $row['image']
                                        ));
                                $arr['fbstatus'] = "success";
                                $arr['fb_post_id'] = $fbPostId;
                                $arr['fbmsg'] = "Shared with facebook";
                            } catch (FacebookApiException $e) {
                                $arr['fbstatus'] = "failed";
                                $arr['fbmsg'] = "Opps! We cannot connect you to facebook now...try again later";
                            }
                        }
                    } else {
                        $arr['fbstatus'] = "failed";
                        $arr['fbmsg'] = "This post cannot be sent to facebook at this time";
                    }
                } else {
                    $arr['fbstatus'] = "failed";
                    $arr['fbmsg'] = "Facebook authentication failed";
                }
            }
        }
        echo json_encode($arr);
    } else if ($_POST['action'] == "deletePhoto") {
        $fileId = $encrypt->safe_b64decode($_POST['fileId']);
        $sql = "SELECT `id`, `album_id`, `user_id`, `35x35`, `50x50`, `100x100`, `original`, `date_added`, `comment` FROM `pictureuploads` WHERE id=$fileId";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $arr['status'] = "success";
            $row = mysql_fetch_array($result);
            unlink($row['35x35']);
            unlink($row['50x50']);
            unlink($row['100x100']);
            unlink($row['original']);
            $sql = "DELETE FROM `pictureuploads` WHERE `id`=$fileId";
            mysql_query($sql);
        } else {
            $arr['status'] = "failed";
        }
        echo json_encode($arr);
    }else if(isset ($_POST['ola'])){
        $arr['status'] = "Success";
        echo json_encode($arr);
    } 
    
//    else if ($_POST['action'] == "albumCover") {
//        $fileId = $encrypt->safe_b64decode($_POST['fileID']);
//        $albumId = $encrypt->safe_b64decode($_POST['album']);
//        
////        $result = mysql_query($sql);
////        if (mysql_num_rows($result) > 0) {
//        $arr['status'] = "success";
////            $row = mysql_fetch_array($result);
////            unlink($row['35x35']);
////            unlink($row['50x50']);
////            unlink($row['100x100']);
////            unlink($row['original']);
////            $sql = "DELETE FROM `pictureuploads` WHERE `id`=$fileId";
////            mysql_query($sql);
////        } else {
////            $arr['status'] = "failed";
////        }
//        echo json_encode($arr);
//    }
} else {
    echo json_encode(array("status" => "failed"));
}
?>