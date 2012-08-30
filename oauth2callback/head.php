<title>
    <?php
    if (isset($_GET['view'])) {
        echo $_GET['view'];
    } else {
        echo "Gossout";
    }
    ?>
</title>
<link rel="shortcut icon" href="../favicon.ico" />
<meta name="viewport" content="width=device-width"> <!--- http://bit.ly/NfpVMY -->

<link rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.22.custom.css" type="text/css" />


<!--[if IE]>
<link rel="stylesheet" href="../css/main.css" />
<![endif]-->
<link rel="stylesheet" media="screen and (min-device-width: 1024px)" href="../css/main.css" />
<link rel='stylesheet' media='screen and (min-width: 240px) and (max-width: 1023px)' href='../css/medium.css' />


<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.22.custom.min.js"></script>
<script type="text/javascript" src="../js/easyloader.js"></script>

<!--<script src="js/login.js" type="text/javascript"></script>-->
<script src="../js/script.js" type="text/javascript"></script>
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
                        $("#404").dialog({
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
                //                error: function( xhr, status, index, anchor ) {
                //                    $( anchor.hash ).html(
                //                    "Something just went wrong...we'll fix this soon. Please try again now" );
                //                }
            },
            cache: false
        });
        $( "#info" ).sortable({revert: true});
        $(".gossbox-list").draggable({connectToSortable: "#info",revert:true, containment:"parent", axis:"y"});
    });

    $(document).ready(function(){
        $("#messenger").hide();
        
        getUpdateCount();
        showMenu('#popGossbag','.menu1');
        showMenu('#popMessage','.menu2');
        showMenu('#popFriend_Request','.menu3');
    });
    
    
</script>