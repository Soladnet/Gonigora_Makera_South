<?php

//function to show any messages
function messages() {
    $message = '';
    if ($_SESSION['success'] != '') {
        $message = '<span class="success" id="message">' . $_SESSION['success'] . '</span>';
        $_SESSION['success'] = '';
    }
    if ($_SESSION['error'] != '') {
        $message = '<span class="error" id="message">' . $_SESSION['error'] . '</span>';
        $_SESSION['error'] = '';
    }
    return $message;
}

function getAlbum($userId) {
    $sql = "SELECT * FROM `album` WHERE `username` = '$userId' ORDER BY album";
    $result = mysql_query($sql);
    $arr = array();
    if (mysql_num_rows($result) > 0) {
        $first = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($first == 0) {
                $sql = "SELECT id , 35x35 as img3535,50x50 as img5050,100x100 as img100100,date_added,comment FROM pictureuploads WHERE album_id=" . $row['id'];
                $pix = mysql_query($sql);
                if (mysql_num_rows($pix) > 0) {
                    $img = array();
                    while ($pixRow = mysql_fetch_array($pix)) {
                        $img[] = $pixRow;
                    }
                    $row['image'] = $img;
                }
                $first++;
            }
            $arr[] = $row;
        }
    }
    return $arr;
}

function registerUser($firstname, $lastname, $gender, $dob, $email, $password) {
    $success = "";
    $password = md5($password);
    $link = connect();
    $token = md5(strtolower($email . $lastname . $password));
    $sql1 = "INSERT INTO `user_personal_info`(`firstname`, `lastname`, `email`, `gender`, `dob`) VALUES ('" . toSentenceCase($firstname) . "','" . toSentenceCase($lastname) . "','$email','$gender','$dob')";
    mysql_query($sql1);
    if (mysql_affected_rows() > 0) {
        $id = mysql_insert_id();
        $sql2 = "INSERT INTO `user_login_details`(`id`,`email`, `password`,`token`) VALUES ('$id','$email','$password','$token')";
        mysql_query($sql2);
        if (mysql_affected_rows() > 0) {
//            $sql3 = "INSERT INTO `community_subscribers`(`user`, `community_id`) VALUES ($id,2)";
//            mysql_query($sql3);
            if (mysql_affected_rows() > 0) {
//                $sql3 = "INSERT INTO `post`(`post`, `community_id`, `sender_id`) VALUES ('$lastname $firstname joined Gossout!',2,1)";
//                mysql_query($sql3);
                $img5050 = "";
                $img3535 = "";
                $img100100 = "";
                if ($gender == "M") {
                    $img5050 = 'images/blankmal50x50.png';
                    $img3535 = 'images/blankmal35x35.png';
                    $img100100 = 'images/blankmal100x100.png';
                } else {
                    $img5050 = 'images/blankfem50x50.png';
                    $img3535 = 'images/blankfem35x35.png';
                    $img100100 = 'images/blankfem100x100.png';
                }
//                $sql = "INSERT INTO `user_profile_pix`(`username`, `50x50`, `35x35`, `100x100`) VALUES ('$id','$img5050','$img3535','$img100100')";
//                mysql_query($sql);
//                if (mysql_affected_rows() > 0) {
                $arr['email'] = $email;
                $arr['first_name'] = toSentenceCase($firstname);
                $arr['last_name'] = toSentenceCase($lastname);
                $arr['fullname'] = toSentenceCase($lastname . ' ' . $firstname);
                $arr['gender'] = $gender;
                $arr['dob'] = $dob;
                $arr['id'] = $id;
                $arr['image50x50'] = $img5050;
                $arr['image35x35'] = $img3535;
                $arr['image100x100'] = $img100100;
                $arr['relationship_status'] = '';
                $arr['phone'] = '';
                $arr['url'] = '';
                $arr['bio'] = '';
                $arr['location'] = '';
                $arr['quote'] = '';
                $arr['likes'] = '';
                $arr['dislikes'] = '';
                $arr['works'] = '';
                $_SESSION['auth'] = $arr;

                $success = "";
            } else {
                $success .= " " . mysql_error();
                $sql = "DELETE FROM `user_personal_info` WHERE `id` = '$id'";
                mysql_query($sql) or die(mysql_error());
            }
//            } else {
//                $success .= " " . mysql_error();
//                $sql = "DELETE FROM `user_personal_info` WHERE `id` = '$id'";
//                mysql_query($sql) or die(mysql_error());
//            }
        } else {
            $success .= " " . mysql_error();
            $sql = "DELETE FROM `user_personal_info` WHERE `id` = '$id'";
            mysql_query($sql) or die(mysql_error());
        }
    } else {
        $success .= " " . mysql_error();
    }

    return $success;
}

function manageSession() {
    if (isset($_SESSION['auth']['last_update'])) {
        if (isset($_SESSION['auth']['last_update']['maxTime'])) {
            if (time() - $_SESSION['auth']['last_update']['updateTime'] > $_SESSION['auth']['last_update']['maxTime']) {
                // last request was more than 1hr ago
                session_destroy();   // destroy session data in storage
                session_unset();     // unset $_SESSION variable for the runtime
            } else {
                session_regenerate_id(true);
                $_SESSION['auth']['last_update']['updateTime'] = time(); // update last activity time stamp
            }
        }
    }
}

// log user in function
function login($username, $password, $rem = false) {
    $user = clean($username);
    $pass = clean($password);

    //convert password to md5
    $pass = md5($pass);

    // check if the user id and password combination exist in database
    $sql = "SELECT l.id, l.email, l.activated,p.dateJoined,  p.firstname, p.lastname, p.gender, p.dob,p.relationship_status,p.phone,p.url,p.bio,p.favquote,p.location,p.likes,p.dislikes,p.works,uc.community_id,c.name,c.category FROM user_login_details AS l JOIN user_personal_info AS p ON p.email = l.email LEFT JOIN user_comm as uc on l.id = uc.user_id LEFT JOIN community as c on c.id = uc.community_id WHERE l.email = '$user' AND l.password = '$pass'";
    $result = mysql_query($sql);
    $arr = array();
    //if match is equal to 1 there is a match
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $arr['email'] = $row['email'];
        $arr['first_name'] = $row['firstname'];
        $arr['last_name'] = $row['lastname'];
        $arr['fullname'] = $row['lastname'] . ' ' . $row['firstname'];
        $arr['gender'] = $row['gender'];
        $arr['dob'] = $row['dob'];
        $arr['activated'] = $row['activated'];
        $arr['dateJoined'] = $row['dateJoined'];
        $arr['id'] = $row['id'];
        $arr['relationship_status'] = $row['relationship_status'];
        $arr['phone'] = $row['phone'];
        $arr['url'] = $row['url'];
        $arr['bio'] = $row['bio'];
        $arr['quote'] = $row['favquote'];
        $arr['location'] = $row['location'];
        $arr['likes'] = $row['likes'];
        $arr['dislikes'] = $row['dislikes'];
        $arr['works'] = $row['works'];

        if ($rem) {
            $time['updateTime'] = time();
            $time['maxTime'] = 31536000;
            $arr['last_update'] = $time;
        }
        $comm['id'] = $row['community_id'];
        $comm['name'] = $row['name'];
        $comm['category'] = $row['category'];
        $arr['community'] = $comm;

        $sql = "SELECT pp.`pix_id`,pu.`100x100`,pu.`50x50`,pu.`35x35` FROM `user_profile_pix` as pp JOIN pictureuploads as pu on pu.id = pp.`pix_id` WHERE pp.`username` = " . $arr['id'] . " order by date desc limit 1";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            $arr['image50x50'] = $row['50x50'];
            $arr['image35x35'] = $row['35x35'];
            $arr['image100x100'] = $row['100x100'];
        } else {
            $image3535 = "";
            $image5050 = "";
            $image100100 = "";
            if ($arr['gender'] == "M") {
                $image3535 = "images/blankmal35x35.png";
                $image5050 = "images/blankmal50x50.png";
                $image100100 = "images/blankmal100x100.png";
            } else {
                $image3535 = "images/blankfem35x35.png";
                $image5050 = "images/blankfem50x50.png";
                $image100100 = "images/blankfem100x100.png";
            }
            $arr['image50x50'] = $image5050;
            $arr['image35x35'] = $image3535;
            $arr['image100x100'] = $image100100;
        }

        $_SESSION['auth'] = $arr;
        header('Location: page.php?view=home');
        exit;
    } else {
        // login failed save error to a session
        $_SESSION['err']['status'] = 'Sorry, wrong username or password';
        header('Location: login.php');
        exit;
    }
    mysql_close($link);
}

function subscribeToCommunity($userId, $com, $comcat) {
    $sql = "SELECT `id` FROM `community` WHERE `name` = '" . clean(htmlspecialchars(toSentenceCase($com))) . "' and category = '" . clean(htmlspecialchars($comcat)) . "'";
    $result = mysql_query($sql);
    $arr = array();
    if (mysql_num_rows($result) > 0) {
        $rowCom = mysql_fetch_array($result);
        $sql = "UPDATE user_comm SET community_id = '" . $rowCom['id'] . "' WHERE `user_id` = '$userId'";
        mysql_query($sql);
        if (mysql_affected_rows() > 0) {
            $sql = "INSERT INTO community_subscribers (`user`,community_id) VALUES ('$userId','" . $rowCom['id'] . "')";
            @mysql_query($sql);
            $arr['status'] = "Your update was Successfull!";
            $_SESSION['auth']['community']['id'] = $rowCom['id'];
            $_SESSION['auth']['community']['name'] = toSentenceCase($com);
            $_SESSION['auth']['community']['category'] = $comcat;
        } else {
            $sql = "INSERT INTO user_comm(user_id,community_id) VALUES ('$userId','" . $rowCom['id'] . "')";
            @mysql_query($sql);
            if (mysql_affected_rows() > 0) {
                $sql = "INSERT INTO community_subscribers (`user`,community_id) VALUES ('$userId','" . $rowCom['id'] . "')";
                @mysql_query($sql);
                $arr['status'] = "Your update was Successfull!";
                $_SESSION['auth']['community']['id'] = $rowCom['id'];
                $_SESSION['auth']['community']['name'] = $com;
                $_SESSION['auth']['community']['category'] = $comcat;
            } else {
                $arr['status'] = "Oops Error 5011!!! Something does not feel right...please try again!";
            }
        }
    } else {
        $sql = "INSERT INTO `community`(`name`, `category`) VALUES ('" . clean(htmlspecialchars($com)) . "','" . clean(htmlspecialchars($comcat)) . "')";
        @mysql_query($sql);
        if (mysql_affected_rows() > 0) {
            $comId = mysql_insert_id();
            $sql = "UPDATE user_comm SET community_id = '$comId' WHERE `user_id` = '$userId'";
            mysql_query($sql);
            if (mysql_affected_rows() > 0) {
                $sql = "INSERT INTO community_subscribers (`user`,community_id) VALUES ('$userId','$comId')";
                @mysql_query($sql);
                $arr['status'] = "Your update was Successfull!";
                $_SESSION['auth']['community']['id'] = $comId;
                $_SESSION['auth']['community']['name'] = toSentenceCase($com);
                $_SESSION['auth']['community']['category'] = $comcat;
            } else {
                $sql = "INSERT INTO user_comm(user_id,community_id) VALUES ('$userId','$comId')";
                @mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                    $sql = "INSERT INTO community_subscribers (`user`,community_id) VALUES ('$userId','$comId')";
                    @mysql_query($sql);
                    $arr['status'] = "Your update was Successfull!";
                    $_SESSION['auth']['community']['id'] = $comId;
                    $_SESSION['auth']['community']['name'] = toSentenceCase($com);
                    $_SESSION['auth']['community']['category'] = $comcat;
                } else {
                    $arr['status'] = "Oops Error 5012!!! Something does not feel right...please try again!";
                }
            }
        } else {
            $arr['status'] = "Oops Error 5013!!! Something does not feel right...please try again!";
        }
    }
    return $arr;
}

function dateToString($date, $withYear = false) {
    $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $arr = explode('-', $date);
    if ($withYear) {
        $str = $arr[2] . " " . $month[$arr[1] - 1] . ", " . $arr[0];
    } else {
        $str = $arr[2] . " " . $month[$arr[1] - 1];
    }

    return $str;
}

function logout() {
    session_destroy();
    session_unset();
    header("Location: login.php");
    exit();
}

//Check-Function
function isValidEmail($email) {
    //Perform a basic syntax-Check
    //If this check fails, there's no need to continue
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    //extract host
    list($user, $host) = explode("@", $email);
    //check, if host is accessible
    if (!checkdnsrr($host, "MX") && !checkdnsrr($host, "A")) {
        return false;
    }

    return true;
}

function clean($value) {

    // If magic quotes not turned on add slashes.
    if (!get_magic_quotes_gpc()) {

        // Adds the slashes.
        $value = addslashes($value);
    }

    // Strip any tags from the value.
    $value = strip_tags($value);

    // Return the value out of the function.
    return $value;
}

function connect() {
    include 'config.php';
    $db = mysql_connect(HOSTNAME, USERNAME, PASSWORD) or die('I cannot connect to MySQL.');
    mysql_select_db(DATABASE_NAME, $db) or die('Database missing');
    return $db;
}

function getIpAddress() {
    $ip;
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

//Get an array with geoip-infodata
function geoCheckIP($ip) {
    //check, if the provided ip is valid
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        throw new InvalidArgumentException("IP is not valid");
    }

    //contact ip-server
    $response = @file_get_contents('http://www.netip.de/search?query=' . $ip);
    if (empty($response)) {
        throw new InvalidArgumentException("Error contacting Geo-IP-Server");
    }

    //Array containing all regex-patterns necessary to extract ip-geoinfo from page
    $patterns = array();
    $patterns["domain"] = '#Domain: (.*?)&nbsp;#i';
    $patterns["country"] = '#Country: (.*?)&nbsp;#i';
    $patterns["state"] = '#State/Region: (.*?)<br#i';
    $patterns["town"] = '#City: (.*?)<br#i';

    //Array where results will be stored
    $ipInfo = array();

    //check response from ipserver for above patterns
    foreach ($patterns as $key => $pattern) {
        //store the result in array
        $ipInfo[$key] = preg_match($pattern, $response, $value) && !empty($value[1]) ? $value[1] : 'not found';
    }

    return $ipInfo;
}

function agoServer($timestamp) {
    $time = "";
    $sql = "SELECT TIMESTAMPDIFF(SECOND,'$timestamp',NOW()) as sec,TIMESTAMPDIFF(MINUTE,'$timestamp',NOW()) as min,TIMESTAMPDIFF(HOUR,'$timestamp',NOW()) as hour,TIMESTAMPDIFF(DAY,'$timestamp',NOW()) as day,TIMESTAMPDIFF(MONTH,'$timestamp',NOW()) as month,TIMESTAMPDIFF(YEAR,'$timestamp',NOW()) as year";
    $re = mysql_query($sql);
    $row = mysql_fetch_array($re);
    if ($row['year'] > 0) {
        if ($row['year'] > 1) {
            $time = $row['year'] . " years ago";
        } else {
            $time = "Last Year";
        }
    } else if ($row['month'] > 0) {
        if ($row['month'] > 1) {
            $time = $row['month'] . " months ago";
        } else {
            $time = "Last month";
        }
    } else if ($row['day'] > 0) {
        if ($row['day'] > 1) {
            $time = $row['day'] . " days ago";
        } else {
            $time = "Yesterday";
        }
    } else if ($row['hour'] > 0) {
        if ($row['hour'] > 1) {
            $time = $row['hour'] . " hours ago";
        } else {
            $time = "an hour ago";
        }
    } else if ($row['min'] > 0) {
        if ($row['min'] > 1) {
            $time = $row['min'] . " minutes ago";
        } else {
            $time = "a minute ago";
        }
    } else if ($row['sec'] > 0) {
        if ($row['sec'] < 30) {
            $time = "few Seconds ago";
        } else if ($row['sec'] < 60) {
            $time = "about a minute ago";
        }
    } else {
        $time = "now";
    }
    return $time; //." | ".$row['sec']." ".$row['min']." ".$row['hour']." ".$row['day']." ".$row['month']." ".$row['yeah'];
}

function ago($time) {
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    $now = time();

    $difference = $now - $time;
    $tense = "ago";

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$j].= "s";
    }
    $txt = "";
    if ($difference == 0) {
        $txt = 'few seconds ago';
    } else {
        $txt = $difference . ' ' . $periods[$j] . ' ago';
    }
    return $txt;
}

function getUserPixSet($userId) {
    $arr = array();
    if ($userId == $_SESSION['auth']['id']) {
        $arr['image50x50'] = $_SESSION['auth']['image50x50'];
        $arr['image35x35'] = $_SESSION['auth']['image35x35'];
        $arr['image100x100'] = $_SESSION['auth']['image100x100'];
    } else {
        $sql = "SELECT pp.`pix_id`,pu.`100x100`,pu.`50x50`,pu.`35x35` FROM `user_profile_pix` as pp JOIN pictureuploads as pu on pu.id = pp.`pix_id` WHERE pp.`username` = " . $userId . " order by date desc limit 1";
        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            $arr['image35x35'] = $row['35x35'];
            $arr['image50x50'] = $row['50x50'];
            $arr['image100x100'] = $row['100x100'];
        } else {
            if ($_SESSION['auth']['gender'] == "M") {
                $arr['image35x35'] = 'images/blankmal35x35.png';
                $arr['image50x50'] = 'images/blankmal50x50.png';
                $arr['image100x100'] = 'images/blankmal100x100.png';
            } else {
                $arr['image35x35'] = 'images/blankfem35x35.png';
                $arr['image50x50'] = 'images/blankfem50x50.png';
                $arr['image100x100'] = 'images/blankfem100x100.png';
            }
        }
    }
    return $arr;
}

function showPostAndComment($userId, $all = 0, $from = 0, $withPost_id = 0, $lowlimit = 0) {
    $where = "";
    if ($all) {
        $where = "where p.sender_id = $userId";
    }
    if ($from) {
        if ($where) {
            $where .= " AND p.community_id=$from";
        } else {
            $where .= "where p.community_id=$from";
        }
    }
    if ($withPost_id) {
        if ($where) {
            $where .=" AND p.id=$withPost_id";
        } else {
            $where .="where p.id=$withPost_id";
        }
    }

    $limit = "Limit $lowlimit,5";

    $postValue = "";
    $postSql = "SELECT p.id,p.post,c.name,p.community_id,p.sender_id,p.time,s.`lastname`,s.`firstname`,cp.`250x250`,cp.`original` FROM `post` as p JOIN user_personal_info as s on p.sender_id=s.id JOIN community_subscribers as cs on (cs.user=$userId and cs.`community_id`=p.`community_id`) JOIN community as c on cs.`community_id`=c.id LEFT JOIN community_pix AS cp ON p.id = cp.post_id $where order by p.id desc $limit";
    $postResult = mysql_query($postSql); //or die(mysql_error());
    if (mysql_num_rows($postResult) > 0) {
        while ($postRow = mysql_fetch_array($postResult)) {
            $image = getUserPixSet($postRow['sender_id']);
            if ($postRow['250x250'] == NULL) {
                $postValue .= '<div class="post" id=' . $postRow['id'] . '>
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name"><a href="page.php?view=profile&uid=' . $postRow['sender_id'] . '">' . $postRow['lastname'] . ' ' . $postRow['firstname'] . '</a></p><p class="status">' . make_links_clickable($postRow['post']) . '</p><p class="time" id="tp' . $postRow['id'] . '">' . agoServer($postRow['time']) . '</p><div class="post_activities"> <span onclick="showGossoutModeldialog(\'dialog\',\'' . $postRow['id'] . '\');">Gossout</span> . <span onclick="showCommentBox(\'box' . $postRow['id'] . '\',\'' . $postRow['id'] . '\',\'' . $_SESSION['auth']['image35x35'] . '\')">Comment</span> . <span><a href="page.php?view=community&com=' . $postRow['community_id'] . '">in ' . $postRow['name'] . '</a></span></div><span id="comments' . $postRow['id'] . '"><script>setTimeout(timeUpdate,20000,\'' . $postRow['time'] . '\',\'tp' . $postRow['id'] . '\');</script>';
            } else {
                $postValue .= '<div class="post" id=' . $postRow['id'] . '>
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name"><a href="page.php?view=profile&uid=' . $postRow['sender_id'] . '">' . $postRow['lastname'] . ' ' . $postRow['firstname'] . '</a></p><p class="status">' . make_links_clickable($postRow['post']) . '</p><ul class="box"><li><img src="' . $postRow['250x250'] . '" alt="' . $postRow['name'] . '" onclick="enlargePostPix(\'' . $postRow['250x250'] . '\',\'Shared with ' . $postRow['name'] . '\');"/></li></ul><p class="time" id="tp' . $postRow['id'] . '">' . agoServer($postRow['time']) . '</p><div class="post_activities"> <span onclick="showGossoutModeldialog(\'dialog\',\'' . $postRow['id'] . '\');">Gossout</span> . <span onclick="showCommentBox(\'box' . $postRow['id'] . '\',\'' . $postRow['id'] . '\',\'' . $_SESSION['auth']['image35x35'] . '\')">Comment</span> . <span><a href="page.php?view=community&com=' . $postRow['community_id'] . '">in ' . $postRow['name'] . '</a></span></div><span id="comments' . $postRow['id'] . '"><script>setTimeout(timeUpdate,20000,\'' . $postRow['time'] . '\',\'tp' . $postRow['id'] . '\');</script>';
            }
            $commentSql = "SELECT c.`id`,c.`comment`,c.`sender_id`,u.`lastname`,u.`firstname`,c.`time` FROM `comments` as c JOIN user_personal_info as u on c.`sender_id` = u.`id` where c.post_id = " . $postRow['id'] . " order by c.time asc";
            $commentResult = mysql_query($commentSql);
            if (mysql_num_rows($commentResult) > 0) {
                while ($commentRow = mysql_fetch_array($commentResult)) {
                    $image = getUserPixSet($commentRow['sender_id']);
                    $postValue .= '<div id="comment" class=' . $commentRow['id'] . '><img class="profile_small" src="' . $image['image35x35'] . '"/><p class="name"><a href="page.php?view=profile&uid=' . $commentRow['sender_id'] . '">' . $commentRow['lastname'] . ' ' . $commentRow['firstname'] . '</a></p><p class="status">' . make_links_clickable($commentRow['comment']) . '</p><p class="time" id="tpc' . $commentRow['id'] . '">' . agoServer($commentRow['time']) . '</p></div><script>setTimeout(timeUpdate,20000,\'' . $commentRow['time'] . '\',\'tpc' . $commentRow['id'] . '\')</script>';
                }
//                $postValue .= '</span><span id="box'.$postRow['id'].'"><div id="commentbox"><form method="GET" onsubmit="getValue(\'' . $postRow['id'] . '\',\'commentsPost\');return false"><img class="profile_small" src="' . $_SESSION['auth']['image35x35'] . '" /><input class="commenttext" type="text" id="c' . $postRow['id'] . '"/></form><div class="arrowdown"> </div></div></span></div>';
            }//else{
            $postValue .= '</span><span id="box' . $postRow['id'] . '"></span></div>';
            //}
        }
    } else {
        if ($from) {
            $arr = getCommunityMembers($from);
            $friends = getUserFriends($userId, true);
            count($arr);
            foreach ($arr as $key => $value) {
                $option = "";
                if (array_key_exists($key, $friends)) {
                    $option = '<span>is a friends</span>';
                } else {
                    $option = '<span onclick="sendFriendRequest(\'' . $value['id'] . '\')" id="status_' . $value['id'] . '">Send Friend Request</span>';
                }
                $postValue.= '<div class="person" id="com_mem_' . $value['id'] . '">
                        <img src="' . $value['image']['image50x50'] . '" alt="' . $value['fullname'] . '" />
                            <div class="details">
                            <span class="p_name"><a href="page.php?view=profile&uid=' . $value['id'] . '">' . $value['fullname'] . '</a></span>
                                <span class="p_location">' . $value['location'] . '</span>
                                    <div class="post_activities">' . $option . '</div>
                                    </div>
                                    </div>';
            }
        }
        $postValue = "No post available at the moment";
    }
    return ($postValue);
}

function alertGossbag($sender_id, $post_id, $community_id, $caption) {
    $sql = "INSERT INTO gossbag(sender_id,post_id, community_id,caption) VALUES('$sender_id','" . $post_id . "','$community_id','" . shortenStr($caption, 90) . "')";
    @mysql_query($sql);
}

/**
 * 
 * Insert user's post in the post table and also alerts the gossbag table
 */
function sendPost($userId, $community, $comm, $text, $senderFullname) {
    $sql = "INSERT INTO `post`(`post`, `community_id`, `sender_id`) VALUES ('" . clean(htmlspecialchars($text)) . "','$community','$userId')";
    mysql_query($sql);
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $id = mysql_insert_id();
        alertGossbag($userId, $id, $community, "$senderFullname post to " . $comm);
        $arr['id'] = $id;
        $arr['sender_id'] = $userId;
        $arr['imgL'] = $_SESSION['auth']['image50x50'];
        $arr['imgS'] = $_SESSION['auth']['image35x35'];
        $arr['name'] = $senderFullname;
        $arr['text'] = make_links_clickable(htmlspecialchars($text));
        $arr['com_id'] = $community;
        $arr['com'] = $comm;
        $arr['time'] = "now";
        $row = mysql_fetch_array(mysql_query("SELECT NOW() as rawTime"));
        $arr['rawTime'] = $row['rawTime'];
        $arr['status'] = "success";
        $arr['message'] = "Post sent successfully!";
    } else {
        $arr['message'] = "Failt to send your post at this time";
        $arr['status'] = "failed";
    }
    return $arr;
}

function sendComment($userId, $postId, $comment, $senderFullname) {
    $sql = "INSERT INTO `comments` (`comment`, `post_id`, `sender_id`) VALUES ('" . clean(htmlspecialchars($comment)) . "', '$postId', '$userId')";
    mysql_query($sql);
    $sql = "SELECT p.community_id,c.name FROM post as p JOIN community as c on c.id=p.community_id WHERE p.id=$postId";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        alertGossbag($userId, $postId, $row['community_id'], $senderFullname . " commented on a post in " . $row['name']);
    }
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $arr['id'] = mysql_insert_id();
        $arr['sender_id'] = $userId;
        $arr['imgS'] = $_SESSION['auth']['image35x35'];
        $arr['name'] = $senderFullname;
        $arr['text'] = make_links_clickable(htmlspecialchars($comment));
        $arr['time'] = "now";
        $row = mysql_fetch_array(mysql_query("SELECT NOW() as rawTime"));
        $arr['rawTime'] = $row['rawTime'];
    }
    return $arr;
}

function sendPirvateMessage($userId, $reciver_id, $comment, $senderFullname) {
    $sql = "INSERT INTO `privatemessae` (`sender_id`, `receiver_id`, `message`) VALUES ('$userId', '$reciver_id', '" . clean(htmlspecialchars($comment)) . "')";
    mysql_query($sql);

    $arr = array();
    if (mysql_affected_rows() > 0) {
        $arr['id'] = mysql_insert_id();
        $arr['imgL'] = $_SESSION['auth']['image50x50'];
        $arr['name'] = $senderFullname;
        $arr['text'] = make_links_clickable(htmlspecialchars($comment));
        $arr['time'] = ago(time());
        $row = mysql_fetch_array(mysql_query("SELECT NOW() as rawTime"));
        $arr['rawTime'] = $row['rawTime'];
    }
    return $arr;
}

function getUpdateCount($userId) {
    $arr = array();
    $arr['msg'] = getPrivateMessageCount($userId);
    $arr['frq'] = getFRCount($userId);
    $arr['bag'] = getGossbagCount($userId);
    return $arr;
}

function getPrivateMessageCount($userId) {
    $sql = "SELECT count(*) as count FROM `privatemessae` WHERE `receiver_id`=$userId AND `status`='N'";
    $result = mysql_query($sql);
    $response = 0;
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $response = $row['count'];
    }
    return $response + 0; //converts to integer value
}

function getGossbag($userId, $postId = "0") {
    $sql = "SELECT gossbag FROM user_time_update WHERE user_id=" . $userId;
    $result = mysql_query($sql);
    $arr = array();
    $time = "";
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $time = $row['gossbag'];
    } else {
        $time = $_SESSION['auth']['dateJoined'];
    }
    $sql = "SELECT tw.id,tw.sender_id,tw.type,if(tw.type='T','Tweaked you','Winked you') as naration,tw.time,concat(p.lastname,' ',p.firstname) as sender_fullname FROM `tweakwink` as tw JOIN user_personal_info as p on tw.sender_id=p.id WHERE tw.`receiver_id`=$userId AND tw.status='N'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $temp = array();
            $image = getUserPixSet($row['sender_id']);
            $temp["id"] = $row['id'];
            $temp["sender_id"] = $row['sender_id'];
            $temp["caption"] = $row['naration'];
            $temp["time"] = $row['time'];
            $temp["sTime"] = agoServer($row['time']);
            $temp["fullname"] = $row['sender_fullname'];
            $temp["img"] = $image['image50x50'];
            $temp["infoType"] = "tw";
            $arr['data'][] = $temp;
            $sql = "UPDATE tweakwink SET `status`='D' WHERE receiver_id=$userId AND sender_id=" . $row['sender_id'];
            mysql_query($sql);
        }
    }
    if ($postId == 0) {
        $sql = "SELECT gb . *,c.name,CONCAT(p.lastname,' ',p.firstname) as fullname, cs.`datejoined` FROM  `gossbag` AS gb JOIN community_subscribers AS cs ON ( cs.`user` =$userId AND gb.`community_id` = cs.`community_id`) JOIN community as c on gb.`community_id`=c.id JOIN user_personal_info as p on gb.sender_id=p.id WHERE gb.`sender_id` <>$userId AND gb.time > '$time' order by gb.id desc";
    } else {
        $sql = "SELECT gb . *,c.name,CONCAT(p.lastname,' ',p.firstname) as fullname, cs.`datejoined` FROM  `gossbag` AS gb JOIN community_subscribers AS cs ON ( cs.`user` =$userId AND gb.`community_id` = cs.`community_id`) JOIN community as c on gb.`community_id`=c.id JOIN user_personal_info as p on gb.sender_id=p.id WHERE gb.`sender_id` <>$userId AND gb.post_id =$postId order by gb.id desc";
    }
    $result = mysql_query($sql);

    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $temp = array();
            $image = getUserPixSet($row['sender_id']);
            $temp["id"] = $row['id'];
            $temp["sender_id"] = $row['sender_id'];
            $temp["post_id"] = $row['post_id'];
            $temp["community_id"] = $row['community_id'];
            $temp["caption"] = $row['caption'];
            $temp["time"] = $row['time'];
            $temp["sTime"] = agoServer($row['time']);
            $temp["catName"] = $row['name'];
            $temp["fullname"] = $row['fullname'];
            $temp["img"] = $image['image50x50'];
            $temp["infoType"] = "gb";
            $arr['data'][] = $temp;
        }
        $sql = "UPDATE user_time_update SET gossbag=NOW() WHERE user_id=$userId";
        mysql_query($sql);
        if (mysql_affected_rows() == 0) {
            $sql = "INSERT INTO user_time_update(user_id) VALUES('$userId')";
            @mysql_query($sql);
        }
    }
    if (count($arr) > 0) {
        $arr['status'] = "success";
    } else {
        $arr['status'] = "failed";
    }
    return $arr;
}

function getGossbagCount($userId) {
    $sql = "SELECT gossbag FROM user_time_update WHERE user_id=" . $userId;
    $result = mysql_query($sql);
    $time = "";
    $gbag = 0;
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $time = $row['gossbag'];
    } else {
        $time = $_SESSION['auth']['dateJoined'];
    }
    $sql = "SELECT gb . *,c.name,p.lastname,p.firstname , cs.`datejoined` FROM  `gossbag` AS gb JOIN community_subscribers AS cs ON ( cs.`user` =$userId AND gb.`community_id` = cs.`community_id`) JOIN community as c on gb.`community_id`=c.id JOIN user_personal_info as p on gb.sender_id=p.id WHERE gb.`sender_id` <>$userId AND gb.time>'$time'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        $gbag = mysql_num_rows($result);
    }
    $sql = "SELECT * FROM `tweakwink` WHERE `receiver_id`=$userId AND status='N'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        $gbag += mysql_num_rows($result);
    }
    return $gbag + 0;
}

function getFRCount($userId) {
    $sql = "SELECT * from usercontacts WHERE username2=$userId AND status='N'";
    $result = mysql_query($sql);
    $response = 0;
    if (mysql_num_rows($result) > 0) {
        $response = mysql_num_rows($result);
    }
    return $response + 0;
}

function getFriendRequest($userId) {
    $sql = "SELECT uc.id,uc.sender_id,concat(p.lastname,' ',p.firstname)as fullname,p.location,uc.time from usercontacts as uc JOIN user_personal_info as p ON uc.username1=p.id WHERE username2=$userId AND status='N'";
    $result = mysql_query($sql);
    $arr = array();
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $image = getUserPixSet($row['sender_id']);
            $arr[] = array("rowId" => $row['id'], "id" => $row['sender_id'], "fullname" => $row['fullname'], "caption" => "Sent you a friend request", "img" => $image['image50x50'], "location" => $row['location'], "time" => agoServer($row['time']), "rawTime" => $row['time']);
        }
    }
    if (count($arr) > 0) {
        $arr['status'] = "success";
    } else {
        $arr['status'] = "failed";
    }
    return $arr;
}

function cancelFrq($userId, $frndId) {
    $sql = "UPDATE usercontacts SET status='C' WHERE username1=$userId AND username2=$frndId AND status='N'";
    mysql_query($sql);
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $arr['status'] = "success";
        $arr['message'] = "Friend Request Canceled!";
    } else {
        $arr['status'] = "failed";
        $arr['message'] = "Your request have been accepted!";
    }
    return $arr;
}

function sendFrq($userId, $frndId) {
    $sql = "SELECT * FROM usercontacts WHERE ((username1=$userId AND username2=$frndId) OR (username1=$frndId AND username2=$userId)) AND (status='N' OR status='Y')";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        $arr = array();
        $arr['status'] = "failed";
        $arr['message'] = "You can only send request once!";
        return $arr;
    }
    $sql = "INSERT INTO usercontacts(username1,username2,sender_id) VALUES('$userId','$frndId','$userId')";
    mysql_query($sql);
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $arr['status'] = "success";
        $arr['message'] = "Friend Request Sent!";
    } else {
        $arr['status'] = "failed";
        $arr['message'] = "Friend Request failed!";
    }
//    $arr['status'] = "failed";
//    $arr['message'] = "$sql";
    return $arr;
}

function checkFrqStatus($userId, $frndId) {
    $sql = "SELECT * FROM usercontacts WHERE ((username1=$userId AND username2=$frndId) OR (username1=$frndId AND username2=$userId)) AND status='N'";
    $result = mysql_query($sql);
    $arr = array();
    if (mysql_num_rows($result) > 0) {
        $arr['status'] = "pending";
        return $arr;
    }
    $arr['status'] = "";
}

function getConversationUpdate($contactId, $userId) {
    $sql = "SELECT p.id,p.receiver_id,p.sender_id,p.message,p.time,p.status,u.firstname,u.lastname FROM `privatemessae` as p JOIN user_personal_info as u ON u.id=p.sender_id  WHERE p.sender_id = $contactId and p.receiver_id=$userId and p.status = 'N' order by p.time";
    $result = mysql_query($sql);
    $arr = array();
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $newMsg = array();
            $image = getUserPixSet($row['sender_id']);
            $newMsg['id'] = $row['id'];
            $newMsg['img'] = $image['image50x50'];
            $newMsg['name'] = $row['lastname'] . " " . $row['firstname'];
            $newMsg['text'] = $row['message'];
            $newMsg['time'] = agoServer($row['time']);
            $newMsg['rawTime'] = $row['time'];
            $arr['data'][] = $newMsg;
//            $arr[] = "<div class='post' id='" . $row['id'] . "'><img class='profile_small' src='" . $row['senderImage'] . "'/><p class='name'><a href='#'>" . $row['lastname'] . " " . $row['firstname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time'>" . agoServer($row['time']) . "</p></div>";
        }
        $arr['status'] = "success";
//        $arr = array_reverse($arr);
//        foreach ($arr as $x) {
//            $response .= $x;
//        }
        $sql = "UPDATE `privatemessae` SET `status`= 'R' WHERE `sender_id`='$contactId' and `receiver_id` = '$userId'";
        mysql_query($sql);
    } else {
        $arr['status'] = "failed";
    }

    return $arr;
}

function showInbox($userInbox) {
    $sql = "UPDATE `privatemessae` SET `status`='D' WHERE `receiver_id` = '$userInbox' and `status` = 'N'";
    $sqlGet = "SELECT p.id, p.sender_id,u.firstname as senderFname,u.lastname as senderLname, p.receiver_id, r.firstname as receiverFname, r.lastname as receiverLname, p.message, p.time, p.status FROM  `privatemessae` AS p JOIN user_personal_info AS u ON u.id = p.sender_id JOIN user_personal_info as r on r.id = p.receiver_id WHERE p.receiver_id =$userInbox OR p.sender_id =$userInbox order by p.time";

    $resultGet = mysql_query($sqlGet);
    $genArr = array();
    $finalArra = array();
    if (mysql_num_rows($resultGet) > 0) {

        while ($row = mysql_fetch_array($resultGet)) {
            $eachMsgarr = array();
            $image = getUserPixSet($row['receiver_id']);
            if ($row['sender_id'] == $userInbox) {

                $eachMsgarr['img'] = $image['image50x50'];
                $eachMsgarr['msgid'] = $row['id'];
                $eachMsgarr['id'] = $row['receiver_id'];
                $eachMsgarr['name'] = $row['receiverLname'] . ' ' . $row['receiverFname'];
                $eachMsgarr['text'] = make_links_clickable($row['message']);
                $eachMsgarr['time'] = agoServer($row['time']);
                $eachMsgarr['rawTime'] = $row['time'];
                $eachMsgarr['status'] = 'R';
                $eachMsgarr['isUser'] = true;
                $genArr[$row['receiver_id']] = $eachMsgarr;

//                $response = "<div class='post' id='" . $row['id'] . "'><img class='profile_small' src='" . $row['receiverImage'] . "'/><img class='profile_small' src='images/reply.png'/><p class='name'><a href='page.php?view=messages&open=" . $row['receiver_id'] . "'>" . $row['receiverLname'] . ' ' . $row['receiverFname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time'>" . agoServer($row['time']) . "</p></div>";
//                $arr[$row['receiver_id']] = array($row['receiverLname'] . ' ' . $row['receiverFname'], $response);
            } else {
                $status = "";
                if (($row['status'] == "D" || $row['status'] == "N")) {
                    $eachMsgarr['status'] = 'N';
                    $status = " shade";
                } else {
                    $eachMsgarr['status'] = 'R';
                }

                $eachMsgarr['img'] = $image['image50x50'];
                $eachMsgarr['msgid'] = $row['id'];
                $eachMsgarr['id'] = $row['sender_id'];
                $eachMsgarr['name'] = $row['senderLname'] . ' ' . $row['senderFname'];
                $eachMsgarr['text'] = $row['message'];
                $eachMsgarr['time'] = agoServer($row['time']);
                $eachMsgarr['rawTime'] = $row['time'];
                $eachMsgarr['isUser'] = false;
                $genArr[$row['sender_id']] = $eachMsgarr;
                //continue heer
//                $response = "<div class='post$status' id='" . $row['id'] . "'><img class='profile_small' src='" . $row['senderImage'] . "'/><p class='name'><a href='page.php?view=messages&open=" . $row['sender_id'] . "'>" . $row['senderLname'] . ' ' . $row['senderFname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time'>" . agoServer($row['time']) . "</p></div>";
//                $arr[$row['sender_id']] = array($row['senderLname'] . ' ' . $row['senderFname'], $response);
            }
        }
        mysql_query($sql);
//        $finalArra = array();
//        foreach ($genArr as $x) {
//            $finalArra[] = $x;
//        }
//        $response = "";
//        $count = 0;
//        foreach ($arr as $x) {
//            if ($count == 0) {
//                $response .=$x[1];
//            } else {
//                $response .=$x[1];
//            }
//        }
    }
    if (count($genArr) > 0) {
        $genArr['status'] = "success";
    } else {
        $genArr['status'] = "failed";
    }
    return $genArr; //$finalArra;
}

function getInboxMessage($contactId, $userId, $limit = 10) {
    $sql = "SELECT p.id,p.sender_id,p.receiver_id,p.message,p.time,p.status,u.firstname,u.lastname FROM `privatemessae` as p JOIN user_personal_info as u ON u.id=p.sender_id WHERE ((p.sender_id = $contactId and p.receiver_id=$userId) or (p.sender_id = $userId and p.receiver_id=$contactId)) order by p.time desc limit $limit";
    $result = mysql_query($sql);
    $response = "";
    if (mysql_num_rows($result) > 0) {
        $arr = array();
        while ($row = mysql_fetch_array($result)) {
            $img = "";
            $img = getUserPixSet($row['sender_id']);
            $arr[] = "<div class='post' id='inb_conv" . $row['id'] . "'><img class='profile_small' src='" . $img['image50x50'] . "'/><p class='name'><a href='#'>" . $row['lastname'] . " " . $row['firstname'] . "</a></p><p class='status'>" . $row['message'] . "</p><p class='time' id='inb_conv_tc" . $row['id'] . "'>" . agoServer($row['time']) . "</p></div><script>setTimeout(timeUpdate,20000,'" . $row['time'] . "','inb_conv_tc" . $row['id'] . "')</script>";
        }
        $arr = array_reverse($arr);
        $response = "<span id='message'>";
        foreach ($arr as $x) {
            $response .= $x;
        }
        $response .= '</span><div id="commentbox"><form method="GET" onsubmit="getValue(\'' . $contactId . '\',\'commentConver\');return false"><input class="commenttext" type="text" id="m' . $contactId . '"/><span id="conver_loading"></span></form><div class="arrowdown"> </div></div>';
    } else {
        $response .= 'Conversation does not exist!';
    }
    $sql = "UPDATE `privatemessae` SET `status`= 'R' WHERE `sender_id`='$contactId' and `receiver_id` = '$userId'";
    mysql_query($sql);
    return $response;
}

function showMyComm($userId) {
    $sqlSub = "SELECT cs.`user`,cs.`community_id`,c.`name` FROM community_subscribers AS cs JOIN community AS c ON c.id=cs.`community_id` WHERE cs.`user` = $userId";
    $result = mysql_query($sqlSub);
    $sqlMy = "SELECT community_id FROM user_comm WHERE user_id = $userId";
    $resultMy = mysql_query($sqlMy);
    $myComm = mysql_fetch_array($resultMy);

    $arr = array();
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $temp = array();
            $temp['id'] = $row['community_id'];
            $temp['name'] = $row['name'];
            $arr['data'][] = $temp;
        }
        $arr['community_id'] = $myComm['community_id'];
    }
    return $arr;
}

function getSugestedComm($userId) {
    $sqlSubCom = "SELECT * FROM community_subscribers WHERE `user`=$userId";
    $resultSubCom = mysql_query($sqlSubCom);
    $arrSubCom = array();
    if (mysql_num_rows($resultSubCom) > 0) {
        while ($row = mysql_fetch_array($resultSubCom)) {
            $arrSubCom[$row['community_id']] = $row['community_id'];
        }
    }

    $sql = "SELECT * FROM community";
    $resultComm = mysql_query($sql);
    $responseArr = array();
    if (mysql_num_rows($resultComm) > 0) {
        while ($row = mysql_fetch_array($resultComm)) {
            if (!array_key_exists($row['id'], $arrSubCom)) {
                $arrSugCom = array();
                $arrSugCom['name'] = toSentenceCase($row['name']);
                $arrSugCom['id'] = $row['id'];
                $responseArr['data'][] = $arrSugCom;
            }
        }
    }

    return $responseArr;
}

function getCommunityInfo($commId) {
    $sql = "SELECT count(cs.community_id) as subsribers,c.name,c.category,c.description,c.datecreated FROM community_subscribers as cs JOIN community as c on cs.community_id=c.id WHERE cs.community_id=$commId";
    $result = mysql_query($sql);
    $arr = array();
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $arr = array("subscriber" => $row['subsribers'], "name" => $row['name'], "category" => $row['category'], "description" => $row['description'], "datecreated" => $row['datecreated']);
    }
    return $arr;
}

function getAllCommunity() {
    $sqlSub = "SELECT c.*,count(cs.user) as subscriber FROM community as c LEFT JOIN community_subscribers as cs on cs.community_id=c.id group by(c.id) order by subscriber desc"; //subscriber count
    $sqlPost = "SELECT community_id,count(community_id) as count FROM `post` group by community_id"; //post count in community
    $sqlComm = "SELECT p.community_id ,count(c.`id`)as commentCount FROM `comments` as c RIGHT JOIN post as p on p.id=c.post_id group by p.community_id"; //comment count
    $sqlLastSender = "SELECT p.id,p.community_id,p.`sender_id`,u.`firstname`,u.`lastname` FROM `post` as p JOIN user_personal_info as u on u.id=p.sender_id order by p.id"; //post sender

    $resultLastSender = mysql_query($sqlLastSender);
    $resultLastSenderArr = array();
    if (mysql_num_rows($resultLastSender) > 0) {
        while ($row = mysql_fetch_array($resultLastSender)) {
            $resultLastSenderArr[$row['community_id']] = array("id" => $row['id'], "sender" => $row['sender_id'], "firstname" => $row['firstname'], "lastname" => $row['lastname']);
        }
    }
    $resultComm = mysql_query($sqlComm);
    $resultCommArr = array();
    if (mysql_num_rows($resultComm) > 0) {
        while ($row = mysql_fetch_array($resultComm)) {
            $name['name'] = $resultLastSenderArr[$row['community_id']]['lastname'] . " " . $resultLastSenderArr[$row['community_id']]['firstname'];
            $name['id'] = $resultLastSenderArr[$row['community_id']]['sender'];
            $resultCommArr[$row['community_id']] = array("community_id" => $row['community_id'], "commentCount" => $row['commentCount'], "lastSender" => $name);
        }
    }
    $resultPost = mysql_query($sqlPost);
    $resultPostArr = array();
    if (mysql_num_rows($resultPost) > 0) {
        while ($row = mysql_fetch_array($resultPost)) {
            $resultPostArr[$row['community_id']] = array("community_id" => $row['community_id'], "count" => $row['count']);
        }
    }
    $resultCommunity = mysql_query($sqlSub);
    $resultCommunityArr = array();
    if (mysql_num_rows($resultCommunity) > 0) {
        while ($row = mysql_fetch_array($resultCommunity)) {
            if (isset($resultPostArr[$row['id']])) {
                $postCount = $resultPostArr[$row['id']]['count'];
                if (isset($resultCommArr[$row['id']])) {
                    $resultCommunityArr[] = array("id" => $row['id'], "name" => toSentenceCase($row['name']), "description" => $row['description'], "subscriber" => $row['subscriber'], "postCount" => $postCount, "commentCount" => $resultCommArr[$row['id']]['commentCount'], "lastSender" => $resultCommArr[$row['id']]['lastSender']['name'], "lastSender_id" => $resultCommArr[$row['id']]['lastSender']['id']);
                } else {
                    $resultCommunityArr[] = array("id" => $row['id'], "name" => toSentenceCase($row['name']), "description" => $row['description'], "subscriber" => $row['subscriber'], "postCount" => $postCount, "commentCount" => 0, "lastSender" => "", "lastSender_id" => "");
                }
            } else {
                if (isset($resultCommArr[$row['id']])) {
                    $resultCommunityArr[] = array("id" => $row['id'], "name" => toSentenceCase($row['name']), "description" => $row['description'], "subscriber" => $row['subscriber'], "postCount" => 0, "commentCount" => $resultCommArr[$row['id']]['commentCount'], "lastSender" => $resultCommArr[$row['id']]['lastSender']['name'], "lastSender_id" => $resultCommArr[$row['id']]['lastSender']['id']);
                } else {
                    $resultCommunityArr[] = array("id" => $row['id'], "name" => toSentenceCase($row['name']), "description" => $row['description'], "subscriber" => $row['subscriber'], "postCount" => 0, "commentCount" => 0, "lastSender" => "", "lastSender_id" => "");
                }
            }
        }
    }
    return $resultCommunityArr;
}

function toSentenceCase($str) {
    $arr = explode(' ', $str);
    $exp = array();
    foreach ($arr as $x) {
        if (strtolower($x) == "of") {
            $exp[] = strtolower($x);
        } else {
            $exp[] = strtoupper($x[0]) . substr($x, 1);
        }
    }
    return implode(' ', $exp);
}

function joinCommunity($userId, $commId) {
    $arr = array();
    $sqlCheck = "SELECT * FROM user_comm WHERE user_id='$userId'";
    $result = mysql_query($sqlCheck);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        if ($commId == $row['community_id']) {
            $arr['status'] = "failed";
            $arr['message'] = "You already belong to this community";
            return $arr;
        }
    }
    $commInfo = getCommunityInfo($commId);
    $sql = "UPDATE user_comm SET community_id='$commId' WHERE user_id='$userId'";
    mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        $comm = array();
        $comm['id'] = $commId;
        $comm['name'] = $commInfo['name'];
        $comm['category'] = $commInfo['category'];
        $_SESSION['auth']['community'] = $comm;

        $sql = "INSERT INTO community_subscribers(`user`,community_id) VALUES('$userId','$commId')";
        @mysql_query($sql);
        $arr['status'] = "success";
        $arr['message'] = "Operation was  Successfull";
        $arr['comm'] = $commInfo['name'];
    } else {
        $sql = "INSERT INTO user_comm(user_id,community_id) VALUES('$userId','$commId')";
        mysql_query($sql);
        if (mysql_affected_rows() > 0) {
            $sql = "INSERT INTO community_subscribers(`user`,community_id) VALUES('$userId','$commId')";
            @mysql_query($sql);
            $arr['status'] = "success";
            $arr['message'] = "Operation was  Successfull";
            $arr['comm'] = $commInfo['name'];

            $comm = array();
            $comm['id'] = $commId;
            $comm['name'] = $commInfo['name'];
            $comm['category'] = $commInfo['category'];
            $_SESSION['auth']['community'] = $comm;
        } else {
            $arr['status'] = "failed";
            $arr['message'] = "You cannot join this community";
        }
    }
    return $arr;
}

function unsubscribe($userId, $comm) {
    $arr = array();
    $sql = "DELETE FROM `community_subscribers` WHERE `user` = $userId AND `community_id` = $comm";
    mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        $arr['status'] = "success";
        $arr['message'] = "Unsubscribe successful";
    } else {
        $arr['status'] = "failed";
        $arr['message'] = "Operation failed! Please try again in a few moment";
    }
    return $arr;
}

function subscribe($userId, $comm) {
    $sql = "INSERT INTO community_subscribers(`user`,community_id) VALUES('$userId','$comm')";
    @mysql_query($sql);
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $arr['status'] = "success";
        $arr['message'] = "Subscribe successful";
    } else {
        $arr['status'] = "failed";
        $arr['message'] = "Operation failed! Please try again in a few moment";
    }
    return $arr;
}

function shortenStr($str, $minLen = 24) {
    $comName = "";
    if (strlen($str) > $minLen) {
        $comName = substr($str, 0, $minLen) . "...";
    } else {
        $comName = $str;
    }
    return $comName;
}

function getCommunityMembers($commId) {
    $sql = "SELECT cs.user,concat(p.lastname,' ', p.firstname) as fullname,p.location,cs.datejoined FROM community_subscribers AS cs JOIN user_personal_info as p ON p.id=cs.`user` WHERE cs.community_id=$commId";
    $result = mysql_query($sql);
    $arr = array();
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $arr[$row['user']] = array("id" => $row['user'], "fullname" => toSentenceCase($row['fullname']), "image" => getUserPixSet($row['user']), "location" => $row['location'], "datejoined" => $row['datejoined']);
        }
    }return $arr;
}

function getUserFriends($userId, $accepted = false) {
    $arr = array();
    if (!$accepted) {
        //get both friends accepted and pending
        $sql = "SELECT if($userId<>uc.username1,uc.username1,uc.username2) AS id,concat(p.lastname,' ',p.firstname) AS fullname,p.location,uc.time FROM usercontacts as uc JOIN user_personal_info AS p ON if($userId<>uc.username1,uc.username1,uc.username2)=p.id WHERE (uc.username1=$userId OR uc.username2=$userId) AND uc.status<>'D' AND uc.status<>'C'";
    } else {
        //get only accepted friends
        $sql = "SELECT if($userId<>uc.username1,uc.username1,uc.username2) AS id,concat(p.lastname,' ',p.firstname) AS fullname,p.location,uc.time FROM usercontacts as uc JOIN user_personal_info AS p ON if($userId<>uc.username1,uc.username1,uc.username2)=p.id WHERE (uc.username1=$userId OR uc.username2=$userId) AND uc.status='Y'";
    }

    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $arr[$row['id']] = array("id" => $row['id'], "fullname" => $row['fullname'], "image" => getUserPixSet($row['id']), "location" => $row['location'] ? $row['location'] : "Not Specified");
        }
    }
    return $arr;
}

function acceptFrq($userId, $frndId, $key) {
    $sql = "UPDATE usercontacts SET status='Y' WHERE sender_id=$frndId AND username2=$userId AND id=$key";
    mysql_query($sql);
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $arr['status'] = "success";
        $arr['message'] = "Operation Successfull!";
    } else {
        $arr['status'] = "failed";
        $arr['message'] = "Operation failed!";
    }
    return $arr;
}

function declineFrq($userId, $frndId, $key) {
    $sql = "UPDATE usercontacts SET status='D' WHERE sender_id=$frndId AND username2=$userId AND id=$key";
    mysql_query($sql);
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $arr['status'] = "success";
        $arr['message'] = "Operation Successfull!";
    } else {
        $arr['status'] = "failed";
        $arr['message'] = "Operation failed!";
    }
    return $arr;
}

function search($term) {
    $val = array();
    $sqlP = "SELECT * FROM `user_personal_info` WHERE `firstname` LIKE '%" . clean($term) . "%' OR `lastname` LIKE '%" . clean($term) . "%' OR email LIKE '%" . clean($term) . "%'";
    $resultP = mysql_query($sqlP);
    if (mysql_num_rows($resultP) > 0) {
        while ($row = mysql_fetch_array($resultP)) {
            $img = getUserPixSet($row['id']);
            $image = array();
            $image['image3535'] = $img['image35x35'];
            $image['image5050'] = $img['image50x50'];
            $image['image100100'] = $img['image100x100'];
            $val['people'][] = array("id" => $row['id'], "fullname" => $row['lastname'] . ' ' . $row['firstname'], "location" => $row['location'], "img" => $image);
        }
    }
    $sqlC = "SELECT c.*,count(cs.community_id) as subscriber FROM `community` as c JOIN community_subscribers as cs on c.id=cs.community_id WHERE c.`name` LIKE '%" . clean($term) . "%' group by cs.community_id";
    $resultC = mysql_query($sqlC);
    if (mysql_num_rows($resultC) > 0) {
        while ($row = mysql_fetch_array($resultC)) {
            $val['community'][] = array("id" => $row['id'], "name" => $row['name'], "subscriber" => $row['subscriber']);
        }
    }
    return $val;
}

function sendTweakWink($userId, $receiver_id, $tweakwink) {
    $sql = "SELECT * FROM tweakwink WHERE sender_id=$userId AND receiver_id=$receiver_id AND `type`='" . clean(htmlspecialchars($tweakwink)) . "' AND status='N'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        $arr = array();
        if ($tweakwink == "T") {
            $msg = "Wait while this user get your tweak before you can tweak again";
        } else if ($tweakwink == "W") {
            $msg = "Wait while this user get your wink before you can wink again";
        }
        $arr['status'] = "failed";
        $arr['message'] = $msg;
        return $arr;
    }
    $sql = "INSERT INTO tweakwink(sender_id,receiver_id,`type`) VALUES('$userId','$receiver_id','" . clean(htmlspecialchars($tweakwink)) . "')";
    mysql_query($sql);
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $arr['status'] = "success";
        $arr['message'] = "Operation successfull";
    } else {
        $arr['status'] = "failed";
        $arr['message'] = "You cannot perform this operation at this moment";
    }
    return $arr;
}

function canTweakWink($userId, $receiver_id, $type) {
    $sql = "SELECT * FROM tweakwink WHERE sender_id=$userId AND receiver_id=$receiver_id AND status='N' AND `type`='$type'";
    $result = mysql_query($sql);
    $arr = array();
    if (mysql_num_rows($result) > 0) {
        $arr['status'] = false; //sent already, you cannot send aagain until previous is received
    } else {
        $arr['status'] = true; //you can send since all previous is delivered
    }
    return $arr;
}

function gossout($userId, $postId, $community_id, $community_name, $senderFullname) {
    $postSql = "SELECT p.id,p.post,c.name,p.community_id,p.sender_id,p.time,s.`lastname`,s.`firstname`,cp.`250x250` FROM `post` as p JOIN user_personal_info as s on p.sender_id=s.id JOIN community_subscribers as cs on (cs.user=$userId and cs.`community_id`=p.`community_id`) JOIN community as c on cs.`community_id`=c.id LEFT JOIN community_pix as cp ON p.id = cp.post_id WHERE p.id=" . clean($postId);
    $result = mysql_query($postSql);
    $postRow = mysql_fetch_array($result);
    $image = getUserPixSet($postRow['sender_id']);
    if ($postRow['250x250'] == NULL) {
        $sharedPost1 = '<span class="notBold">Gossout</span><div class="post">
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name"><a href="page.php?view=profile&uid=' . $postRow['sender_id'] . '">' . $postRow['lastname'] . ' ' . $postRow['firstname'] . '</a></p><p class="status">' . clean($postRow['post']) . '</p><div class="post_activities"><span><a href="page.php?view=community&com=' . $postRow['community_id'] . '">in ' . clean($postRow['name']) . '</a></span></div></div>';
        $sharedPost2 = '<span class="notBold">Gossout</span><div class="post">
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name"><a href="page.php?view=profile&uid=' . $postRow['sender_id'] . '">' . $postRow['lastname'] . ' ' . $postRow['firstname'] . '</a></p><p class="status">' . $postRow['post'] . '</p><div class="post_activities"><span><a href="page.php?view=community&com=' . $postRow['community_id'] . '">in ' . $postRow['name'] . '</a></span></div></div>';
    } else {
        $sharedPost1 = '<span class="notBold">Gossout</span><div class="post">
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name"><a href="page.php?view=profile&uid=' . $postRow['sender_id'] . '">' . $postRow['lastname'] . ' ' . $postRow['firstname'] . '</a></p><p class="status">' . clean($postRow['post']) . '</p><ul class="box"><li><img src="' . $postRow['250x250'] . '"/></li></ul><div class="post_activities"><span><a href="page.php?view=community&com=' . $postRow['community_id'] . '">in ' . clean($postRow['name']) . '</a></span></div></div>';
        $sharedPost2 = '<span class="notBold">Gossout</span><div class="post">
                <img class="profile_small"src="' . $image['image50x50'] . '"/>
                <p class="name"><a href="page.php?view=profile&uid=' . $postRow['sender_id'] . '">' . $postRow['lastname'] . ' ' . $postRow['firstname'] . '</a></p><p class="status">' . $postRow['post'] . '</p><ul class="box"><li><img src="' . $postRow['250x250'] . '" onclick="enlargePostPix(\'' . $postRow['250x250'] . '\',\'' . $postRow['name'] . '\');"/></li></ul><div class="post_activities"><span><a href="page.php?view=community&com=' . $postRow['community_id'] . '">in ' . $postRow['name'] . '</a></span></div></div>';
    }
    $sql = "INSERT INTO `post`(`post`, `community_id`, `sender_id`) VALUES ('" . $sharedPost1 . "','$community_id','$userId')";
    mysql_query($sql);
    $arr = array();
    if (mysql_affected_rows() > 0) {
        $id = mysql_insert_id();
        $arr['id'] = $id;
        $arr['sender_id'] = $userId;
        $arr['imgL'] = $_SESSION['auth']['image50x50'];
        $arr['imgS'] = $_SESSION['auth']['image35x35'];
        $arr['name'] = $senderFullname;
        $arr['text'] = $sharedPost2;
        $arr['com_id'] = $community_id;
        $arr['com'] = $community_name;
        $arr['time'] = "now";
        $row = mysql_fetch_array(mysql_query("SELECT NOW() as rawTime"));
        $arr['rawTime'] = $row['rawTime'];
        $arr['status'] = "success";
        $arr['message'] = "Post sent successfully!";
        alertGossbag($userId, $id, $community_id, "$senderFullname shared a post from " . $postRow['name']);
    } else {
        $arr['message'] = "Failt to send your post at this time";
        $arr['status'] = "failed";
    }

    return $arr;
}

function sendEmail($to, $from, $fromName, $subject, $message) {
    // multiple recipients
//    $to = 'aidan@example.com' . ', '; // note the comma
//    $to .= 'wez@example.com';
    $to = 'soladnet@gmail.com';
// subject
// message
    $message = '
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />  
        <!--- http://bit.ly/NfpVMY -->
        <!--[if IE]>
        <link rel="stylesheet" href="//gossout.com/css/main.css" />
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet" media="screen and (min-device-width: 1024px)" href="//gossout.com/css/main.css" />
        <link rel="stylesheet" media="screen and (max-device-width: 1023px)" href="//gossout.com/css/medium.css" />
    </head>
    <body>

        <div class="container">

            <div id="nav2" class="nav2_gradient">
                <span id="logo">
                    <a href="http://gossout.com" title="Logo"><img src="//gossout.com/images/logo_text_s.png" alt="Gossout"></a>
                </span>
                <div class="clear"></div>
            </div>

            <div class="center_div width800">
                <div class="inner_wrappper box_shadow8 center_div ">
                    <div id="column1">
                        <p  class="info">You are receiving this email because you belong to Gossout community. This transmission (including any attachments) may contain confidential information, privileged material (including material protected by the solicitor-client or other applicable privileges), or constitute non-public information. Any use of this information by anyone other than the intended recipient is prohibited. If you have received this transmission in error, please immediately reply to the sender and delete this information from your system. Use, dissemination, distribution, or reproduction of this transmission by unintended recipients is not authorized and may be unlawful.</p>
            <!--                        <p>About what Gossout is...</p>
            <span>Or anything!...</span>-->
                    </div>

                    <div id="column2">
                        <div id="signup_form" class="box_shadow1 box_round_5" >
                            <div class="post">
                                
                                <p class="name heading">
                                    Welcome to Gossout Community
                                </p>
                                <p class="status">
                                    Congratulations NAME! Your registration on Gossout.com have been received successfully.
                                </p>
                                <p class="status">
                                    You are just in the final step of your registration. Please click the link below or copy and past it in your address bar to confirm your email address.
                                </p>
                                <p class="status">
                                    Activation link: URL TO CLICK
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="nav2" class="footer nav2_gradient">
            <span id="logo">
                &copy; <?php echo date("Y") ?> Gossout.com
            </span>
            <div class="clear"></div>
        </div>
        <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(["_setAccount", "UA-32894251-1"]);
  _gaq.push(["_setDomainName", "gossout.com"]);
  _gaq.push(["_trackPageview"]);

  (function() {
    var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
    ga.src = ("https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
    </body>
</html>
';

// To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
//    $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
    $headers .= "From: $fromName <$from>" . "\r\n";
//    $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//    $headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
// Mail it
    mail($to, $subject, $message, $headers);
}

function sendGetExternalData($url, $post_data) {
    foreach ($post_data as $key => $value) {
        $post_items[] = $key . '=' . $value;
    }
    $post_string = implode('&', $post_items);
    $curl_connection = curl_init($url);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
//curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
    $result = curl_exec($curl_connection);
    curl_close($curl_connection);
    return $result;
}

function make_links_clickable($text) {
    return preg_replace('!(((f|ht)tp://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank">$1</a>', $text);
}

function getGossoutUsers($withImage = true) {
    if ($withImage) {
        $sql = "SELECT * from ";
    }
}

?>
