<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php include_once("head.php"); ?>
    </head>
    <body>
        <div id="page">
            <?php include_once("nav.php") ?>
            <div class="inner-container">
                <?php include_once("left.php"); ?>


                <div class="content">
                    <?php
                    if (isset($_GET['com'])) {
                        if (trim(clean($_GET['com'])) != "") {
                            ?>
                            <div id="tabs">
                                <div id="timeline">
                                    <div class="posts">
                                        <?php
                                        $commId = clean($_GET['com']);
                                        $id = $_SESSION['auth']['id'];
                                        $arr = getCommunityInfo($commId);
                                        $arr_members = getCommunityMembers($commId);
                                        echo "<h3 class='heading'>" . $arr['name'] . " (" . $arr['subscriber'] . " subscribers)";
                                        if (!array_key_exists($id, $arr_members)) {
                                            echo " <span id='heading$commId'><span style='cursor:pointer;' onclick='join($commId);$(\"#heading$commId\").html(\"Request Sent!\")'>Join</span> | <span style='cursor:pointer;' onclick='subscribe($commId);$(\"#heading$commId\").html(\"Request Sent!\")'>Subscribe</span><span>";
                                        }
                                        echo"</h3>";
                                        echo showPostAndComment($_SESSION['auth']['id'], 0, clean($_GET['com']));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                        } else {
                            ?>
                            <div id="gossforum">
                                <table rules="all"  bordercolor="#CCC" >
                                    <tr>
                                        <th>Community</th>
                                        <th>Discussion</th>
                                        <th>Comments</th>
                                        <th>Last Post</th>
                                    </tr>
                                    <?php
                                    $arr = getAllCommunity();
                                    foreach ($arr as $value) {
                                        echo '<tr>
                                <td><p class="catg_name"><a href="page.php?view=community&com=' . $value['id'] . '">' . $value['name'] . ' (' . $value['subscriber'] . ' subscribers)</a></p>
                                    <!--<p class="catg_desc">' . $value['description'] . '</p>-->
                                </td>
                                <td align="right">' . $value['postCount'] . '</td>
                                <td align="right">' . $value['commentCount'] . '</td>
                                <td><a href="page.php?view=profile&uid=' . $value['lastSender_id'] . '">' . $value['lastSender'] . '</a></td>
                            </tr>';
                                    }
                                    ?>

                                </table>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div id="gossforum">
                            <table rules="all"  bordercolor="#CCC" >
                                <tr>
                                    <th>Community</th>
                                    <th>Discussion</th>
                                    <th>Comments</th>
                                    <th>Last Post</th>
                                </tr>
                                <?php
                                $arr = getAllCommunity();
                                foreach ($arr as $value) {
                                    echo '<tr>
                                <td><p class="catg_name"><a href="page.php?view=community&com=' . $value['id'] . '">' . $value['name'] . ' (' . $value['subscriber'] . ' subscribers)</a></p>
                                    <!--<p class="catg_desc">' . $value['description'] . '</p>-->
                                </td>
                                <td align="right">' . $value['postCount'] . '</td>
                                <td align="right">' . $value['commentCount'] . '</td>
                                <td><a href="page.php?view=profile&uid=' . $value['lastSender_id'] . '">' . toSentenceCase($value['lastSender']) . '</a></td>
                            </tr>';
                                }
                                ?>

                            </table>
                        </div>
                        <?php
                    }
                    ?>

                </div>


                <?php include_once("right.php"); ?>


            </div>
        </div>
    </div>
</div>
</div> 
</body>
</html>
