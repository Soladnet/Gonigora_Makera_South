<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8"/>
        <title>Soladnet Software</title>

        <link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
        <!--[if lt IE 9]>
        <link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="js/script.js" type="text/javascript"></script>
        <script src="../js/jquery-1.8.0.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="../js/home_ajaxfileupload.js"></script> 

        <script type="text/javascript" src="../js/jquery-ui-1.8.23.custom.min.js"></script>
        <link rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.23.custom.css" type="text/css" />

        <script src="js/hideshow.js" type="text/javascript"></script>
        <script src="js/jquery.tablesorter.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.equalHeight.js"></script>
        <script type="text/javascript">
            $(document).ready(function() 
            { 
                $(".tablesorter").tablesorter(); 
            } 
        );
            $(document).ready(function() {
                //When page loads...
                $( "#status" ).toggle(
                function() {
                    $( "#status" ).animate({  height: 60  }, 500 );
                    $("#status_community").css("display", "inline");
                },
                function() {
                    $( "#status" ).animate({ height:30 }, 500 );
                    $("#status_community").css("display", "none");
                }
            );
                
                $(".tab_content").hide(); //Hide all content
                $("ul.tabs li:first").addClass("active").show(); //Activate first tab
                $(".tab_content:first").show(); //Show first tab content

                //On Click Event
                $("ul.tabs li").click(function() {

                    $("ul.tabs li").removeClass("active"); //Remove any "active" class
                    $(this).addClass("active"); //Add "active" class to selected tab
                    $(".tab_content").hide(); //Hide all tab content

                    var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
                    $(activeTab).fadeIn(); //Fade in the active ID content
                    return false;
                });

            });
        </script>
        <script type="text/javascript">
            $(function(){
                $('.column').equalHeight();
            });
        </script>

    </head>


    <body>

        <header id="header">
            <hgroup>
                <h1 class="site_title"><a href="index.php">Gossout Admin</a></h1>
                <h2 class="section_title">Dashboard</h2><div class="btn_view_site"><a href="http://www.gossout.com">View Site</a></div>
            </hgroup>
        </header> <!-- end of header bar -->

        <section id="secondary_bar">
            <div class="user">
                <p>Welcome <?php
if (isset($_SESSION['auth'])) {
    echo "<a href='../page.php?view=profile&uid=" . $_SESSION['auth']['id'] . "'>" . $_SESSION['auth']["fullname"] . "</a>";
} else {
    echo "UNKNOWN";
}
?> <!--(<a href="#">3 Messages</a>)--></p>
                <a class="logout_user" href="page.php?view=home&signout=" title="Logout">Logout</a> 
            </div>
            <div class="breadcrumbs_container">
                <article class="breadcrumbs"><a href="index.php">Website Admin</a> <div class="breadcrumb_divider"></div> <a class="current">Dashboard</a></article>
            </div>
        </section><!-- end of secondary bar -->

        <aside id="sidebar" class="column">
            <form class="quick_search" onsubmit="return false">
                <input type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
            </form>
            <hr/>
            <h3>Menu</h3>
            <h3>Content</h3>
            <ul class="toggle">
                <li class="icn_settings"><a href="#">Option Not available this time</a></li>
                <!--                <li class="icn_new_article"><a href="#">New Article</a></li>
                                <li class="icn_edit_article"><a href="#">Edit Articles</a></li>
                                <li class="icn_categories"><a href="#">Categories</a></li>
                                <li class="icn_tags"><a href="#">Tags</a></li>-->
            </ul>
            <h3>Webmail</h3>
            <ul class="toggle">
                <li class="icn_folder"><a href="https://login.secureserver.net" target="_blank">Login to email</a></li>
                <!--                <li class="icn_new_article"><a href="#">New Article</a></li>
                                <li class="icn_edit_article"><a href="#">Edit Articles</a></li>
                                <li class="icn_categories"><a href="#">Categories</a></li>
                                <li class="icn_tags"><a href="#">Tags</a></li>-->
            </ul>
            <!--            <h3>Users</h3>
                        <ul class="toggle">
                            <li class="icn_add_user"><a href="#">Add New User</a></li>
                            <li class="icn_view_users"><a href="#">View Users</a></li>
                            <li class="icn_profile"><a href="#">Your Profile</a></li>
                        </ul>
                        <h3>Media</h3>
                        <ul class="toggle">
                            <li class="icn_folder"><a href="#">File Manager</a></li>
                            <li class="icn_photo"><a href="#">Gallery</a></li>
                            <li class="icn_audio"><a href="#">Audio</a></li>
                            <li class="icn_video"><a href="#">Video</a></li>
                        </ul>
                        <h3>Admin</h3>
                        <ul class="toggle">
                            <li class="icn_settings"><a href="#">Options</a></li>
                            <li class="icn_security"><a href="#">Security</a></li>
                            <li class="icn_jump_back"><a href="#">Logout</a></li>
                        </ul>-->

            <footer>
                <hr />
                <p><strong>Copyright &copy; <?php echo "2011 - " . date("Y"); ?> Soladnet Software</strong></p>
                <p>Professionals in Desktop, Mobile and Web Technologies</p>
            </footer>
        </aside><!-- end of sidebar -->

        <section id="main" class="column">

            <h4 id="act_alert_msg"></h4>
            <!--            <h4 class="alert_info">Welcome to the free MediaLoot admin panel template, this could be an informative message.</h4>
                        <h4 class="alert_warning">A Warning Alert</h4>
                        <h4 class="alert_error">An Error Message</h4>
                        <h4 class="alert_success">A Success Message</h4>-->
            <article class="module width_full">
                <header><h3>Site Statistics</h3></header>
                <div class="module_content">
                    <article class="stats_graph">
                        <img src="../images/logo_image_text.png" height="120" alt="" align="left" />
                    </article>

                    <article class="stats_overview">
                        <div class="overview_today">
                            <p class="overview_day">Today</p>
                            <p class="overview_count">N/A</p>
                            <p class="overview_type">Hits</p>
                            <p class="overview_count">N/A</p>
                            <p class="overview_type">Views</p>
                        </div>
                        <div class="overview_previous">
                            <p class="overview_day">Yesterday</p>
                            <p class="overview_count">N/A</p>
                            <p class="overview_type">Hits</p>
                            <p class="overview_count">N/A</p>
                            <p class="overview_type">Views</p>
                        </div>
                    </article>
                    <div class="clear"></div>
                </div>
            </article><!-- end of stats article -->

            <article class="module width_3_quarter">
                <header><h3 class="tabs_involved">User Activities</h3>
                    <ul class="tabs">
                        <li><a href="#tab1">New Users</a></li>
                        <li><a href="#tab2">Community Stat</a></li>
                    </ul>
                </header>

                <div class="tab_container">
                    <div id="tab1" class="tab_content">
                        <table class="tablesorter" cellspacing="0"> 
                            <thead> 
                                <tr> 
<!--                                    <th></th> -->
                                    <th>Full Name</th> 
                                    <th>Location</th> 
                                    <th>Community</th> 
                                    <th>Time</th> 
                                </tr> 
                            </thead>
                            <?php
                            $arr = getRecentUsers();
                            if (count($arr["data"]) > 0) {
                                ?>
                                <tbody> 
                                    <?php
                                    foreach ($arr["data"] as $x) {
                                        ?>
                                        <tr> 
        <!--                                            <td><input type="checkbox" name="post_<?php echo $x['id']; ?>"></td> -->
                                            <td><?php echo $x['fullname']; ?></td> 
                                            <td><?php echo $x['location']; ?></td> 
                                            <td><?php echo "<!--<a href='' target='blank'>-->" . $x['community'] . "<!--</a>-->"; ?></td> 
                                            <td><?php echo agoServer($x['joined']); ?></td> 
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <?php
                            }
                            ?>
                        </table>
                    </div><!-- end of #tab1 -->

                    <div id="tab2" class="tab_content">
                        <table class="tablesorter" cellspacing="0"> 
                            <thead> 
                                <tr> 
                                    <th>Community</th>
                                    <th>Subscribers</th>
                                    <th>Discussion</th>
                                    <th>Comments</th>
                                    <th>Last Post</th>
                                </tr> 
                            </thead> 
                            <tbody> 
                                <?php
                                $arr = getAllCommunity();
                                $i = 0;
                                foreach ($arr as $value) {
                                    $i++;
                                    echo '<tr>
                                    <td><p class="catg_name"><!--<a href="page.php?view=community&com=' . $value['id'] . '">-->' . $value['name'] . '<!--</a>--></p>
                                        <!--<p class="catg_desc">' . $value['description'] . '</p>-->
                                    </td>
                                    <td> ' . $value['subscriber'] . '</td>
                                    <td align="right">' . $value['postCount'] . '</td>
                                    <td align="right">' . $value['commentCount'] . '</td>
                                    <td><!--<a href="page.php?view=profile&uid=' . $value['lastSender_id'] . '">-->' . toSentenceCase($value['lastSender']) . '<!--</a>--></td>
                                </tr>';
                                    if ($i >= 10) {
                                        break;
                                    }
                                }
                                ?>
                            </tbody> 
                        </table>

                    </div><!-- end of #tab2 -->

                </div><!-- end of .tab_container -->

            </article><!-- end of content manager article -->

            <article class="module width_quarter">
                <header><h3>Admin Chat Box</h3></header>
                <div class="message_list">
                    <div class="module_content">
<!--                        <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                            <p><strong>John Doe</strong></p></div>
                        <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                            <p><strong>John Doe</strong></p></div>
                        <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                            <p><strong>John Doe</strong></p></div>
                        <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                            <p><strong>John Doe</strong></p></div>
                        <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                            <p><strong>John Doe</strong></p></div>-->
                    </div>
                </div>
                <footer>
                    <form class="post_message" onsubmit="return false">
                        <input type="text" value="Message" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
                        <input type="submit" class="btn_post_message" value=""/>
                    </form>
                </footer>
            </article><!-- end of messages article -->

            <div class="clear"></div>
            <form id="statusUpdate" onSubmit="return false" method="POST" enctype="multipart/form-data">
                <article class="module width_full">
                    <header><h3>Share with Gossout Community</h3></header>
                    <div class="module_content">
                        <fieldset>
                            <label>Communities</label>
                            <select name="community" id="community_selected">
                                <?php
                                if ($_SESSION['auth']['admin'] == "AS") {
                                    $arr = getAllCommunity();
                                    if (count($arr) > 0) {
                                        foreach ($arr as $value) {
                                            $selected = "";
                                            $val = $value['name'];
                                            if ($val == "Zuma Broadcast") {
                                                $selected = "selected=selected";
                                                $val = "Share with all communities";
                                            }
                                            echo "<option value='" . $value['id'] . "' $selected>" . $val . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </fieldset>
                        <fieldset>
                            <label>Content</label>

                            <textarea placeholder="What's happening right now!" class="update_textarea" id="status" ></textarea>
                            <span id="status_community"><?php
//                            if ($_SESSION['auth']['community']['name'] != "") {
//                                echo "Share with " . $_SESSION['auth']['community']['name'];
//                            } else {
//                                echo "<span class='req'>Join a community before you can share information</span>";
//                            }
                                ?></span><span id="share_loading"></span>
                            <br/>
                <!--                                <input type="button" value="Button"/>
                                                <input type="reset" value="Clear"/>-->

                        </fieldset>
                        <!--                    <fieldset style="width:48%; float:left; margin-right: 3%;">  to make two field float next to one another, adjust values accordingly 
                                                <label>Category</label>
                                                <select style="width:92%;">
                                                    <option>Articles</option>
                                                    <option>Tutorials</option>
                                                    <option>Freebies</option>
                                                </select>
                                            </fieldset>
                                            <fieldset style="width:48%; float:left;">  to make two field float next to one another, adjust values accordingly 
                                                <label>Tags</label>
                                                <input type="text" style="width:92%;">
                                            </fieldset><div class="clear"></div>-->
                    </div>
                    <footer>
                        <div class="submit_link">
    <!--                        <select>
                                <option>Draft</option>
                                <option>Published</option>
                            </select>-->
                            <input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input" >
                            <input type="submit" value="Share" onClick="getValue('#status','posts');"/>
    <!--                        <input type="submit" value="Publish" class="alt_btn">-->
                            <input type="reset" value="Reset">
                        </div>
                    </footer>


                </article></form><!-- end of post new article -->


            <!--            <article class="module width_full">
                            <header><h3>Basic Styles</h3></header>
                            <div class="module_content">
                                <h1>Header 1</h1>
                                <h2>Header 2</h2>
                                <h3>Header 3</h3>
                                <h4>Header 4</h4>
                                <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras mattis consectetur purus sit amet fermentum. Maecenas faucibus mollis interdum. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
            
                                <p>Donec id elit non mi porta <a href="#">link text</a> gravida at eget metus. Donec ullamcorper nulla non metus auctor fringilla. Cras mattis consectetur purus sit amet fermentum. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
            
                                <ul>
                                    <li>Donec ullamcorper nulla non metus auctor fringilla. </li>
                                    <li>Cras mattis consectetur purus sit amet fermentum.</li>
                                    <li>Donec ullamcorper nulla non metus auctor fringilla. </li>
                                    <li>Cras mattis consectetur purus sit amet fermentum.</li>
                                </ul>
                            </div>
                        </article> end of styles article -->
            <div class="spacer"></div>
        </section>


    </body>

</html>