<?php
session_start();
include("executecommand.php");
$conn_arr = connect();
if (isset($_GET['signout'])) {
    logout();
}
if (!isset($_SESSION['auth'])) {
    $_SESSION['navigateTo'] = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    header("Location:index.php");
    exit;
} else {
//    manageSession();

    $sql = "SELECT uc.*,c.name,c.category FROM `user_comm` as uc JOIN community as c on uc.community_id=c.id WHERE uc.user_id=" . $_SESSION['auth']['id'];
    $result = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $comm = array();
        $comm['id'] = $row['community_id'];
        $comm['name'] = $row['name'];
        $comm['category'] = $row['category'];
        $_SESSION['auth']['community'] = $comm;
    } else {
        $comm = array();
        $comm['id'] = NULL;
        $comm['name'] = NULL;
        $comm['category'] = NULL;
        $_SESSION['auth']['community'] = $comm;
    }
}
?>
