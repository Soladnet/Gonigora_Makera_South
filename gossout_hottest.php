<?php
session_start();
include 'executecommand.php';
connect();
?>
<div class="timeline">
<div class="posts" id="tr_post">
    <?php
    $where = "";
    $from = "80";
    $userId = $_SESSION['auth']['id'];
    $all = "";
    $lowlimit = "0";
    if ($all) {
        $where = "where p.sender_id = $userId";
    }
    if ($from) {
        if ($where) {
            $where .= " AND p.community_id=$from";
        } else {
            $where = "where p.community_id=$from";
        }
    }

    $limit = "Limit $lowlimit,15";

    $postValue = "";
    $postSql = "SELECT p.id,p.post,c.name,p.community_id,p.sender_id,p.time,p.status,if(p.status='Show',concat(s.`firstname`,' ',s.`lastname`),'Anonymous') as fullname,cp.`250x250`,cp.`original` FROM `post` as p JOIN user_personal_info as s on p.sender_id=s.id JOIN community_subscribers as cs on (cs.user=$userId and cs.`community_id`=p.`community_id`) JOIN community as c on cs.`community_id`=c.id LEFT JOIN community_pix AS cp ON p.id = cp.post_id $where order by p.id desc $limit";
    $postResult = mysql_query($postSql) or die(mysql_error());
    if (mysql_num_rows($postResult) > 0) {
        while ($postRow = mysql_fetch_array($postResult)) {
            $name = '<a href="page.php?view=profile&uid=' . $postRow['sender_id'] . '">' . toSentenceCase($postRow['fullname']) . '</a>';
            $image = getUserPixSet($postRow['sender_id']);
            if ($postRow['fullname'] == "Anonymous") {
                $name = $postRow['fullname'];
                $image['image50x50'] = "images/anony.png";
            }


            if ($postRow['250x250'] == NULL) {
                $postValue .= '<div class="post" id=tr_' . $postRow['id'] . '>
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name">' . $name . '</p><p class="status">' . make_links_clickable($postRow['post']) . '</p><p class="time" id="tp' . $postRow['id'] . '">' . agoServer($postRow['time']) . '</p><div class="post_activities"> <span onclick="showGossoutModeldialog(\'dialog\',\'' . $postRow['id'] . '\');">Gossout</span> . <span onclick="showCommentBox(\'box' . $postRow['id'] . '\',\'' . $postRow['id'] . '\',\'' . $_SESSION['auth']['image35x35'] . '\')">Comment</span>';
                if ($postRow['name'] != "Zuma Broadcast") {
                    $postValue .= ' . <span><a href="page.php?view=community&com=' . $postRow['community_id'] . '">in ' . $postRow['name'] . '</a></span></div><span id="comments' . $postRow['id'] . '"><script>setTimeout(timeUpdate,20000,\'' . $postRow['time'] . '\',\'tp' . $postRow['id'] . '\');</script>';
                } else {
                    $postValue .= '</div><span id="comments' . $postRow['id'] . '"><script>setTimeout(timeUpdate,20000,\'' . $postRow['time'] . '\',\'tp' . $postRow['id'] . '\');</script>';
                }
            } else {
                $postValue .= '<div class="post" id=tr_' . $postRow['id'] . '>
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name">' . $name . '</p><p class="status">' . make_links_clickable($postRow['post']) . '</p><ul class="box"><li><img src="' . $postRow['250x250'] . '" alt="' . $postRow['name'] . '" onclick="enlargePostPix(\'' . $postRow['250x250'] . '\',\'Shared with ' . $postRow['name'] . '\');"/></li></ul><p class="time" id="tp' . $postRow['id'] . '">' . agoServer($postRow['time']) . '</p><div class="post_activities"> <span onclick="showGossoutModeldialog(\'dialog\',\'' . $postRow['id'] . '\');">Gossout</span> . <span onclick="showCommentBox(\'box' . $postRow['id'] . '\',\'' . $postRow['id'] . '\',\'' . $_SESSION['auth']['image35x35'] . '\')">Comment</span>';
                if ($postRow['name'] != "Zuma Broadcast") {
                    $postValue .= ' . <span><a href="page.php?view=community&com=' . $postRow['community_id'] . '">in ' . $postRow['name'] . '</a></span></div><span id="comments' . $postRow['id'] . '"><script>setTimeout(timeUpdate,20000,\'' . $postRow['time'] . '\',\'tp' . $postRow['id'] . '\');</script>';
                } else {
                    $postValue .= '</div><span id="comments' . $postRow['id'] . '"><script>setTimeout(timeUpdate,20000,\'' . $postRow['time'] . '\',\'tp' . $postRow['id'] . '\');</script>';
                }
            }
            $commentSql = "SELECT c.`id`,c.`comment`,c.`sender_id`,u.`lastname`,u.`firstname`,c.`time` FROM `comments` as c JOIN user_personal_info as u on c.`sender_id` = u.`id` where c.post_id = " . $postRow['id'] . " order by c.time asc";
            $commentResult = mysql_query($commentSql);
            if (mysql_num_rows($commentResult) > 0) {
                while ($commentRow = mysql_fetch_array($commentResult)) {
                    $image = getUserPixSet($commentRow['sender_id']);
                    $postValue .= '<div id="comment" class=' . $commentRow['id'] . '><img class="profile_small" src="' . $image['image35x35'] . '"/><p class="name"><a href="page.php?view=profile&uid=' . $commentRow['sender_id'] . '">' . toSentenceCase($commentRow['firstname'] . ' ' . $commentRow['lastname']) . '</a></p><p class="status">' . make_links_clickable($commentRow['comment']) . '</p><p class="time" id="tpc' . $commentRow['id'] . '">' . agoServer($commentRow['time']) . '</p></div><script>setTimeout(timeUpdate,20000,\'' . $commentRow['time'] . '\',\'tpc' . $commentRow['id'] . '\')</script>';
                }
//                $postValue .= '</span><span id="box'.$postRow['id'].'"><div id="commentbox"><form method="GET" onsubmit="getValue(\'' . $postRow['id'] . '\',\'commentsPost\');return false"><img class="profile_small" src="' . $_SESSION['auth']['image35x35'] . '" /><input class="commenttext" type="text" id="c' . $postRow['id'] . '"/></form><div class="arrowdown"> </div></div></span></div>';
            }//else {
            $postValue .= '</span><span id="box' . $postRow['id'] . '"></span></div>';
            //}
        }
        echo $postValue;
    } else {
        if ($from) {
            $arr = getCommunityMembers($from);
            $friends = getUserFriends($userId, true);
            count($arr);
            foreach ($arr as $key => $value) {
                $option = "";
                if (array_key_exists($key, $friends)) {
                    $option = '<span>is a friends</span>';
                } else {
                    $option = '<span onclick="sendFriendRequest(\'' . $value['id'] . '\')" id="status_' . $value['id'] . '">Send Friend Request</span>';
                }
                $postValue.= '<div class="person" id="com_mem_' . $value['id'] . '">
                        <img src="' . $value['image']['image50x50'] . '" alt="' . toSentenceCase($value['fullname']) . '" />
                            <div class="details">
                            <span class="p_name"><a href="page.php?view=profile&uid=' . $value['id'] . '">' . toSentenceCase($value['fullname']) . '</a></span>
                                <span class="p_location">' . $value['location'] . '</span>
                                    <div class="post_activities">' . $option . '</div>
                                    </div>
                                    </div>';
            }
        }
        $postValue = "No post available at the moment";
    }
//echo ($postValue);
    ?>
</div>
<span class="tr_posts_loading"></span>
<div class='view-more'>
    <p class='notice' style='text-align: center;'><a onclick="showMoreFTLPost('.tr_posts_loading','#tr_post','TRD');" >Load more ...</a></p>
</div>
</div>