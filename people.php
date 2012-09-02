<div class=" ">
            <div class="">

                <?php
                $myId = $_SESSION['auth']['id'];
                $my_friends_arr = getUserFriends($myId); //including those i have not accepted their request send param shoule be false
                $people = array();
                $sql = "SELECT * FROM community_subscribers WHERE `user`=$myId";
                $result = mysql_query($sql);
                if (mysql_num_rows($result) > 0) {
                    while ($row = mysql_fetch_array($result)) {
                        $commMember = getCommunityMembers($row['community_id']);
                        foreach ($commMember as $key => $value) {
                            if (!array_key_exists($key, $my_friends_arr) && $key != $myId) {
                                $people[$key] = $value;
                            }
                        }
                    }
                }
                shuffle($people);
                if (count($people) > 0) {
                    echo '<div class="heading">
                People
            </div>';
                    $count = 0;
                    foreach ($people as $key => $value) {
                        echo '<div class="person" id="p_' . $value['id'] . '">
                        <img src="' . $value['image']['image50x50'] . '" alt="' . $value['fullname'] . '" />
                            <div class="details">
                            <span class="p_name"><a href="page.php?view=profile&uid=' . $value['id'] . '">' .$value['fullname'] . '</a></span>
                                <span class="p_location">' . shortenStr($value['location']) . '</span>
                                    <div class="post_activities"><span onclick="sendFriendRequest(\'' . $value['id'] . '\')" id="status_' . $value['id'] . '">Send Friend Request</span></div>
                                    </div>
                                    <span class="people_loading'.$value['id'].'"></span>
                                    </div>';
                        $count++;
                        if($count>2){
                            break;
                        }
                    }
                  /*  echo '<div class="p_search"> 
                    <form >
                        <table>
                            <tr>
                                <td><input class="searchbox" type="text" placeholder="Search People" /></td>
        <!--                        <td><input class="searchbutton" type="submit" value="Search"/></td>-->
                            </tr>
                        </table>
                    </form>
                </div>
            
            <div class="clear"></div>'; */
                }
                ?>
            </div>
        </div>
        
