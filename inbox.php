<?php

session_start();
include 'executecommand.php';
connect();
$array = showInbox($_SESSION['auth']['id']);
echo '<div class="posts">';
if ($array['status'] == "success"){
    foreach ($array as $msg) {
        if ($msg['isUser']) {
            echo "<div class='post' id='inb_msg" . $msg['msgid'] . "'><img class='profile_small' src='" . $msg['img'] . "'/><img class='profile_small' src='images/reply.png'/><p class='name'><a href='page.php?view=messages&open=" . $msg['id'] . "'>" . $msg['name'] . "</a></p><p class='status'>" . $msg['text'] . "</p><p class='time' id='inb_msg_tim" . $msg['msgid'] . "'>" . $msg['time'] . "</p></div><script>setTimeout(timeUpdate,2000,'" . $msg['rawTime'] . "','inb_msg_tim" . $msg['rawTime'] . "');</script>";
        } else {
            echo "<div class='post' id='inb_msg" . $msg['msgid'] . "'><img class='profile_small' src='" . $msg['img'] . "'/><p class='name'><a href='page.php?view=messages&open=" . $msg['id'] . "'>" . $msg['name'] . "</a></p><p class='status'>" . $msg['text'] . "</p><p class='time' id='inb_msg_tim" . $msg['msgid'] . "'>" . $msg['time'] . "</p></div><script>setTimeout(timeUpdate,2000,'" . $msg['rawTime'] . "','inb_msg_tim" . $msg['rawTime'] . "');</script>";
        }
    }
}else{
    echo "No message available";
}
echo '</div>';
//exit;
//$sql = "UPDATE `privatemessae` SET `status`='D' WHERE `receiver_id` = '".$_SESSION['auth']['id']."' and `status` = 'N'";
//    $sqlGet = "SELECT p.id, p.sender_id,u.firstname as senderFname,u.lastname as senderLname, p.receiver_id, r.firstname as receiverFname, r.lastname as receiverLname,p.message, p.time, p.status FROM  `privatemessae` AS p JOIN user_personal_info AS u ON u.id = p.sender_id JOIN user_personal_info as r on r.id = p.receiver_id WHERE p.receiver_id =".$_SESSION['auth']['id']." OR p.sender_id =".$_SESSION['auth']['id']." order by p.time";
//    $arr = array();
//    $resultGet = mysql_query($sqlGet);
//    $response = "";
//    if (mysql_num_rows($resultGet) > 0) {
//      
//        while ($row = mysql_fetch_array($resultGet)) {
//            $eachMsgarr = array();
//            if ($row['sender_id'] == $_SESSION['auth']['id']) {
//                $image = getUserPixSet($row['receiver_id']);
//                $response = "<div class='post' id='" . $row['id'] . "'><img class='profile_small' src='" . $image['image50x50'] . "'/><img class='profile_small' src='images/reply.png'/><p class='name'><a href='page.php?view=messages&open=" . $row['receiver_id'] . "'>" . $row['receiverLname'] . ' ' . $row['receiverFname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time' id='tim".$row['id']."'>" . agoServer($row['time']) . "</p></div><script>setTimeout(timeUpdate,2000,'".$row['time']."','tim".$row['id']."');makeMeClickable('#"+$row['id']+"');</script>";
//                $arr[$row['receiver_id']] = array($row['receiverLname'] . ' ' . $row['receiverFname'], $response);
//            } else {
//                $status = "";
//                if (($row['status'] == "D" || $row['status'] == "N")) {
//                    $eachMsgarr['status'] = 'N';
//                    $status = " shade";
//                } else {
//                    $eachMsgarr['status'] = 'R';
//                }
//                $image = getUserPixSet($row['sender_id']);
//                $response = "<div class='post$status' id='" . $row['id'] . "'><img class='profile_small' src='" . $image['image50x50'] . "'/><p class='name'><a href='page.php?view=messages&open=" . $row['sender_id'] . "'>" . $row['senderLname'] . ' ' . $row['senderFname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time' id='tim".$row['id']."'>" . agoServer($row['time']) . "</p></div><script>setTimeout(timeUpdate,2000,'".$row['time']."','tim".$row['id']."');makeMeClickable('#"+$row['id']+"');</script>";
//                $arr[$row['sender_id']] = array($row['senderLname'] . ' ' . $row['senderFname'], $response);
//            }
//        }
//        mysql_query($sql);
//        $arr = array_reverse($arr);
//        $response = "";
//        $count = 0;
//        foreach ($arr as $x) {
//            if ($count == 0) {
//                echo $x[1];
//            } else {
//               echo $x[1];
//            }
//            $count++;
//        }
//    }else{
//        echo "No messages";
//    }
//    //echo $response;
?>
