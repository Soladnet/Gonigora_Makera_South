<?php

session_start();
include 'executecommand.php';
connect();
$joinExcept = array("Football Gossip", "Celebrity", "Fashion & Style", "Controversy");
if ($_POST['value'] == "mycommunity") {
    $res = "";
    $myCom = showMyComm($_SESSION['auth']['id']);
    if (count($myCom) > 0) {
        $res .= '<div class="heading box_round_5_tr">My Communities</div>';
    }

    $res .= '<div class="dropMenu">';

    if (count($myCom) > 0) {
        $tabindex = 0;
        $c = 0;
        foreach ($myCom['data'] as $value) {
            if ($myCom['community_id'] == $value['id']) {
                $res .= '<h3 class="community my_community">' . shortenStr($value['name']) . '<span class="addbutton ui-state-highlight home" id="hom' . $value['id'] . '"><img src="images/icon_home.png" /></span><div class="clear"></div></h3><div style="padding: 2px; font-size: 0.6em; cursor: pointer;" id="pan' . $value['id'] . '" class="panel"><span>This is your current community</span></div>';
            } else {
                if (in_array($value['name'], $joinExcept)) {
                    $res .= '<h3 class="community my_community">' . shortenStr($value['name']) . '<span class="home" id="hom' . $value['id'] . '"></span></h3><div style="padding:2px;font-size:.6em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><p><span onclick="unsubscribe(' . $value['id'] . ')">Unsubscribe</span><!-- | <span onclick="join(' . $value['id'] . ')">Join</span>--></p></div>';
                }else{
                    $res .= '<h3 class="community my_community">' . shortenStr($value['name']) . '<span class="home" id="hom' . $value['id'] . '"></span></h3><div style="padding:2px;font-size:.6em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><p><span onclick="unsubscribe(' . $value['id'] . ')">Unsubscribe</span> | <span onclick="join(' . $value['id'] . ')">Join</span></p></div>';
                }
            }
            if ($tabindex == 0) {
                $tabindex = -1;
            }
            $c++;
            if ($c >= 5) {
                break;
            }
        }
    }
    $res .= '</div>
        <script>
        $(function(){
        $(".dropMenu").accordion({
        autoHeight: false,
        navigation: true,
        collapsible: true,
        active: false,
        icons: false
        });        
        });
        $( "div .community" ).click(function() {
            $("div .community").removeClass("community_selected");
            $(this).toggleClass( "community_selected");
            return false;
        });        
</script>';
    echo $res;
} else if ($_POST['value'] == "suggestion") {
    
    $sug = "";

    $sugComm = getSugestedComm($_SESSION['auth']['id']);
    if (count($sugComm)) {
        $sug .= '<div class="heading">Suggested Community</div><div class="dropMenu">';
        shuffle($sugComm['data']);
        $tabindex = 0;
        foreach ($sugComm['data'] as $value) {
            if (in_array($value['name'], $joinExcept)) {
                $sug .= '<h3 class="community">' . $value['name'] . '<span class="home" id="hom' . $value['id'] . '"></span></h3><div style="padding:2px;font-size:.6em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><p><span onclick="subscribe(' . $value['id'] . ')">Subscribe</span> <!--| <span onclick="join(' . $value['id'] . ')">Join</span>--></p></div>';
            } else {
                $sug .= '<h3 class="community">' . $value['name'] . '<span class="home" id="hom' . $value['id'] . '"></span></h3><div style="padding:2px;font-size:.6em;cursor:pointer;" id="pan' . $value['id'] . '" class="panel"><p><span onclick="subscribe(' . $value['id'] . ')">Subscribe</span> | <span onclick="join(' . $value['id'] . ')">Join</span></p></div>';
            }
            if ($tabindex > 3) {
                break;
            } else {
                $tabindex++;
            }
        }
        $sug .= '</div><script>$(function(){
$(".dropMenu").accordion({
            autoHeight: false,
            navigation: true,
            collapsible: true,
            active: false,
            icons: false
        });        
})</script>';
    }

    echo $sug;
}
?>
