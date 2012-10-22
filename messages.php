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
                                echo '<li class="lefttab"><a href="#conversation" >Conversation</a></li><li class="righttab"><a href="#inbox" >Inbox</a></li><li><a href="#outbox">Outbox</a></li>';
                            } else {
                                echo '<li class="lefttab"><a href="#inbox">Inbox</a></li><li><a href="#outbox">Outbox</a></li>';
                            }
                            ?>
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
                        <div id="inbox">

                            <?php
                            $sql = "SELECT m.`id`,  m.`receiver_id`,m.`sender_id`,concat(l.firstname,' ',l.lastname) as fullname, m.`message`, m.`time`, m.`status` FROM `privatemessae` as m JOIN user_personal_info as l ON m.`sender_id`=l.id WHERE m.receiver_id=" . $_SESSION['auth']['id'] . " order by m.time desc";
                            $result = mysql_query($sql);
                            if (mysql_num_rows($result) > 0) {
                                echo '<div class="posts">';
                                $pivot = array();
                                while ($row = mysql_fetch_array($result)) {
                                    if (!in_array($row['sender_id'], $pivot)) {
                                        $msg = getUserPixSet($row['sender_id']);
                                        if ($row['status'] == "D" || $row['status'] == "N") {
                                            echo "<div class='post shade' id='in_msg" . $row['id'] . "'><img class='profile_small' src='" . $msg['image50x50'] . "'/><p class='name'><a href='page.php?view=messages&open=" . $row['sender_id'] . "'>" . $row['fullname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time' id='in_msg_tim" . $row['id'] . "'>" . agoServer($row['time']) . "</p></div><script>setTimeout(timeUpdate,2000,'" . $row['time'] . "','in_msg_tim" . $row['time'] . "');</script>";
                                        } else {
                                            echo "<div class='post' id='in_msg" . $row['id'] . "'><img class='profile_small' src='" . $msg['image50x50'] . "'/><p class='name'><a href='page.php?view=messages&open=" . $row['sender_id'] . "'>" . $row['fullname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time' id='in_msg_tim" . $row['id'] . "'>" . agoServer($row['time']) . "</p></div><script>setTimeout(timeUpdate,2000,'" . $row['time'] . "','in_msg_tim" . $row['time'] . "');</script>";
                                        }
                                        $pivot[] = $row['sender_id'];
                                    }
                                }
                                echo "</div>";
                            } else {
                                echo "No outbox message";
                            }
                            ?>

                        </div>
                        <div id="outbox">

                            <?php
                            $sql = "SELECT m.`id`,  m.`receiver_id`,concat(l.firstname,' ',l.lastname) as fullname, m.`message`, m.`time`, m.`status` FROM `privatemessae` as m JOIN user_personal_info as l ON m.`receiver_id`=l.id WHERE m.sender_id=" . $_SESSION['auth']['id'];
                            $result = mysql_query($sql);
                            if (mysql_num_rows($result) > 0) {
                                $pivot = array();
                                echo '<div class="posts">';
                                while ($row = mysql_fetch_array($result)) {
                                    $msg = getUserPixSet($row['receiver_id']);
                                    echo "<div class='post' id='out_msg" . $row['id'] . "'><img class='profile_small' src='" . $msg['image50x50'] . "'/><p class='name'><a href='page.php?view=messages&open=" . $row['id'] . "'>" . $row['fullname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time' id='out_msg_tim" . $row['id'] . "'>" . agoServer($row['time']) . "</p></div><script>setTimeout(timeUpdate,2000,'" . $row['time'] . "','out_msg_tim" . $row['time'] . "');</script>";
                                }
                                echo "</div>";
                            } else {
                                echo "No outbox message";
                            }
                            ?>

                        </div>


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
