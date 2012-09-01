<?php
session_start();
include 'executecommand.php';
connect();
echo '<div class="posts">';
$sql = "SELECT c.post_id,p.community_id ,count(c.`post_id`)as commentCount FROM `comments` as c RIGHT JOIN post as p on p.id=c.post_id group by `post_id` order by commentCount desc";
$result = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($result) > 0) {
    $hottestCount = 0;
    while ($row = mysql_fetch_array($result)) {
        if ($row['post_id'] != NULL) {
            $sqlPost = "SELECT p.id,p.post,p.community_id,c.name,p.sender_id,p.time,s.`lastname`,s.`firstname` FROM `post` as p JOIN user_personal_info as s on p.sender_id=s.id JOIN community as c on c.id=p.community_id WHERE p.id=".$row['post_id'];
            $postResult = mysql_query($sqlPost);
            while ($postRow = mysql_fetch_array($postResult)) {
                $image = getUserPixSet($postRow['sender_id']);
                echo '<div class="post" id=' . $postRow['id'] . '>
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name"><a href="page.php?view=profile&uid='.$postRow['sender_id'].'">' . $postRow['lastname'] . ' ' . $postRow['firstname'] . '</a></p>
                <p class="status">' . $postRow['post'] . '</p>
                <p class="time" id="hot_tp' . $postRow['id'] . '">' . agoServer($postRow['time']) . '</p>
                <div class="post_activities"><span><a href="page.php?view=community&com='.$postRow['community_id'].'">in '.$postRow['name'].'</a></span></div>
                <span id="comments' . $postRow['id'] . '"><script>setTimeout(timeUpdate,20000,\'' . $postRow['time'] . '\',\'hot_tp' . $postRow['id'] . '\');</script>';
                $commentSql = "SELECT c.`id`,c.`comment`,c.`sender_id`,u.`lastname`,u.`firstname`,c.`time` FROM `comments` as c JOIN user_personal_info as u on c.`sender_id` = u.`id` where c.post_id = " . $postRow['id'] . " order by c.time asc";
                $commentResult = mysql_query($commentSql);
                if (mysql_num_rows($commentResult) > 0) {
                    while ($commentRow = mysql_fetch_array($commentResult)) {
                        $image = getUserPixSet($commentRow['sender_id']);
                        echo  '<div id="comment" class=' . $commentRow['id'] . '><img class="profile_small" src="' . $image['image35x35'] . '"/><p class="name"><a href="page.php?view=profile&uid='.$commentRow['sender_id'].'">' . $commentRow['lastname'] . ' ' . $commentRow['firstname'] . '</a></p><p class="status">' . $commentRow['comment'] . '</p><p class="time" id="hot_tpc' . $commentRow['id'] . '">' . agoServer($commentRow['time']) . '</p></div><script>setTimeout(timeUpdate,20000,\'' . $commentRow['time'] . '\',\'hot_tpc' . $commentRow['id'] . '\')</script>';
                    }
                }
                echo '</span><span id="box'.$postRow['id'].'"></span></div>';
            }
            $hottestCount++;
        }
    }
    if($hottestCount==0){
        echo 'Hottest gossips are coming soon...';
    }
} else {
    echo 'Hottest gossips are coming soon...';
}
echo '</div>';
?>