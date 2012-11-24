<div class="right">
    <div id="messenger" class="ui-widget ui-corner-all left-boxes box_round_5 box_shadow ui-state-highlight"><span id="flashMsg"></span></div>
    <div class="right_container">
        <div>
            <span id="mycommunity">
                <?php
                $joinExcept = array("Football Gossip", "Celebrity", "Fashion & Style", "Controversy");
                $myCom = showMyComm($_SESSION['auth']['id']);
                if (count($myCom) > 0) {
                    echo '<div class="heading box_round_5_tr">My Communities</div>';
                }
                ?>
                <div class="dropMenu">
                    <?php
                    if (count($myCom) > 0) {
                        $c = 0;

                        foreach ($myCom['data'] as $value) {
                            if ($myCom['community_id'] == $value['id']) {
                                echo '<h3 class="community my_community">' . toSentenceCase(shortenStr($value['name'])) . '<span class="addbutton ui-state-highlight home" id="hom' . $value['id'] . '"><img src="images/icon_home.png" /></span><div class="clear"></div></h3><div style="padding:.2px;font-size:.7em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><span>This is your current community</span></div>';
                            } else {
                                if (in_array($value['name'], $joinExcept)) {
                                    echo '<h3 class="community my_community">' . toSentenceCase(shortenStr($value['name'])) . '<span class="home" id="hom' . $value['id'] . '"></span></h3><div style="padding:2px;font-size:.7em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><p><span onclick="unsubscribe(' . $value['id'] . ')">Unsubscribe</span> <!--| <span onclick="join(' . $value['id'] . ')">Join</span>--></p></div>';
                                } else {
                                    echo '<h3 class="community my_community">' . toSentenceCase(shortenStr($value['name'])) . '<span class="home" id="hom' . $value['id'] . '"></span></h3><div style="padding:2px;font-size:.7em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><p><span onclick="unsubscribe(' . $value['id'] . ')">Unsubscribe</span> | <span onclick="join(' . $value['id'] . ')">Join</span></p></div>';
                                }
                            }
                            $c++;
                            if ($c >= 5) {
                                break;
                            }
                        }
                    }
                    ?>
                </div>
            </span>
            <span id="suggestion">
                <?php
                $sugComm = getSugestedComm($_SESSION['auth']['id']);
                if (count($sugComm)) {
                    echo '<div class="heading">Suggested Communities</div><div class="dropMenu">';
                    shuffle($sugComm['data']);
                    $count = 0;
                    foreach ($sugComm['data'] as $value) {
                        $count++;
                        if (in_array($value['name'], $joinExcept)) {
                            echo '<h3 class="community">' . $value['name'] . '<span class="home" id="hom' . $value['id'] . '"></span></h3><div style="padding:2px;font-size:.7em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><p><span onclick="subscribe(' . $value['id'] . ')">Subscribe</span> <!--| <span onclick="join(' . $value['id'] . ')">Join</span>--></p></div>';
                        } else {
                            echo '<h3 class="community">' . $value['name'] . '<span class="home" id="hom' . $value['id'] . '"></span></h3><div style="padding:2px;font-size:.7em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><p><span onclick="subscribe(' . $value['id'] . ')">Subscribe</span> | <span onclick="join(' . $value['id'] . ')">Join</span></p></div>';
                        }

                        if ($count > 3) {
                            break;
                        }
                    }
                    echo '</div>';
                }
                ?>
            </span>

        </div>
        <?php include_once("people.php"); ?>
        <?php include_once("footer.php"); ?>
        <div class="clear"></div>
    </div>
</div>