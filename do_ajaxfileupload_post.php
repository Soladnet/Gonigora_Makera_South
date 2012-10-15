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
            /////

            $sql = "SELECT c.`community_id`, concat(u.`firstname`,' ',u.lastname) as fullname,u.email FROM `community_subscribers` as c JOIN user_personal_info as u ON c.user=u.id WHERE c.`community_id`=$community";
            $result = mysql_query($sql);
            $email = trim(strip_tags("post+notification@gossout.com"));
            $full_name = 'Gossout';
            $from_mail = $full_name . '<' . $email . '>';
            $from_mail2 = $full_name . '<no-rely@gossout.com>';
            $message = stripcslashes($text);

            // set here
            $subject = "$senderFullname post to $comm";
            $headers = "Reply-To: $from_mail2 \r\n" . "From:" . $from_mail . "\r\n" . "X-Mailer: PHP/" . phpversion();
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_array($result)) {
                    $to = $row['email'];
                    $html = '<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <!--        <link rel="stylesheet" media="screen and (min-device-width: 1024px)" href="css/main.css" />-->
        <style> a {text-decoration: none;} ol,ul {    list-style: none;} h1,h2,h3,h4,h5,h6 {font-weight: normal; color: #333;font-family: "Avant Garde", Avantgarde, "Century Gothic", CenturyGothic, "AppleGothic", sans-serif;} hr {margin: .3em 0;    width: 100%;    height: 1px;    border-width:0;    color: #ddd;background-color: #ddd;} span {} img {border: none;padding: .2em;    max-width: 100%;} .inner_wrappper {display: inline-block;padding: .5em;background: #fafafa;width: 100%;}
            .nav2_gradient {background-color: #f3f3f3; background-image: -webkit-gradient(linear,left top,left bottom,from(#f3f3f3),to(#dad9d7));background-image: -webkit-linear-gradient(top,#f3f3f3,#dad9d7);background-image: -moz-linear-gradient(top,#f3f3f3,#dad9d7);background-image: -ms-linear-gradient(top,#f3f3f3,#dad9d7);background-image: -o-linear-gradient(top,#f3f3f3,#dad9d7);background-image: linear-gradient(to bottom,#f3f3f3,#dad9d7);}#logo a img {padding: .5em;} .index_fnx{background-color:#FAFAFA;  border: 1px solid #F4F4F4; margin-top: 2px;}
            .friend_index{background: url(images/image-friend.png) no-repeat left top!important;}
            #column1 {display: inline-block;width: 49.5%;vertical-align: top;}
            #column1 {text-align: left;} .box_shadow8 {-webkit-box-shadow: 0 0 8px 0 #999;box-shadow: 0 0 8px 0 #999;}
            .center_div { margin: 0px auto 0;} .width800{        width: 80%;} .clear {clear: both;} #nav2 {border-bottom: 1px solid #717373;} .index_fnx .fnx{text-align:center;font-size: 1em;font-weight: bold;} .fnx_detail{font-size: .85em;}#footer{    padding: 5px 10px 5px 10px; margin: 0 auto; } #footer a{    color:#333; padding: 0 .2em;} #footer a:hover{    color:#A6CC8B;} #footer li {    float: left;}a{color:#fff} a:active,a:hover,a:visited{color: #ddd}</style>
    </head>
    <body> 
        <div>
            <div class="center_div width800">
                <div class="inner_wrappper box_shadow8 center_div ">
                    <div id="column1" style="width: 100%">
                        <div  class="community_index index_fnx" > 
                            <span> <h1 class="fnx"><img src="http://gossout.com/images/G.png" /><a href="page.php?view=community&com=' . $community . '"> ' . $comm . '</a><hr> </h1><a href="http://www.gossout.com/page.php?view=notification&open=' . $id . '"><span class="box_shadow8 center_div" style="display: block; width: 40%;text-align: center;background-color:#99c53d;font-size: .9em; ">Comment on Post</span></a>
                                <p class="fnx_detail"><img src="http://www.gossout.com/' . $arr['imgL'] . '" align="left"/><strong>' . $name . '</strong><br/>' . $message . '</p>
                                <p><img src="http://www.gossout.com/upload/community_pix/' . $myImgVerUrl[0].'" class="center_div" /></p>
                            </span>
                            <a href="http://www.gossout.com/page.php?view=notification&open=' . $id . '"><span class="box_shadow8 center_div" style="display: block; width: 40%;text-align: center;background-color:#99c53d;font-size: .9em; ">Comment on Post</span></a>
                        </div>

                    </div>
                    <div id="footer" class="p_name">
                        <hr>
                        <table cellspacing="5px">
                            <tr >
                                <td colspan="3" style="font-size: 13px" font-family: \'Segoe UI\',sans-serif;> <span style="font-size: .8em">For more information on gossout.com contact us on feedback@gossout.com</span>
                            </td>
                        </tr>
                    </table>
                    <div class="clear"></div>
                </div>
            </div>
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
            $arr['imgL'] = $_SESSION['auth']['image50x50'];
            $arr['imgS'] = $_SESSION['auth']['image35x35'];
            $arr['imgStatus'] = "upload/community_pix/$myImgVerUrl[0]";
            $arr['sql'] = $sql;
            $arr['name'] = $senderFullname;
            $arr['text'] = htmlspecialchars(stripcslashes($text));
            $arr['com_id'] = $community;
            $arr['com'] = $comm;
            $arr['time'] = "now";
            $row = mysql_fetch_array(mysql_query("SELECT NOW() as rawTime"));
            $arr['rawTime'] = $row['rawTime'];
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