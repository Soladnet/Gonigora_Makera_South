<div class="left">
    <div class="left-container">
        <div class="left-boxes box_round_5 box_shadow ">
            <div class="heading box_round_5_tl-tr">
                <?php
                if (isset($_SESSION['auth'])) {
                    echo "<a href='page.php?view=profile&uid=" . $_SESSION['auth']['id'] . "'>" . $_SESSION['auth']["fullname"] . "</a>";
                } else {
                    echo "UNKNOWN";
                }
                ?>

            </div>
            <ul>
                <li class="gossbox-list bt menu1" id="gossbag" onclick="getUpdateFlicker('#tooltip_menu1_content', 'GossBag')">GossBag</li>
                <!-- flicker menu -->
                <div id="popGossbag">
                    <div id="triangle"></div>
                    <div id="tooltip_menu1">
                        <div class="heading box_round_5_tl-tr">
                            GossBag
                        </div>
                        <span id="tooltip_menu1_content"><div><img src='images/load.gif' id='messagesgloading' /></div></span>
                        <div class="menu_bottom">
                            <!--                            Show all Gossbag-->
                        </div>
                    </div>
                </div>
                <!-- end flicker menu -->
                <li class="gossbox-list bt menu2" id="messages" onclick="getUpdateFlicker('#tooltip_menu2_content', 'Messages')">Messages</li>
                <!-- flicker menu -->
                <div id="popMessage">
                    <div id="triangle"></div>
                    <div id="tooltip_menu2">
                        <div class="heading box_round_5_tl-tr">
                            Messages
                        </div>
                        <span id="tooltip_menu2_content"><div><img src='images/load.gif' id='messagesgloading' /></div></span>
                        <a href="page.php?view=messages">
                            <div class="menu_bottom">
                                See all messages
                            </div></a>
                    </div>
                </div>
                <!-- end flicker menu -->
                <li class="gossbox-list bt menu3" id="friend_requests" onclick="getUpdateFlicker('#tooltip_menu3_content', 'Friend Requests')">Friend Requests</li>
                <!-- flicker menu -->
                <div id="popFriend_Request">
                    <div id="triangle"></div>
                    <div id="tooltip_menu3">
                        <div class="heading box_round_5_tl-tr">
                            Friend Request
                        </div>
                        <span id="tooltip_menu3_content"><img src='images/load.gif' id='messagesgloading' /></span>
                        <div class="menu_bottom">
                            <a href="page.php?view=request">See all Friend Requests</a>
                        </div>
                    </div>
                </div>
                <!-- end flicker menu -->
                <li class="gossbox-list bt menu4" id="settings">Settings</li>
                <div id="popSettings">
                    <div id="triangle"></div>
                    <div id="tooltip_menu4">
                        <div class="heading box_round_5_tl-tr" style="padding: 3px;">
                            User Option
                        </div>
                        <div class="profile"><a href="page.php?view=profile" ><span>My Profile</span></a></div>
<!--                        <div class="profile"><a href="page.php?view=community" ><span>Join Community</span></a></div>-->
                        <div class="logout"><a href="page.php?view=home&signout=">Logout</a></div>
                        <div class="menu_bottom">

                        </div>
                    </div>
                </div>
            </ul>
            <script>makeMeClickable(".logout");makeMeClickable(".profile");</script>
        </div>
    </div>
    <span id="community_chat">
        <?php
        if (isset($_SESSION['auth']['community']['id'])) {
//           include("community-chat.php");
        }
        ?>
    </span>
</div>
