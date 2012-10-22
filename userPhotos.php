<?php
session_start();
include 'executecommand.php';
include 'encryptionClass.php';
connect();
$encrypt = new Encryption();
$str = $_SERVER['HTTP_REFERER'];
$id = custumGet($str, "uid=", $_SESSION['auth']['id'], true);
if (strpos($str, "param=")) {
    $paramval = custumGet($_SERVER['HTTP_REFERER'], "param=", "", false);
    $decodedParam = $encrypt->decode($paramval);
    if (strpos($decodedParam, "viewAlbum=") >= 0) {
        $albumid = custumGet($decodedParam, "viewAlbum=", "", false);
    }
}
?>
<div id="photos" >
    <?php
    if ($id == $_SESSION['auth']['id']) {
        if (!isset($albumid)) {
//            echo '<a id="delAlbum">Remove Album</a>';
        } else {
            echo '<a id="backButton" href="page.php?view=profile&photo=userPhoto&uid=' . $id . '"><img src="images/icon_left.png" />Go Back</a><br/><a id="upLoadButton" href="page.php?view=upload&photos=">Add Photos</a>';
        }
        ?>

        <script>
            $("#upLoadButton,#delAlbum,.backButton").button();    
            $("#upLoadButton,#delAlbum,.backButton").css('margin-bottom', '5px')
                                                    
        </script>
        <?php
    }
    ?>
    <div class="clear"></div>
    <?php
    if (isset($albumid)) {
        $sql = "SELECT pu.`id`, pu.`album_id`, a.`album`, pu.`user_id`, pu.`35x35`, pu.`50x50`, pu.`100x100`, pu.`original`, pu.`date_added`, pu.`comment` FROM `pictureuploads` as pu JOIN album as a on a.id=pu.`album_id` WHERE `user_id`=$id AND `album_id`=$albumid";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $album = "";
            while ($row = mysql_fetch_array($result)) {
                ?>
                <div class="album">
                    <img src="<?php echo $row['100x100'] ?>" title="<?php echo $row['comment'] ?>" onclick="enlargePostPix('<?php echo $row["original"] ?>','In <?php echo toSentenceCase($row["album"]) ?>');"/>
                    <div class="albumname">
                        <p onclick='makeProfilePix("<?php echo $row['id'] ?>")'>Make Profile Pix</p>
                        <p onclick="deleteFile(1, false)">Remove Picture</p>
                    </div>
                </div>
                <?php
//                echo '<li> <img id="img' . $row['id'] . '" src = "' . $row['img'] . '" alt="' . $_SESSION['auth']['fullname'] . '" title = "' . 'Album: ' . $row['album'] . '-' . $row['comment'] . '" onmouseover="positionMenu(\'' . $row['id'] . '\',\'hover' . $row['id'] . '\')" onmouseout="hideMenu(\'hover' . $row['id'] . '\')"/ >';
//                if ($id == $_SESSION['auth']['id']) {
//                    echo '<div id="hover' . $row['id'] . '" style="font-size:.9em;width:105px;text-align:center;position: absolute;display: block;right: 0;bottom: 0;background-color: whiteSmoke;" ></div>';
//                }
//                echo '</li>';
            }
        } else {
            echo "No Image Found!";
        }
    } else {
        $sql = "SELECT a.`id`, a.`album`, a.`album_cover`, a.`datecreated`,if(p.`100x100`<>NULL,p.`100x100`,'images/album-icon.png') as cover FROM `album` as a LEFT JOIN  pictureuploads as p ON a.album_cover=p.id WHERE a.`username`=$id";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                ?>
                <div class="album">
                    <a href="page.php?view=profile&photo=userPhoto&uid=<?php echo "$id&param=" . $encrypt->encode("viewAlbum=$row[id]"); ?>"><img src="<?php echo $row['cover'] ?>" title="<?php echo $row['album'] ?>" /></a>
                    <div class="albumname"><!--<input type="checkbox" name="" id="album"/>--><?php echo toSentenceCase(shortenStr($row['album'], 8)); ?></div>
                </div>
                <?php
            }
        }
    }
    ?>
    <div class="clear"></div>
    <!--    <ul class="box">
    <?php
//    connect();
//    $sql = "SELECT up.id,up.`album_id`,up.`100x100` as img,up.`comment`,a.album FROM `pictureuploads` as up JOIN album as a on a.id=up.`album_id` WHERE up.`user_id` =$id ORDER BY a.album";
//    $result = mysql_query($sql) or die(mysql_error());
//    if (mysql_num_rows($result) > 0) {
//        $album = "";
//        while ($row = mysql_fetch_array($result)) {
//            if ($album != $row['album']) {
//                echo "<div class='clear'></div><span style='width:50%'>" . $row['album'] . "<span><div class='clear'></div>";
//                $album = $row['album'];
//            }
//            echo '<li> <img id="img' . $row['id'] . '" src = "' . $row['img'] . '" alt="' . $_SESSION['auth']['fullname'] . '" title = "' . 'Album: ' . $row['album'] . '-' . $row['comment'] . '" onmouseover="positionMenu(\'' . $row['id'] . '\',\'hover' . $row['id'] . '\')" onmouseout="hideMenu(\'hover' . $row['id'] . '\')"/ onclick="enlargePostPix(\'' . $row['img'] . '\',\'In ' . $row['album'] . '\');">';
//            if ($id == $_SESSION['auth']['id']) {
//                echo '<div id="hover' . $row['id'] . '" style="font-size:.9em;width:105px;text-align:center;position: absolute;display: block;right: 0;bottom: 0;background-color: whiteSmoke;" ></div>';
//            }
//            echo '</li>';
//        }
//    } else {
//        echo "<li>No Image Found!</li>";
//    }
    ?>
    
        </ul>-->
</div>
<?php

function custumGet($ref, $needle, $currUser, $returnId) {
    $str = clean(strstr($ref, $needle));
    if (trim($str) != "") {
        $arr = explode("&", $str);
        $arr = explode("=", $arr[0]);

        if (trim($arr[1]) != "") {
            $id = trim($arr[1]);
        } else {
            if ($returnId) {
                $id = $currUser;
            } else {
                $id = "";
            }
        }
    } else {
        if ($returnId) {
            $id = $currUser;
        } else {
            $id = "";
        }
    }
    return $id;
}
?>