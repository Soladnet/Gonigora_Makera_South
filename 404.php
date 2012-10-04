<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php include_once("head.php"); ?>
        <script type="text/javascript" src="js/home_ajaxfileupload.js"></script> 
        <style>
            #status_community{display: none;font-size: .7em;color:#999;}
        </style>
        <script>
            var laststate= 0;
            $(function() {
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
                jQuery(
                function($)
                {
                    $('.content').bind('scroll', function()
                    {
                        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
                            //alert(laststate);
                            laststate += 5;
                            showMorePost(laststate);
                            //                            alert(res);
                        }
                    })
                }
            );
                
            });
            
        </script>

    </head>
    <body id="#style">

        <div id="page">
            <?php include_once("nav.php") ?>
            <div class="inner-container" >
                <h1> 404 Page Not Found</h1>
                <div class="not_found">
                    <div class="not_found_msg"> Oops, the page isn't available, click the back button on your browser to go back or use the search bar. </div>
                <div>
             </div>
                <div id="dialog"></div>
                


            </div>

        </div>

    </body>
</html>
