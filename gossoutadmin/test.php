<?php
session_start();
include 'executecommand.php';
connect();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<html>
    <head>

    </head>
    <body>
        <form method="POST" id="statusUpdate" action="do_ajaxfileupload_post.php" enctype="multipart/form-data">
            <article class="module width_full">
                <header><h3>Share with Gossout Community</h3></header>
                <div class="module_content">
                    <fieldset>
                        <label>Communities</label>
                        <select name="com" id="community_selected">
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


            </article></form>
    </body>
</html>