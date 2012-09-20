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
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "../upload/community_pix/$newfilename")) {
        $image_versions = array(
            'image250x250' => array(
                'upload_dir' => '../upload/community_pix/',
                'upload_url' => '../upload/community_pix/',
                'max_width' => 250,
                'max_height' => 250
            ),
            'image1000x100' => array(
                'upload_dir' => '../upload/community_pix/',
                'upload_url' => '../upload/community_pix/',
                'max_width' => 100,
                'max_height' => 100
            )
            ,
            'image50x50' => array(
                'upload_dir' => '../upload/community_pix/',
                'upload_url' => '../upload/community_pix/',
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
        $community = $_POST['com'];
//        $comm = $_SESSION['auth']['community']['name'];
//        $senderFullname = $_SESSION['auth']['fullname'];
        $sql = "INSERT INTO `post`(`post`, `community_id`, `sender_id`) VALUES ('" . clean(htmlspecialchars($text)) . "','$community','$userId')";
        mysql_query($sql);
        if (mysql_affected_rows() > 0) {
            $id = mysql_insert_id();
//            alertGossbag($userId, $id, $community, "$senderFullname post to " . $comm);
            $sql = "INSERT INTO community_pix(community_id,user_id,post_id,original,`250x250`,`100x100`,`50x50`) VALUES('$community','$userId','$id','upload/community_pix/$newfilename','upload/community_pix/" . $myImgVerUrl[0] . "','upload/community_pix/" . $myImgVerUrl[1] . "','upload/community_pix/" . $myImgVerUrl[2] . "')";
            mysql_query($sql);
//            $arr['id'] = $id;
//            $arr['sender_id'] = $userId;
//            $arr['imgL'] = $_SESSION['auth']['image50x50'];
//            $arr['imgS'] = $_SESSION['auth']['image35x35'];
//            $arr['imgStatus'] = "upload/community_pix/$myImgVerUrl[0]";
//            $arr['sql'] = $sql;
//            $arr['name'] = $senderFullname;
//            $arr['text'] = htmlspecialchars($text);
//            $arr['com_id'] = $community;
//            $arr['com'] = $comm;
//            $arr['time'] = "now";
//            $row = mysql_fetch_array(mysql_query("SELECT NOW() as rawTime"));
//            $arr['rawTime'] = $row['rawTime'];
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
    $file_path = "../upload/community_pix/" . $file_name;
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