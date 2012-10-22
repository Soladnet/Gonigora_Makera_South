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
<link rel="stylesheet" href="css/1-theme-bg-images-fonts-colors.css" />
<link rel="stylesheet" href="css/main.css" />
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link rel="stylesheet" href="css/1-theme-bg-images-fonts-colors.css" />
<link rel="stylesheet" media="screen and (min-device-width: 1024px)" href="css/main.css" />
<link rel='stylesheet' media="screen and (max-device-width: 1023px)" href="css/medium.css" />

<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>

<script src="js/script.js" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        $("#tabs").tabs({
            ajaxOptions: {
                spinner: "Loading..."
            },
            
            cache: false
        });
        
        $( "#tabs" ).tabs(  "select" , <?php echo isset($_GET['view'])?$_GET['view']=="profile"?isset($_GET['photo'])?1:0:0:0 ?>  );
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
        $("#messenger").hide();
        getUpdateCount();
        showMenu('#popGossbag','.menu1');
        showMenu('#popMessage','.menu2');
        showMenu('#popFriend_Request','.menu3');
        showMenu('#popSettings','.menu4');
        $("input").blur(function(){
            setTimeout(function(){
               $('#suggestions').fadeOut(); 
            },1000);
            
        });
        $('div.tabs').tabs();
    });
   
</script>