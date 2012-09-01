<?php

if (isset($_GET['view'])) {
    if (strtolower($_GET['view']) == "terms") {
        include  'terms.php';
    } else if (strtolower($_GET['view']) == "about") {
        include 'about.php';
    } else if (strtolower($_GET['view']) == "privacy") {
        include 'privacy.php';
    } else {
        include 'server_header_auth.php';
        if (strtolower($_GET['view']) == "home") {
            include 'home.php';
            exit();
        } else if (strtolower($_GET['view']) == "join") {
            include 'registration.php';
            exit();
        } else if (strtolower($_GET['view']) == "messages") {
            include 'messages.php';
            exit();
        } else if (strtolower($_GET['view']) == "profile") {
            include 'profile.php';
            exit();
        } else if (strtolower($_GET['view']) == "community") {
            include 'gossforums.php';
            exit();
        } else if (strtolower($_GET['view']) == "upload") {
            if (isset($_GET['photos'])) {
                include 'uploadPhoto.php';
            } else if (isset($_GET['video'])) {
                header("HTTP/1.0 404 Not Found");
                exit();
            } else {
                header("HTTP/1.0 404 Not Found");
                exit();
            }
        } else if (strtolower($_GET['view']) == "facebook") {
            $_SESSION['find'] = $_GET["view"];
            header("Location: oauth2callback/");
        } else if (strtolower($_GET['view']) == "notification") {
            include("individualrecord.php");
        } else if (strtolower($_GET['view']) == "tweakwink") {
            include("individualrecord.php");
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }
} else {
    header("Location: ?view=home");
    exit();
}
?>
