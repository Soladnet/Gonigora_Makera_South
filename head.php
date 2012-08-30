<title>
    <?php
    if (isset($_GET['view'])) {
        echo toSentenceCase($_GET['view']);
    } else {
        echo "Gossout";
    }
    ?>
</title>
<link rel="shortcut icon" href="favicon.ico" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />  
<!--- http://bit.ly/NfpVMY -->
<link rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.23.custom.css" type="text/css" />
<!--[if IE]>
<link rel="stylesheet" href="css/main.css" />
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link rel="stylesheet" media="screen and (min-device-width: 1024px)" href="css/main.css" />
<link rel='stylesheet' media="screen and (max-device-width: 1023px)" href="css/medium.css" />

<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>

<script src="js/script.js" type="text/javascript"></script>
<?php
if (isset($_GET['view'])) {
    if ($_GET['view'] == 'profile') {
        
    }
}
?>
<script>
    $(document).ready(function() {
        $("#tabs").tabs({
            ajaxOptions: {
                spinner: "Loading...",
                
                statusCode:{
                    404: function(){
                        $("#dialog").dialog({
                            autoOpen: true,
                            modal: true,
                            buttons:{
                                OK: function(){
                                    $(this).dialog( "close" );
                                }
                            },
                            minHeight: 200
                        });
                    },
                    400: function(){
                        //bad request
                    },
                    401: function(){
                        //unauthorized
                    },
                    403: function(){
                        //forbiden
                    },
                    407: function(){
                        //proxy auth requ
                    },
                    408: function(){
                        //timeout
                    },
                    450: function(){
                        //parental control
                    }
                }
           
                
            },
            cache: false
        });
        $( "div .community" ).click(function() {
            $("div .community").removeClass("community_selected");
            $(this).toggleClass( "community_selected");
            return false;
        });
        $( "#info" ).sortable({revert: true});
        $(".gossbox-list").draggable({connectToSortable: "#info",revert:true, containment:"parent", axis:"y"});
        $(".dropMenu").accordion({
            autoHeight: false,
            navigation: true,
            collapsible: true,
            active: false,
            icons: false
        });
    });
    $(document).ready(function(){
        $("#messenger").hide();
        getUpdateCount();
        showMenu('#popGossbag','.menu1');
        showMenu('#popMessage','.menu2');
        showMenu('#popFriend_Request','.menu3');
        showMenu('#popSettings','.menu4');
    });
	
	$(function() {
		$('div.tabs').tabs();
	});
</script>