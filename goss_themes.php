<?php
include 'executecommand.php';
include 'encryptionClass.php';
$encrypt = new Encryption();
connect();
?>
<div class="posts">
    <div class="post">
        <p>Customize the way Gossout looks to you.</p>
    </div>
    <div class="post">
        <p class="status">Pick a design</p>
        <?php
        $sql = "SELECT * FROM theme";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                echo "<div class='album'>";
                $theme = $encrypt->safe_b64encode($row['id']);
                echo "<img src='$row[thumb]' height='100' onclick=\"enlargePostPix('$row[preview]','Shared with Zuma Broadcast');\" />";
                echo "<div class='albumname'>
                <p>&nbsp;</p>
                    <p onclick='useTheme(\"$theme\")'>Use Theme</p>
                </div>";
                echo "</div>";
            }
        }
        ?>
    </div>
<!--    <img src='' height=""/>-->
</div>

