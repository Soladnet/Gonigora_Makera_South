<?php
if (!isset($_SESSION['auth'])) {
    session_unset();
    header("Location:index.php");
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php include_once("head.php"); ?>
        <style>
            .content,select,input{
                font-size: .8em;
            }
        </style>
        <script>
            function dfn(selected,id){
                $("#"+id).val(selected);
                return;
            }
            function autoComplete(id){
                var cache = {}
                $( "#"+id ).autocomplete({
                    
                    source: function( request, response ) {
                        var term = request.term;
                        var searchType = "";
                        if(id!="location"){
                            searchType = $("#comcat").val();
                        }else{
                            searchType = "adcity";
                        }
                        if ( term in cache ) {
                            response( $.map( data.data, function( item ) {
                                var cname = item.name.split(",");
                                return {
                                    label: cname[0] + ", " + item.subtext,
                                    value: cname[0] + ", " + item.subtext
                                }
                            }));
                            return;
                        }
                        $.ajax({
                            url: "https://graph.facebook.com/search?",
                            dataType: "jsonp",
                            data: {
                                q: request.term,
                                type:searchType,
                                access_token: '347320715345278|b9fa8ad4aea8f9b56a3b83a6987bc5a2'
                            },
                            success: function( data ) {
                                response( $.map( data.data, function( item ) {
                                    var cname = item.name.split(",");
                                    return {
                                        label: cname[0] + ", " + item.subtext,
                                        value: cname[0] + ", " + item.subtext
                                    }
                                }));
                            }
                        });
                    },
                    minLength: 2,
                    select: function( event, ui ) {
                        dfn(ui.item.label,id);
                    },
                    open: function() {
                        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
                    },
                    close: function() {
                        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
                    }
                });
            }
            $(function(){
                $(".next").button({
                    icons: {
                        secondary: "ui-icon ui-icon-arrowthick-1-e"
                    }
                });
                $(".back").button({
                    icons:{
                        primary: "ui-icon ui-icon-arrowthick-1-w"
                    } 
                });
                $(".skip").button({
                    icons:{
                        secondary: "ui-icon ui-icon-extlink"
                    } 
                });
                $('.next').click(function(){ 
                    var $tabs = $('#tabs').tabs();
                    var selected = $tabs.tabs('option', 'selected');
                    $tabs.tabs('select', selected+1);
                });
                $('.back').click(function(){ 
                    var $tabs = $('#tabs').tabs();
                    var selected = $tabs.tabs('option', 'selected');
                    if(selected-1<0){
                        selected = 0;
                    }else{
                        selected = selected-1;
                    }
                    $tabs.tabs('select', selected);
                });
            });
            
            
        </script>
    </head>
    <body>
        <div id="page">
            <?php include_once("nav.php") ?>
            <div class="inner-container">
                <?php include_once("left.php"); ?>
                <div class="content">
                    <div id="tabs">
                        <ul class="tabNav">
                            <li class="lefttab"><a href="#community" >Join a community </a></li>
                            <li ><a href="#ppinfo" >Tell us about you </a></li>
                            <li ><a href="#friends" >Share</a></li>
                            <li class="righttab"><a href="page.php?view=upload&photos=" >Add photo </a></li>
                        </ul>
                        <div id="ppinfo">
                            <div class="ui-corner-all">

                                <div style="padding: 15px;">
                                    <p>This will take just a moment</p>
                                    <p class="heading"><img src="images/icon_user.png" style="padding: 4px;" />Personal Information</p> 
                                    <ul>
                                        <li class="ui-state-active ui-corner-all" title="edit personal info" style="float:right; text-decoration: none;">
                                            <span class="ui-icon ui-icon-check" id="pinfo" onclick="editInfo('pinfo');"></span>
                                        </li>
                                    </ul>
                                    <table border="0" width="90%">
                                        <tr>
                                            <td align="right" width="50%">Relationship status:</td>
                                            <td>
                                                <span id='relationship'>
                                                    <select name = 'relationship' id='editRelationship'>
                                                        <option value='Single'>Single</option>
                                                        <option value='In a relationship'>In a relationship</option>
                                                        <option value='Married'>Married</option>
                                                        <option value='Divorced'>Divorced</option>
                                                        <option value='Its Complicated'>Its Complicated</option>
                                                    </select>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right">Phone:</td><td><span id='phone'><input type='text' name = 'relationship' value='' id='editPhone' /></span></td>
                                        </tr>
                                        <tr>
                                            <td align="right">url:</td><td><span id='url'><input type='text' name = 'relationship' value='' id='editUrl' /></span></td>
                                        </tr>
                                    </table><br/>
                                    <p class="heading">
                                        <img src="images/icon_home.png" style="padding: 4px;" /> City
                                    </p> 
                                    <ul>
                                        <li class="ui-state-active ui-corner-all" title="edit current city" style="float:right; text-decoration: none;">
                                            <span class="ui-icon ui-icon-check" id="plocate" onclick="editInfo('plocate');"></span>
                                        </li>
                                    </ul>
                                    <table border="0" width="90%">
                                        <tr>
                                            <td align="right" width="30%"></td><td><span id="editlocation"><input type="text" onkeydown="autoComplete('location')" id="location" size=50 value=""/></span></td>
                                        </tr>
                                    </table>
                                    <span style="display: block;" class="menu_bottom">&nbsp;<a href="page.php?view=home"><span style="float: right; margin-left: .4em;margin-right: .4em;" class="skip" id="skip">Skip</span></a><span style="float: right; margin-left: .4em;margin-right: .4em;" class="next" id="join" onclick="editInfo('plocate');editInfo('pinfo');">Continue</span><span style="float: right" id="back" class="back">Back</span><span style="display: block" class="clear"></span></span>
                                </div>
                            </div>
                        </div>
                        <div id="community">
                            <div class="ui-corner-all">
                                <p style="padding: 8px; line-height: 2em;">Please note, joining a community on gossout is a prerequisite. Use the fields below to specify a community of your choice or navigate to your home page to see suggested communities </p>
                                <p class="heading">
                                    <img src="images/icon_communities.png" style="padding: 4px;" />Subscribe to a community for gossips
                                </p>
                                <ul>
                                    <li class="ui-state-active ui-corner-all" title="Join Community" style="float:right; text-decoration: none;">
                                        <span class="ui-icon ui-icon-check" id="pcommunity" onclick="editInfo('pcommunity');"></span>
                                    </li>
                                </ul>
                                <div style="height: 10em;padding: 15px;" >
                                    <table width="90%">
                                        <tr>
                                            <td width="30%">Category</td><td>
                                                <span id="editcomcat"><select id="comcat">
                                                        <option value="adcollege">Campus / College</option>
                                                        <option value="adcity">City</option>
                                                    </select></span>
                                            </td>
                                        </tr><tr>
                                            <td width="30%">
                                               Community Name: 
                                            </td><td><span id="editcommunity"><input  id="com" onkeydown="autoComplete('com')" type="text" size=50 /></span></td>
                                        </tr>
                                    </table>
<span style="display: block;" class="menu_bottom">&nbsp;<span style="float: right; margin-left: .4em;margin-right: .4em;" class="next" id="join" onclick="editInfo('pcommunity')">Continue</span><!--<span style="float: right" id="skip" class="back">Back</span>--><span style="display: block" class="clear"></span></span>
                                </div>
                            </div>
                        </div>
                        <div id="friends">
                            <p class="heading">Disclaimer</p>
                            <p style="padding: 8px; line-height: 2em;">Gossout won't share your personal information you import with anyone, but we will store them (when necessary) on your behalf and may use them later to help others search for people or to generate friend suggestions for you and others. <!--Depending on your email provider, addresses from your contacts list and mail folders may be imported. You should only import contacts from accounts you've set up for personal use.--></p>
                            <p>
                                <a href="page.php?view=facebook"><img src="images/facebook1.png" /></a>
                            </p>
                            <span style="display: block;" class="menu_bottom">&nbsp;<a href="page.php?view=home"><span style="float: right; margin-left: .4em;margin-right: .4em;" class="skip" id="skip">Skip</span></a><span style="float: right; margin-left: .4em;margin-right: .4em;" class="next" id="join">Continue</span><span style="float: right" id="back" class="back">Back</span><span style="display: block" class="clear"></span></span>
                        </div>
                        <!--                        <div id="photo">
                                                    Add photo
                                                </div>-->
                    </div>					
                    <div class="clear"></div>
                    <div id="messenger" class="ui-widget ui-corner-all left-boxes box_round_15 box_shadow1 ui-state-highlight"><span id="flashMsg"></span></div>
                </div>
                <?php include_once("right.php"); ?>
            </div>
        </div>
    </body>
</html>
