<?php
include 'executecommand.php';
connect();
$sql = "SELECT if(47<>uc.username1,uc.username1,uc.username2) AS id,concat(p.firstname,' ',p.lastname) AS fullname,p.location,com.name,uc.time FROM usercontacts as uc JOIN user_personal_info AS p ON if(47<>uc.username1,uc.username1,uc.username2)=p.id LEFT JOIN `user_comm` as ucom ON if(47<>uc.username1,uc.username1,uc.username2)=ucom.user_id LEFT JOIN community as com ON ucom.community_id=com.id WHERE (uc.username1=47 OR uc.username2=47) AND uc.status<>'D' AND uc.status<>'C'";
$result = mysql_query($sql);
if(mysql_num_rows($result)>0){
    $arr = array();
    while($row = mysql_fetch_array($result)){
        $arr[] = $row['id'];
    }
    $r = "";
    foreach ($arr as $x){
        if($r != "")
            $r .= ",";
        $r .= $x;
    }
    $r = explode(",", $r);
    echo implode(" or sender_id = ", $r);
}
?>