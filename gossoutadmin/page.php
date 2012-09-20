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
            include 'dash.php';
            exit();
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }
} else {
    header("Location: ?view=home");
    exit();
}
?>
