<?php

session_start();
include("executecommand.php");
$conn_arr = connect();
if (isset($_GET['signout'])) {
    logout();
}
if (!isset($_SESSION['auth'])) {
    session_unset();
    header("Location:index.php");
} else if(!isset($_SESSION['auth']['admin'])){
    header("Location: http://www.gossout.com/");
    
}else{
    manageSession();
}
?>
