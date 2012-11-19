<?php

session_start();
$accept_file_types = '/^image\/(gif|jpeg|png)$/';
$maxFileSize = 2000000;
$arr = array();
if ($_FILES['fileToUpload']['type'] == "image/jpg") {
    $ext = ".jpg";
    $arr['status'] = "success";
} else if ($_FILES['fileToUpload']['type'] == "image/jpeg") {
    $ext = ".jpeg";
    $arr['status'] = "success";
} else if ($_FILES['fileToUpload']['type'] == "image/png") {
    $ext = ".png";
    $arr['status'] = "success";
} else if ($_FILES['fileToUpload']['type'] == "image/gif") {
    $ext = ".gif";
    $arr['status'] = "success";
} else {
    $arr['status'] = "failed";
    $arr['message'] = "File type not supported";
}
if ($_FILES['fileToUpload']['size'] > $maxFileSize) {
    $arr['status'] = "failed";
    $arr['message'] = "File must not exceed 2MB";
}
if ($arr['status'] == "success" && isset($_SESSION['auth']['id'])) {
    $user = $_SESSION['auth']['id'];
    $newfilename = time() . "_" . $user . "_" . $ext;
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "upload/community_pix/$newfilename")) {
        $image_versions = array(
            'image250x250' => array(
                'upload_dir' => 'upload/community_pix/',
                'upload_url' => 'upload/community_pix/',
                'max_width' => 250,
                'max_height' => 250
            ),
            'image1000x100' => array(
                'upload_dir' => 'upload/community_pix/',
                'upload_url' => 'upload/community_pix/',
                'max_width' => 100,
                'max_height' => 100
            )
            ,
            'image50x50' => array(
                'upload_dir' => 'upload/community_pix/',
                'upload_url' => 'upload/community_pix/',
                'max_width' => 50,
                'max_height' => 50
            )
        );
        $myImgVerUrl = array();
        foreach ($image_versions as $version => $options) {
            $imgext = strtolower(substr(strrchr(rawurlencode($newfilename), '.'), 1));
            $imgName = substr(rawurlencode($newfilename), 0, strrpos(rawurlencode($newfilename), '.')) . '_' . $options['max_width'] . 'x' . $options['max_width'] . '.' . $imgext;
            if (create_scaled_image($newfilename, $options)) {
                $myImgVerUrl[] = $imgName;
            }
        }
        include 'executecommand.php';
        connect();
        if (isset($_POST['posts'])) {
            $text = $_POST['posts'];
        } else {
            $text = "";
        }
        $userId = $_SESSION['auth']['id'];
        $community = $_SESSION['auth']['community']['id'];
        $comm = $_SESSION['auth']['community']['name'];
        $senderFullname = $_SESSION['auth']['fullname'];
        $sql = "INSERT INTO `post`(`post`, `community_id`, `sender_id`) VALUES ('" . clean(htmlspecialchars($text)) . "','$community','$userId')";
        mysql_query($sql);
        if (mysql_affected_rows() > 0) {
            $id = mysql_insert_id();
            alertGossbag($userId, $id, $community, "$senderFullname post to " . $comm);
            $sql = "INSERT INTO community_pix(community_id,user_id,post_id,original,`250x250`,`100x100`,`50x50`) VALUES('$community','$userId','$id','upload/community_pix/$newfilename','upload/community_pix/" . $myImgVerUrl[0] . "','upload/community_pix/" . $myImgVerUrl[1] . "','upload/community_pix/" . $myImgVerUrl[2] . "')";
            mysql_query($sql);
            
            $arr['imgL'] = $_SESSION['auth']['image50x50'];
            $arr['imgS'] = $_SESSION['auth']['image35x35'];
            $arr['imgStatus'] = "upload/community_pix/$myImgVerUrl[0]";
            
            $sql = "SELECT u.id, c.`community_id`, concat(u.`firstname`,' ',u.lastname) as fullname,u.location,u.email,NOW() as rawTime FROM `community_subscribers` as c JOIN user_personal_info as u ON c.user=u.id WHERE c.`community_id`=$community";
            $result = mysql_query($sql);

            $email = trim(strip_tags("post+notification@gossout.com"));
            $full_name = 'Gossout';
            $from_mail = $full_name . '<' . $email . '>';
            $from_mail2 = $full_name . '<no-rely@gossout.com>';
            $message = stripcslashes($text);

            // set here
            $subject = "$senderFullname shared an image post in $comm";
            $headers = "Reply-To: $from_mail2 \r\n" . "From:" . $from_mail . "\r\n" . "X-Mailer: PHP/" . phpversion();
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_array($result)) {
                    $arr['rawTime'] = $row['rawTime'];

                    $to = $row['email'];
                    $html = '<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
    body{ font-family: "Segoe UI",sans-serif; background-color: #f9f9f9; color: #717171;}
    a { color: #62a70f; text-decoration: none; }
    a:hover { color: #000;}
    a:active , a:focus { color: green;}
    h2 { color: #252525; font-weight: normal; padding: 3px; margin: 0;}
    ol,ul { list-style: none; }
    p {margin: 3px;}
    hr { margin: .3em 0;    width: 100%;    height: 1px;    border-width:0;    color: #ddd;    background-color: #ddd;}
    img { border: none; padding: .2em; margin: .5em; max-width: 100%;}
    .container {max-width: 800px; margin: 0 auto; background-color: #fff; border: 1px solid #f2f2f2; padding: 10px}
    .header {background: url(http://gossout.com/images/logo_text_s.png) no-repeat right top!important;}  
    .header .time {font-size: .7em;}
    .content { background-color: #fff; padding: 1em;}
    .content p { font-size: .9em;}
    .content span { font-size: .8em;}
    .footer { background-color: #f9f9f9; padding: 10px; font-size: .8em;}

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <br>
            <h2>' . $row['fullname'] . ', </h2>
            <p><span class="user-name"><a href="http://gossout.com/page.php?view=profile&uid=' . $userId . '">' . $senderFullname . '</a></span> shared an image post in <span><a href="http://gossout.com/page.php?view=community&com=' . $row['community_id'] . '">' . $comm . '</a></span></p>
            <p class="time" align="right"> Time: ' . $arr['rawTime'] . '</p>
            <hr>      
        </div>
        <div class="content">
            <img src="http://gossout.com/' . $arr['imgL'] . '" align="left">
            <span class="user-name"><a href="http://gossout.com/page.php?view=profile&uid=' . $userId . '">' . $senderFullname . '</a></span>
            <p>' . $message . '</p>
            <span><a href="http://gossout.com/page.php?view=notification&open=' . $id . '">Comment on post</a></span>
        </div>
        <hr>
        <div class="footer">
            This email was intended for <span class="user-name"><a href="http://gossout.com/page.php?view=profile&uid=' . $row['id'] . '">' . $row['fullname'] . '</a></span> 
            (<span class="user-location">' . ($row['location'] ? $row['location'] : $to) . '</span>).
            <!--<br>If you believe 
            <span class="user-name"><a href="http://gossout.com/page.php?view=profile&uid=' . $userId . '">' . $senderFullname . '</a></span>
            is engaging in abusive behavior on
            <span><a href="http://gossout.com">Gossout</a></span>, you may <a href="">report 
            <span class="user-name"><a href="">Sample Name </a></span>
            for spam.</a> 
            <br>Forgot your 
            <span><a href="http://www.gossout.com">Gossout</a></span> password? 
            <a href="">Get instructions on how to reset it.</a>
            <br>You can also 
            <a href="">unsubscribe to these emails.</a>
            <br>If you received this message in error and did not sign up for <span><a href="http://www.gossout.com">Gossout</a></span>
            , click <a href="">not my account. </a>-->
            <br>
            <hr>
            <table cellspacing="5px">
                <tr>        
                    <td> <a href="http://gossout.com/page.php?view=about">About</a> </td>
                    <td> <a href="http://gossout.com/page.php?view=terms">Terms</a> </td>
                    <td> <a href="http://gossout.com/page.php?view=privacy">Privacy</a> </td>
                </tr>
                <tr >
                    <td colspan="3"> &copy; ' . date("Y") . '<a href="http://gossout.com">Gossout</a></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
';
                    if ($_SESSION['auth']['email'] != $to) {
                        @mail($to, $subject, $html, $headers);
                    }
                }
            }


            ///



            $arr['id'] = $id;
            $arr['sender_id'] = $userId;

            $arr['sql'] = $sql;
            $arr['name'] = $senderFullname;
            $arr['text'] = htmlspecialchars(stripcslashes($text));
            $arr['com_id'] = $community;
            $arr['com'] = $comm;
            $arr['time'] = "now";
            $arr['status'] = "success";
            $arr['message'] = "Post sent successfully!";
        } else {
            $arr['message'] = "Failt to send your post at this time";
            $arr['status'] = "failed";
        }
    } else {
        $arr['status'] = "failed";
        $arr['message'] = "You file could not be uploaded at this time...";
    }
}
echo json_encode($arr);

function create_scaled_image($file_name, $options) {
    $file_path = "upload/community_pix/" . $file_name;
//        $new_file_path = $options['upload_dir'].$file_name;
    list($img_width, $img_height) = @getimagesize($file_path);
    if (!$img_width || !$img_height) {
        return false;
    }
    $scale = min(
            $options['max_width'] / $img_width, $options['max_height'] / $img_height
    );
    $imgext = strtolower(substr(strrchr($file_name, '.'), 1));
    $imgName = substr($file_name, 0, strrpos($file_name, '.')) . '_' . $options['max_width'] . 'x' . $options['max_width'] . '.' . $imgext;

    $new_file_path = $options['upload_dir'] . $imgName;
    if ($scale >= 1) {
        if ($file_path !== $new_file_path) {
            return copy($file_path, $new_file_path);
        }
        return true;
    }
    $new_width = $img_width * $scale;
    $new_height = $img_height * $scale;
    $new_img = @imagecreatetruecolor($new_width, $new_height);
    switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
        case 'jpg':
        case 'jpeg':
            $src_img = @imagecreatefromjpeg($file_path);
            $write_image = 'imagejpeg';
            $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
            break;
        case 'gif':
            @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
            $src_img = @imagecreatefromgif($file_path);
            $write_image = 'imagegif';
            $image_quality = null;
            break;
        case 'png':
            @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
            @imagealphablending($new_img, false);
            @imagesavealpha($new_img, true);
            $src_img = @imagecreatefrompng($file_path);
            $write_image = 'imagepng';
            $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
            break;
        default:
            $src_img = null;
    }
    $success = $src_img && @imagecopyresampled(
                    $new_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height
            ) && $write_image($new_img, $new_file_path, $image_quality);
    // Free up memory (imagedestroy does not delete files):
    @imagedestroy($src_img);
    @imagedestroy($new_img);
    return $success;
}

?>