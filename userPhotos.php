<?php
session_start();
include 'executecommand.php';
$str = $_SERVER['HTTP_REFERER'];
$str = clean(strstr($str, "uid="));
if (trim($str) != "") {
    $arr = explode("&", $str);
    $arr = explode("=", $arr[0]);
    if (trim($arr[1]) != "") {
        $id = trim($arr[1]);
    } else {
        $id = $_SESSION['auth']['id'];
    }
} else {
    $id = $_SESSION['auth']['id'];
}
?>
<div id="photos" >
    <?php
    if($id==$_SESSION['auth']['id']){
    ?>
    <a id="upLoadButton" href="page.php?view=upload&photos=">Add Photos</a>
    <script>
        $("#upLoadButton").button();    
        $("#upLoadButton").css('margin-bottom', '5px')
        
    </script>
    <?php
    }
    ?>
    <ul class="box">
        <?php
        connect();
        $sql = "SELECT up.id,up.`album_id`,up.`100x100` as img,up.`comment`,a.album FROM `pictureuploads` as up JOIN album as a on a.id=up.`album_id` WHERE up.`user_id` =$id ORDER BY a.album";
        $result = mysql_query($sql) or die(mysql_error());
        if (mysql_num_rows($result) > 0) {
            $album = "";
            while($row = mysql_fetch_array($result)){
                if($album!=$row['album']){
                    echo "<div class='clear'></div><span style='width:50%'>".$row['album']."<span><div class='clear'></div>";
                    $album = $row['album'];
                }
                echo '<li> <img id="img'.$row['id'].'" src = "'.$row['img'].'" alt="'.$_SESSION['auth']['fullname'].'" title = "'.'Album: '.$row['album'].'-'.$row['comment'].'" onmouseover="positionMenu(\''.$row['id'].'\',\'hover'.$row['id'].'\')" onmouseout="hideMenu(\'hover'.$row['id'].'\')"/ onclick="enlargePostPix(\''.$row['img'].'\',\'In '.$row['album'].'\');">';
                if($id==$_SESSION['auth']['id']){
                    echo '<div id="hover'.$row['id'].'" style="font-size:.9em;width:105px;text-align:center;position: absolute;display: block;right: 0;bottom: 0;background-color: whiteSmoke;" ></div>';
                }
                echo '</li>';
            }
        } else {
            echo "<li>No Image Found!</li>";
        }
        ?>

    </ul>
</div>