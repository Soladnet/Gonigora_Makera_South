<?php
session_start();
include 'executecommand.php';
connect();
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
$sql = "SELECT p.id,p.dateJoined,  p.firstname, p.lastname, p.gender,p.email,p.favquote, p.dob,p.relationship_status,p.phone,p.url,p.bio,p.favquote,p.location,p.likes,p.dislikes,p.works,uc.community_id,c.name,c.category FROM user_personal_info AS p LEFT JOIN user_comm as uc on p.id = uc.user_id LEFT JOIN community as c on c.id = uc.community_id WHERE p.id = $id";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
?>
<html>
    <head>
        <script>
            function log( message ) {
                $( "<div/>" ).text( message ).prependTo( "#log" );
                $( "#log" ).scrollTop( 0 );
            }

		
        </script>
    </head>
    <body>
        <div id="info" width="100%">
<!--            <input type="text" id="location" />
            <div id="log"></div>-->
            <span class="gossbox-list">
                <p class="heading">Personal Information</p>
                <?php
                if ($id == $_SESSION['auth']['id']) {
                    ?>
                    <ul>
                        <li class="ui-state-active ui-corner-all" title="edit personal info" style="float:right; text-decoration: none;">
                            <span class="ui-icon ui-icon-pencil" id="pinfo" onclick="editInfo('pinfo');"></span>
                        </li>
                    </ul>
                    <?php
                }
                echo "<p><span class='desc'>Fullname:</span> " . $row['firstname'] . ', ' . $row['lastname'] . "</p>";
                echo "<p><span class='desc'>Email:</span> " . $row['email'] . "</p>";
                echo "<p><span class='desc'>Gender:</span> " . $row['gender'] . "</p>";
                echo "<p><span class='desc'>Date of Birth:</span> " . dateToString($row['dob']) . "</p>";
                echo "<span class='personalInfo'>";
                echo "<p><span class='desc'>Relatioship status:</span> <span id='relationship'>" . $row['relationship_status'] . "</span></p>";
                echo "<p><span class='desc'>Phone:</span> <span id='phone'>" . $row['phone'] . "</span></p>";
                echo "<p><span class='desc'>url:</span> <span id='url'>" . $row['url'] . "</span></p>";
                echo "</span>";
                ?>
            </span>
            <span class="gossbox-list">
                <p class="heading">Likes</p>
                <?php
                if ($id == $_SESSION['auth']['id']) {
                    ?>
                    <ul>
                        <li class="ui-state-active ui-corner-all" title="edit likes" style="float:right; text-decoration: none;" >
                            <span class="ui-icon ui-icon-pencil" id="plike" onclick="editInfo('plike');"></span>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                <p>&nbsp;</p><span id="editlikes"><?php echo $row['likes'] ?></span>
            </span>
            <span class="gossbox-list">
                <p class="heading">Dislikes</p>
                <?php
                if ($id == $_SESSION['auth']['id']) {
                    ?>
                    <ul>
                        <li class="ui-state-active ui-corner-all" title="edit dislikes" style="float:right; text-decoration: none;">
                            <span class="ui-icon ui-icon-pencil" id="pdislikes" onclick="editInfo('pdislikes');"></span>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                <p>&nbsp;</p><span id="editdislikes"><?php echo $row['dislikes'] ?></span>
            </span>
            <span class="gossbox-list">
                <p class="heading">City</p>
                <?php
                if ($id == $_SESSION['auth']['id']) {
                    ?>
                    <ul>
                        <li class="ui-state-active ui-corner-all" title="edit current city" style="float:right; text-decoration: none;">
                            <span class="ui-icon ui-icon-pencil" id="plocate" onclick="editInfo('plocate');"></span>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                <p>&nbsp;</p><span id="editlocation" ><?php echo $row['location']; ?></span>
            </span>
            <span class="gossbox-list">
                <p class="heading">Bio</p>
                <?php
                if ($id == $_SESSION['auth']['id']) {
                    ?>
                    <ul>
                        <li class="ui-state-active ui-corner-all" title="edit your bio" style="float:right; text-decoration: none;">
                            <span class="ui-icon ui-icon-pencil" id="pbio" onclick="editInfo('pbio');"></span>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                <p>&nbsp;</p><span id="editbio"><?php echo $row['bio'] ?></span>
            </span>
            <span class="gossbox-list">
                <p class="heading">Favourite Quote</p>
                <?php
                if ($id == $_SESSION['auth']['id']) {
                    ?>
                    <ul>
                        <li class="ui-state-active ui-corner-all" title="edit your favorite qoute" style="float:right; text-decoration: none;">
                            <span class="ui-icon ui-icon-pencil" id="pqoute" onclick="editInfo('pqoute');"></span>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                <p>&nbsp;</p><span id="editQuote"><?php echo $row['favquote'] ?></span>
            </span>
            <hr/>
   <!--         <span class="gossbox-list">
                <p class="heading">Place of Work</p>
                <?php
                if ($id == $_SESSION['auth']['id']) {
                    ?>
                    <ul>
                        <li class="ui-state-active ui-corner-all" title="edit place or work" style="float:right; text-decoration: none;">
                            <span class="ui-icon ui-icon-pencil" id="pwork" onclick="editInfo('pwork');"></span>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                <p>&nbsp;</p><span id="editwork"><?php echo $row['works'] ?></span>
            </span>
            <span class="gossbox-list">
                <p class="heading">Entertainment</p>
                <?php
                if ($id == $_SESSION['auth']['id']) {
                    ?>
                    <ul>
                        <li class="ui-state-default ui-corner-all" title="edit entertainment" style="float:right; text-decoration: none;"><span class="ui-icon ui-icon-pencil" onclick="editInfo('pentertainment');"></span></li>
                    </ul>
                    <?php
                }
                ?>
                <p>Music</p>
                <hr/>
                <span id="music"></span>
                <p>Video</p>
                <hr/>
                <span id="vid"></span>
                <p>Shows</p>
                <hr/>
                <span id="shows"></span>
            </span>-->
        </div>
    </body>
</html>