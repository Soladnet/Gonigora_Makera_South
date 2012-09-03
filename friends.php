<?php

session_start();
include 'executecommand.php';
connect();
$str = $_SERVER['HTTP_REFERER'];
$str = clean(strstr($str, "uid="));
if (trim($str) != "") {
    $arr = explode("&", $str);
    $arr = explode("=", $arr[0]);
    if (trim($arr[1]) != "") {
        $id = trim($arr[1]);
    } else {
        $id = $_SESSION['auth']['id'];
    }
} else {
    $id = $_SESSION['auth']['id'];
}
$friends = getUserFriends($id, true);
$myfriends = getUserFriends($_SESSION['auth']['id']);
if (count($friends) > 0) {
    foreach ($friends as $key => $details) {
        if ($key != $_SESSION['auth']['id']) {
            $option = array_key_exists($key, $myfriends) ? "" : "<span onclick=\"sendFriendRequest('$key')\" style='font-size:.6em'>Send Friend Request</span>";
        } else {
            $option = "";
        }

        echo '<div class="post" style="width:48%;float:left;"><img class="profile_small" src="' . $details['image']['image50x50'] . '"/><p class="name"><a href="page.php?view=profile&uid=' . $details['id'] . '">' . toSentenceCase($details['fullname']) . '</a></p><p class="status">' . $details['location'] . '</p><p class="time"><span class="people_loading'.$details['id'].'"></span></p><div class="post_activities">' . $option . '</div></div>';
    }
} else {
    echo "No Friends was found!";
}
?>
