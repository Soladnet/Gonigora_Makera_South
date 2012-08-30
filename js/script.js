function parseURLParams(url,count) {
    var queryStart = url.indexOf("?") + 1;
    var queryEnd   = url.indexOf("#") + 1 || url.length + 1;
    var query      = url.slice(queryStart, queryEnd - 1);

    if (query === url || query === "") return;

    var params  = {};
    var nvPairs = query.replace(/\+/g, " ").split("&");

    for (var i=0; i<nvPairs.length; i++) {
        var nv = nvPairs[i].split("=");
        var n  = decodeURIComponent(nv[0]);
        var v  = decodeURIComponent(nv[1]);
        if ( !(n in params) ) {
            params[n] = [];
        }
        params[n].push(nv.length === 2 ? v : null);
    }
    t = (params.view+"").substring(0, 1).toUpperCase()+(params.view+"").substring(1, (params.view+"").length);
    if(count>0){
        document.title = t+" ( "+count+" )";
    }else{
        document.title = t;
    }
}
function getValue(id,type){
    if($(id).hasClass("sending")){
        return;
    }else{
        $(id).addClass("sending");
    }
    if(type=="posts"){
        
        var val = $.trim($(id).val());
        if($("#fileToUpload").val()==""){
            if(val==""){
                return;
            }else{
                $("#share_loading").html('<img src="images/load.gif" />');
                $(id).attr('disabled', 'disabled');
                sendData(val,type,'.posts',id);   
            }
        }else{
            $(id).attr('disabled', 'disabled');
            $("#share_loading").html('<img src="images/load.gif" />');
            ajaxFileUpload(val,id);
        }
    }
    else if(type=="commentsPost"){
        var comment = $.trim($("#c"+id).val());
        if(comment==""){
            return;
        }
        $("#c"+id).attr('disabled', 'disabled').addClass("sending");
        $("#c_loading"+id).html("<img src='images/load.gif' />");
        sendComment(comment, type, "#comments"+id, "#c"+id, id);
        $("#c"+id).removeAttr('disabled');
    
    }else if(type=="commentConver"){
        var conver = $.trim($("#m"+id).val());
        if(conver==""){
            return;
        }
        $("#m"+id).attr('disabled', 'disabled').addClass("sending");
        
        $("#c_loading"+id).html("<img src='images/load.gif' />");
        sendComment(conver, type, "#message", "#m"+id, id);
        $("#m"+id).removeAttr('disabled');
    //        $( ".content" ).scrollTop( 3534543 );
    
    }
}
function sendData(value,to,updateHtml,source){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: to,  
            posts: value
        },
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(to=="posts"){
                if (output){ // fix strange ie bug
                    if(output.status=="success"){
                        //                        alert(output.status);
                        var result = '<div class="post" id=' + output.id + '><img class="profile_small"src="' +output.imgL+ '"/><p class="name"><a href="page.php?view=profile&uid='+output.sender_id+'">' +output.name+ '</a></p><p class="status">' +output.text+ '</p><p class="time" id="tp'+output.id+'">' +output.time+ '</p><div class="post_activities"> <span onclick="showGossoutModeldialog(\'dialog\',\'' + output.id + '\');">Gossout</span> . <span onclick="showCommentBox(\'box'+output.id+'\',\''+output.id+'\',\''+output.imgS+'\')">comment</span> . <span><a href="page.php?view=community&com='+output.com_id+'">in '+output.com+'</a></span></div><script>setTimeout(timeUpdate,20000,\''+output.rawTime+'\',\'tp'+output.id+'\')</script><span id="comments' +output.id+ '"></span><span id="box'+output.id+'"></span></div>';
                        $(updateHtml).html(result+$(updateHtml).html());
                        $( "#status" ).animate({
                            height:30
                        }, 1000 );
                        $("#status_community").css("display", "none");
                        $(source).removeAttr('disabled');
                        $(source).val("");
                        $(source).removeClass("sending");
                        showFlashMessageDialoge(output.message,"messenger","info");
                        
                    }
                    else if(output.status=="failed"){
                        $(source).removeAttr('disabled');
                        showFlashMessageDialoge(output.message,"messenger","error");
                    }
                }
                $("#share_loading").html("");
            }
        
        
        }
    });
}
function sendComment(value,to,updateHtml,source,postId){
    $.ajax({
        url: 'exec.php', 
        data: {
            action: to, 
            posts: value,
            sourceId:postId
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            function callback() {
                setTimeout(function() {
                    $( "shade" ).removeAttr( "style" ).hide().fadeIn();
                }, 1000 );
                $(".post").removeClass("shade");
            }
            if (output){ // fix strange ie bug
                if(to=="commentsPost"){
                    var result = '<div id="comment" class='+ output.id + '><img class="profile_small" src="'+output.imgS+ '"/><p class="name"><a href="page.php?view=profile&uid='+output.sender_id+'">' +output.name+ '</a></p><p class="status">'  +output.text+ '</p><p class="time" id="tpc'+output.id+'">' +output.time+ '</p></div><script>setTimeout(timeUpdate,20000,\''+output.rawTime+'\',\'tpc'+output.id+'\')</script>';
                    
                    if(updateHtml!=""){
                        $(updateHtml).html($(updateHtml).html()+result);
                    }
                    $("#c_loading"+postId).html("");
                    $("#"+postId).removeClass("sending");
                }else if(to=="commentConver"){
                    if(updateHtml!=""){
                        result = "<div class='post shade' id='inb_conv" + output.id + "'><img class='profile_small' src='" +output.imgL+ "'/><p class='name'><a href='#'>"+output.name+ "</a></p><p class='status'>"  +output.text+ "</p><p class='time' id='inb_conv_tc"+output.id+"'>" +output.time+  "</p></div><script>setTimeout(timeUpdate,20000,'"+output.rawTime+"','inb_conv_tc"+output.id+"')</script>";
                        $(updateHtml).html($(updateHtml).html()+result);
                        $( ".shade" ).effect( "bounce", {}, 100, callback );
                        
                    }
                    else{
                        showFlashMessageDialoge("Message Sent!","messenger","info");
                    }
                }
                $(source).val(""); 
                $(source).removeClass("sending");
            }
        }
    });
}
function showCommentBox(updateHtml,id,img){
    var val = $("#"+updateHtml).html();
    if(val==""){
        $("#"+updateHtml).html('<div id="commentbox"><form method="GET" onsubmit="getValue(\'' +id+ '\',\'commentsPost\');return false"><table class="commentTable"><tr><td class="comment-img-td"><img class="profile_small" src="' + img + '" /></td><td><input class="commenttext" type="text" id="c' +id + '"/></td></tr></table><span id="c_loading' +id + '"></span></form>');
        $("#c"+id).focus();
    }else{
        $("#"+updateHtml).html("");
    }
}
function getUpdateCount(){
    $.ajax({
        url: 'exec.php', 
        data: {
            action: '', 
            count: ''
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            var count = 0;
            if (output){ // fix strange ie bug
                if(output.bag>0){
                    $('#gossbag').html("GossBag <sup class='req'>"+output.bag+"</sup>");
                    count = count + output.bag;
                }else{
                    $('#gossbag').html("GossBag");
                }
                if(output.msg>0){
                    $('#messages').html("Messages <sup class='req'>"+output.msg+"</sup>");
                    count = count + output.msg;
                }
                else{
                    $('#messages').html("Messages");
                }
                if(output.frq>0){
                    $('#friend_requests').html("Friend Requests <sup class='req'>"+output.frq+"</sup>");
                    count = count+ output.frq;
                }else{
                    $('#friend_requests').html("Friend Requests");
                }
            }
            
            parseURLParams(document.URL, count);
        }
    });
    setTimeout(getUpdateCount, 3000);
}
function getInbox(){
    $.ajax({
        url: 'exec.php',
        data: {
            action: "Messages",
            flcikr:''
        },
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            var result = "";
            $.each(output, function(i,output){
                if (output){ // fix strange ie bug
                    var lastSent = "";
                    if(output.isUser){
                        lastSent = "<img class='profile_small' src='images/reply.png'/>";
                    }else{
                        lastSent = "";
                    }
                    var shade = "";
                    if(output.status=="N"){
                        shade = " shade";
                    }else{
                        shade = "";
                    }
                    result += "<div class='post"+shade+"' id='"+output.msgid+ "'><img class='profile_small' src='" +output.img+ "'/>"+lastSent+"<p class='name'><a href='page.php?view=Messages&open=" +output.id+ "'>" +output.name+ "</a></p><p class='status'>"+output.text+"</p><p class='time' id='tim"+output.msgid+"'>" +output.time+ "</p></div><script>setTimeout(timeUpdate,20000,'"+ouput.rawTime+"','tim"+output.msgid+"')<script>";
                }
            });
            result += '';
            $("#inbox").html(result);
        }
    });
    setTimeout(getInbox, 30000);
}
function getUpdateFlicker(id,message){
    if($(id).hasClass("clicked")){
        $(id).removeClass("clicked");
        return;
    }
    var baseLink;
    //    if(message=="GossBag"){
    //        baseLink = "#";
    //    }else if(message=="Messages"){
    baseLink = "page.php?view="+message;
    //    }
    //    else if(message=="Friend Requests"){
    //        baseLink = "#";
    //    }
    
    $.ajax({
        url: 'exec.php',
        data: {
            action: message,
            flcikr:''
        },
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            $(id).addClass("clicked");
            var result = "";
            
            if (output){ // fix strange ie bug
                if(message=="GossBag"){
                    $("#gossbagloading").remove()
                    $.each(output.data, function(i,output){
                        if(output.infoType=="gb"){
                            result += "<div class='post' id='"+output.id+ "'><img class='profile_small' src='" +output.img+ "'/><p class='name'><a href='page.php?view=notification&open=" +output.post_id+ "'>" +output.fullname+ "</a></p><p class='status'>"+output.caption+"</p><div class='post_activities'>" +output.sTime+ "</div></div>";
                        }else{
                            result += "<div class='post' id='tw_f"+output.id+ "'><img class='profile_small' src='" +output.img+ "'/><p class='name'><a href='page.php?view=tweakwink&open=" +output.id+ "'>" +output.fullname+ "</a></p><p class='status'>"+output.caption+"</p><div class='post_activities'>" +output.sTime+ "</div></div>";
                        }
                        
                    });
                }else if(message=="Messages"){
                    $("#messagesloading").remove();
                    $.each(output, function(i,output){
                        var lastSent = "";
                        if(output.isUser){
                            lastSent = "<img class='profile_small' src='images/reply.png'/>";
                        }else{
                            lastSent = "";
                        }
                        var shade = "";
                        if(output.status=="N"){
                            shade = " shade";
                        }else{
                            shade = "";
                        }
                        result += "<div class='post"+shade+"' id='"+output.msgid+ "'><img class='profile_small' src='" +output.img+ "'/>"+lastSent+"<p class='name'><a href='page.php?view="+message+"&open=" +output.id+ "'>" +output.name+ "</a></p><p class='status'>"+output.text+"</p><div class='post_activities'>" +output.time+ "</div></div>"+'<script>makeMeClickable("#'+output.msgid+'");</script>';
                    });
                }else if(message=="Friend Requests"){
                    $("#friendreqloading").remove();
                    //                    alert("done");
                    $.each(output, function(i,output){
                        result += "<div class='post' id='frq"+output.id+ "'><img class='profile_small' src='" +output.img+ "'/><p class='name'><a href='page.php?view="+message+"&open=" +output.id+ "'>" +output.fullname+ "</a></p><p class='status'>"+output.caption+"</p><p class='time'>" +output.time+ "</p>"+'<span id="requestoption'+output.id+'"><input type="submit" value="Accept" onclick="acceptOrDeclineFrq(\''+output.id+'\',\'acpt\',\''+output.rowId+'\')" class="ash_gradient" /><input type="submit" value="Decline" class="ash_gradient" onclick="acceptOrDeclineFrq(\''+output.id+'\',\'decl\',\''+output.rowId+'\')" /></span>'+"</div>";//+'<script>makeMeClickable("#'+output.id+'");</script>';
                    });
                }
                    
                    
            }
            
            //            result += '<div class="menu_bottom"><a href="'+baseLink+'"><span>Show all '+message+'</span></a></div>';
            $(id).html(result);
        }
    });

}
function showMenu(id,itemClass){
    
    $(function() {
        $(itemClass).click(function() {
            
            if ($(itemClass).hasClass("bt")) {
                $("#popGossbag").hide();
                $("#popMessage").hide();
                $("#popFriend_Request").hide();
                $("#popSettings").hide();
                $(".menu1").addClass("bt").removeClass("clicked");
                $(".menu2").addClass("bt").removeClass("clicked");
                $(".menu3").addClass("bt").removeClass("clicked");
                $(".menu4").addClass("bt").removeClass("clicked");
                
                $(itemClass).removeClass("bt").addClass("clicked");
                $(id).show();
            
            } else {
                $(itemClass).removeClass("clicked").addClass("bt")
                $(id).hide();
            }
        });
    // alert("id= "+id);
    });
}
function getConversationUpdate(contactId){
    if(contactId==0){
        $(".content").scrollTop(43535);
        return;
    }
    
    $.ajax({
        url: 'exec.php',
        data: {
            action: "conver",
            inbox: contactId
        },
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output){
                
                if(output.status=="success"){
                    var result = "";
                    $.each(output.data, function(i,output){
                        result += "<div class='post' id='" +output.id+ "'><img class='profile_small' src='" +output.img+ "'/><p class='name'><a href='#'>" +output.name +"</a></p><p class='status'>"+output.text+"</p><p class='time' id='inb_conv_tc"+output.id+"'>"+output.time+"</p></div>"+'<script>setTimeout(timeUpdate,20000,\''+output.rawTime+'\',\'inb_conv_tc'+output.id+'\');</script>';
                    });
                    $("#message").html($("#message").html()+result);
                    $(".content").scrollTop(43535);
                }
            }
        
        }
    });
    setTimeout(getConversationUpdate, 15000,contactId);
}
function editInfo(id){
    if(id=="pinfo"){
        if($("#"+id).hasClass("ui-icon-pencil")){
            $("#"+id).removeClass("ui-icon-pencil");
            $("#"+id).addClass("ui-icon-check");
        }else{
            if($("#"+id).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request", "messenger","info");
                return;
            }else{
                $("#"+id).removeClass("ui-icon-check");
                $("#"+id).addClass("ui-icon-clock");
            }
            var rel = $("#editRelationship").val();
            var phone = $("#editPhone").val();
            var url = $("#editUrl").val();
            $.ajax({
                url: 'exec.php',  
                data: {
                    action: 'update',  
                    phn: phone,
                    urls:url,
                    rels:rel
                }, 
                cache: false, 
                dataType: "json", 
                type: "post",
                success: function(output) {
                    if (output){ // fix strange ie bug
                        showFlashMessageDialoge(output.status, "messenger","info");
                    }
                    $("#"+id).removeClass("ui-icon-clock");
                    $("#"+id).addClass("ui-icon-pencil");
                    
                    $("#relationship").html(rel);
                    $("#phone").html(phone);
                    $("#url").html(url);
                //                    var anchor = 
                }
            });
            return;
        }
        var prev = $("#relationship").html();
        $("#relationship").html("<select name = 'relationship' id='editRelationship'><option value='Single'>Single</option><option value='In a relationship'>In a relationship</option><option value='Married'>Married</option><option value='Divorced'>Divorced</option><option value='Its Complicated'>Its Complicated</option></select>");
        prev = $("#phone").html();
        $("#phone").html("<input type='text' name = 'relationship' value='"+prev+"' id='editPhone' />");
        prev = $("#url").html();
        $("#url").html("<input type='text' name = 'relationship' value='"+prev+"' id='editUrl' />");
    
    }else if(id=="plike"){
        
        if($("#"+id).hasClass("ui-icon-pencil")){
            $("#"+id).removeClass("ui-icon-pencil");
            $("#"+id).addClass("ui-icon-check");
        }else{
            if($("#"+id).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request", "messenger","info");
                return;
            }else{
                $("#"+id).removeClass("ui-icon-check");
                $("#"+id).addClass("ui-icon-clock");
            }
            var likes = $("#likes").val();
            
            $.ajax({
                url: 'exec.php',  
                data: {
                    action: 'update',  
                    likes: likes
                }, 
                cache: false,
                dataType: "json",
                type: "post",
                success: function(output) {
                    if (output){ // fix strange ie bug
                        showFlashMessageDialoge(output.status, "messenger","info");
                    }
                    $("#"+id).removeClass("ui-icon-clock");
                    $("#"+id).addClass("ui-icon-pencil");
                    
                    
                    $("#editlikes").html(likes);
                }
            });
            return;
        }
        var likes = $("#editlikes").html();
        $("#editlikes").html("<textarea id='likes' cols='50' rows='5'>"+likes+"</textarea>");
    }else if(id=="pdislikes"){
        if($("#"+id).hasClass("ui-icon-pencil")){
            $("#"+id).removeClass("ui-icon-pencil");
            $("#"+id).addClass("ui-icon-check");
        }else{
            if($("#"+id).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request", "messenger","info");
                return;
            }else{
                $("#"+id).removeClass("ui-icon-check");
                $("#"+id).addClass("ui-icon-clock");
            }
            var dislikes = $("#dislikes").val();
            
            $.ajax({
                url: 'exec.php',  
                data: {
                    action: 'update',  
                    dislikes: dislikes
                }, 
                cache: false,
                dataType: "json",
                type: "post",
                success: function(output) {
                    if (output){ // fix strange ie bug
                        showFlashMessageDialoge(output.status, "messenger","info");
                    }
                    $("#"+id).removeClass("ui-icon-clock");
                    $("#"+id).addClass("ui-icon-pencil");
                    
                    
                    $("#editdislikes").html(dislikes);
                }
            });
            return;
        }
        var dislikes = $("#editdislikes").html();
        $("#editdislikes").html("<textarea id='dislikes' cols='50' rows='5'>"+dislikes+"</textarea>");
    }else if(id=="plocate"){
        if($("#"+id).hasClass("ui-icon-pencil")){
            $("#"+id).removeClass("ui-icon-pencil");
            $("#"+id).addClass("ui-icon-check");
        }else{
            if($("#"+id).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request", "messenger","info");
                return;
            }else{
                $("#"+id).removeClass("ui-icon-check");
                $("#"+id).addClass("ui-icon-clock");
            }
            var location = $("#location").val();
            if(location==""){
                showFlashMessageDialoge("No changes were made", "messenger","info");
                return;
            }
            $.ajax({
                url: 'exec.php',  
                data: {
                    action: 'update',  
                    location: location
                }, 
                cache: false, 
                dataType: "json", 
                type: "post",
                success: function(output) {
                    if (output){ // fix strange ie bug
                        showFlashMessageDialoge(output.status, "messenger","info");
                    }
                    $("#"+id).removeClass("ui-icon-clock");
                    $("#"+id).addClass("ui-icon-pencil");
                    
                    $("#editlocation").html(location);
                    
                }
            });
            return;
        }
        function dfn(selected){
            $("#location").val(selected);
            return;
        }
        
        $("#editlocation").html('<input type="text" id="location" size=50 value="'+$("#editlocation").html()+'"/>');
        var cache = {},
        lastXhr;
        $( "#location" ).autocomplete({
            source: function( request, response ) {
                var term = request.term;
                if ( term in cache ) {
                    response( $.map( cache[ term ].geonames, function( item ) {
                        return {
                            label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                            value: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName
                        }
                    }));
                    return;
                }
                $.ajax({
                    url: "http://api.geonames.org/searchJSON",
                    dataType: "jsonp",
                    data: {
                        
                        style: "full",
                        maxRows: 10,
                        username: 'soladnet',
                        name_startsWith: term
                    },
                    success: function( data ) {
                        cache[ term ] = data;
                        response( $.map( data.geonames, function( item ) {
                            return {
                                label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                                value: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                dfn(ui.item.label);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });
    }else if(id=="pbio"){
        if($("#"+id).hasClass("ui-icon-pencil")){
            $("#"+id).removeClass("ui-icon-pencil");
            $("#"+id).addClass("ui-icon-check");
        }else{
            if($("#"+id).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request", "messenger","info");
                return;
            }else{
                $("#"+id).removeClass("ui-icon-check");
                $("#"+id).addClass("ui-icon-clock");
            }
            var bio = $("#biography").val();
            
            $.ajax({
                url: 'exec.php',  
                data: {
                    action: 'update',  
                    bio: bio
                }, 
                cache: false,
                dataType: "json",
                type: "post",
                success: function(output) {
                    if (output){ // fix strange ie bug
                        showFlashMessageDialoge(output.status, "messenger","info");
                    }
                    $("#"+id).removeClass("ui-icon-clock");
                    $("#"+id).addClass("ui-icon-pencil");
                    
                    
                    $("#editbio").html(bio);
                }
            });
            return;
        }
        var bio = $("#editbio").html();
        $("#editbio").html("<textarea id='biography' cols='50' rows='5'>"+bio+"</textarea>");
    }else if(id=="pqoute"){
        if($("#"+id).hasClass("ui-icon-pencil")){
            $("#"+id).removeClass("ui-icon-pencil");
            $("#"+id).addClass("ui-icon-check");
        }else{
            if($("#"+id).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request", "messenger","info");
                return;
            }else{
                $("#"+id).removeClass("ui-icon-check");
                $("#"+id).addClass("ui-icon-clock");
            }
            var quote = $("#favoriteqoute").val();
            
            $.ajax({
                url: 'exec.php',  
                data: {
                    action: 'update',  
                    quote: quote
                }, 
                cache: false,
                dataType: "json",
                type: "post",
                success: function(output) {
                    if (output){ // fix strange ie bug
                        showFlashMessageDialoge(output.status, "messenger","info");
                    }
                    $("#"+id).removeClass("ui-icon-clock");
                    $("#"+id).addClass("ui-icon-pencil");
                    $("#editQuote").html(quote);
                }
            });
            return;
        }
        var quote = $("#editQuote").html();
        $("#editQuote").html("<textarea id='favoriteqoute' cols='50' rows='5'>"+quote+"</textarea>");
    }else if(id=="pcommunity"){
        if($("#"+id).hasClass("ui-icon-pencil")){
            $("#"+id).removeClass("ui-icon-pencil");
            $("#"+id).addClass("ui-icon-check");
        }else{
            if($("#"+id).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request", "messenger","info");
                return;
            }else{
                $("#"+id).removeClass("ui-icon-check");
                $("#"+id).addClass("ui-icon-clock");
            }
            var community = $.trim($("#com").val());
            var comcat = $("#comcat").val();
            if($.trim(community)==""){
                showFlashMessageDialoge("No changes were made", "messenger","info");
                return;
            }
            $.ajax({
                url: 'exec.php',  
                data: {
                    action: 'update',  
                    com: community,
                    comcat: comcat
                }, 
                cache: false,
                dataType: "json",
                type: "post",
                success: function(output) {
                    if (output){ // fix strange ie bug
                        showFlashMessageDialoge(output.status, "messenger","info");
                    }
                    $("#"+id).removeClass("ui-icon-clock");
                    $("#"+id).addClass("ui-icon-pencil");
                    $("#editcommunity").html(community);
                    $("#editcomcat").html("");
                }
            });
            return;
        }
        var community = $("#editcommunity").html();
        $("#editcomcat").html('<select id="comcat"><option value="adcollege">Campus / College</option><option value="adcity">City</option></select>');
        $("#editcommunity").html("<input  id='com' onkeydown='autoComplete(\"com\")' type='text' value='"+community+"' size=50 />");
    }else if(id=="pexperience"){
        alert('not supported');
    }else if(id=="pentertainment"){
        alert('not supported');
    }
}
function getAlbum(id,updateHtml){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: id,  
            album: ''
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            var result = "";
            var count = 0;
            $.each(output, function(i,output){
                if(output!=""){
                    result += "<option value='" +output.id+ "'>" +output.album+ "</option>";
                    if(count==0){
                        if(output.image){
                            displayImages(output.image,"albumImages")                            
                        }

                    }
                }
                count++;
            });
            
            if(output!=""){
                $("#"+updateHtml).html("<select name='album' onchange='getAlbumImages(this.value)'>"+result+"</select><span id='newAlbum'></span><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"create('newAlbum')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Create new Album</span></span>");
            }else{
                $("#"+updateHtml).html("<span id='loading'></span><span id='newAlbum'></span><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"create('newAlbum')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Create new Album</span></span>")
            }
        
        
        }
    });
}
function displayImages(obj,updateHtml){
    $("#"+updateHtml).html("");
    $.each(obj, function(i,obj){
        $("#"+updateHtml).html($("#"+updateHtml).html()+'<li><img src="'+obj.img100100+'" /></li>');
    });
}
function getAlbumImages(id){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: id,  
            album: 'getImg'
        }, 
        cache: false,
        dataType: "json",
        type: "post",
        success: function(output) {
            if (output){ // fix strange ie bug
                displayImages(output,"albumImages");
            }
        }
    });
}
function create(id){
    if(id=="newAlbum"){
        $("#"+id).html("<span id='loading'></span><input type='text' id='newalbm' style='float:right;height:2.3em'/><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"save('newalbm')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Save</span></span><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"emptyElement('newAlbum')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Cancel</span></span>");
    
    }
}
function save(id){
    var alb = $("#"+id).val();
    if(alb==""){
        return;
    }else{
        $("#"+id).attr("disabled", "disabled");
        $("#loading").html("<img src='images/load.gif' />");
    }
    
    $.ajax({
        url: 'exec.php',  
        data: {
            action: '',  
            album: 'new',
            name:alb
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output.status){
                
                var result = "";
                $.each(output, function(i,output){
                    if(output.id){
                        result += "<option value='" +output.id+ "'>" +output.album+ "</option>";
                    }
                });
                emptyElement("newAlbum");
                $("#album").html("<select name='album' onchange='getAlbumImages(this.value)'>"+result+"</select><span id='newAlbum'></span><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"create('newAlbum')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Create new Album</span></span>");
            }else{
                $("#"+id).removeAttr("disabled")
            }
        
        }
    });
}
function emptyElement(id){
    $("#"+id).html("");
}
function showFlashMessageDialoge(message,dialogId,status){
    if(status=="error"){
        $( "#"+dialogId ).removeClass("ui-state-highlight");
        $( "#"+dialogId ).addClass("ui-state-error");
    }else{
        $( "#"+dialogId ).removeClass("ui-state-error");
        $( "#"+dialogId ).addClass("ui-state-highlight");
    }
    $("#flashMsg").html(message);
    //    $( "#"+dialogId ).css("text-align","center").css("width", "240px").css("padding", "0.7em").css("position", "absolute").css("display", "block").css("right", "0").css("bottom", "0").css("background-color", "whiteSmoke").css("cursor","pointer");
    //    $( "#"+dialogId ).position({
    //        of: $( ".left" ),
    //        my: "center bottom",
    //        at: "center bottom",
    //        offset: "none",
    //        collision: "0 0"
    //    });
    $( "#"+dialogId ).show( "scale", [], 500, function(){
        setTimeout(function() {
            $( "#"+dialogId+":visible" ).fadeOut();
        }, 8000 );
    } );
}
function timeUpdate(rawTime,updateHtml){
    
    $.ajax({
        url: 'exec.php',  
        data: {
            action: '',  
            timeUpdate: rawTime
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output){
                $("#"+updateHtml).html(output.time);
            }
            setTimeout(timeUpdate,20000,rawTime, updateHtml);
        }
    });
}
function join(id){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: 'join',  
            join: id
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output){
                if(output.status=="success"){
                    refreshRightPanel("mycommunity");
                    refreshRightPanel("suggestion");
                    $(".comhome").removeClass("addbutton");
                    $(".comhome").removeClass("ui-state-highlight");
                    $(".comhome").html("");
                    $("#hom"+id).addClass("addbutton");
                    $("#hom"+id).addClass("ui-state-highlight");
                    $("#hom"+id).html('<img src="images/icon_home.png" />');
                    $(".panel").html("<p>Unsubscribe | <span>Join</span></p>")
                    $("#pan"+id).html('This is your current community');
                    $("#status_community").html("Share with "+output.comm);
                    showFlashMessageDialoge(output.message,"messenger","info");
                }else{
                    showFlashMessageDialoge(output.message,"messenger","error");
                }
            }
        }
    });
}
function unsubscribe(id){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: 'unsub',  
            unsub: id
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output){
                if(output.status=="success"){
                    refreshRightPanel("mycommunity");
                    refreshRightPanel("suggestion");
                    $("#pan"+id).html('<p><span onclick="subscribe('+id+')">Subscribe</span> | <span onclick="join('+id+')">Join</span></p>');
                    showFlashMessageDialoge(output.message,"messenger","info");
                }else{
                    showFlashMessageDialoge(output.message,"messenger","error");
                }
            }
        }
    });
}
function subscribe(id){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: 'sub',  
            sub: id
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output){
                if(output.status=="success"){
                    refreshRightPanel("mycommunity");
                    refreshRightPanel("suggestion");
                    $("#pan"+id).html('<p><span onclick="unsubscribe('+id+')">Unsubscribe</span> | <span onclick="join('+id+')">Join</span></p>');
                    showFlashMessageDialoge(output.message,"messenger","info");
                }else{
                    showFlashMessageDialoge(output.message,"messenger","error");
                }
            }
        }
    });
}
function refreshRightPanel(section){
    $.ajax({
        url: 'getRightPanel.php',  
        data: {
            value: section
        }, 
        cache: false, 
        type: "post",
        success: function(output) {
            if(output){
                $("#"+section).html(output);
            }else{
                $("#"+section).html("");
            }
        }
    });
    
}
function makeMeClickable(id){
    $(id).click(function(){
        window.location=$(this).find("a").attr("href"); 
        return false;
    });
}

function makeProfilePix(imgId){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: '',  
            ppix: imgId
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output){
                if(output.status=="success"){
                    showFlashMessageDialoge(output.message,"messenger","info");
                }else{
                    showFlashMessageDialoge(output.message,"messenger","error");
                }
            }
        }
    });
}
function positionMenu(imgId,menu){
    $("#"+menu).html("<span onclick='makeProfilePix("+imgId+")'>Make profile pix</span>");
    $( "#"+menu ).css("text-align","center").css("font-size",".9em").css("width","105").css("position", "absolute").css("display", "block").css("right", "0").css("bottom", "0").css("background-color", "whiteSmoke").css("cursor","pointer");
    $( "#"+menu ).position({
        of: $( "#img"+imgId ),
        my: "right bottom",
        at: "right bottom",
        offset: "none",
        collision: "0 0"
    });
    
}
function hideMenu(id){
    
    $( "#"+id ).hide( "fold", {}, 5000, $(function(){}) );
}
function showMessageModelDialog(id,title,cid){
    $("#"+id).html("Message: <textarea cols='30' id='dialogtext'></textarea>");
    
    $("#"+id).dialog({
        autoOpen: true,
        modal: true,
        show: "",
        title: title,
        minHeight: 50,
        resizable: false,
        draggable: true,
        buttons: {
            Send: function() {
                //                $( this ).dialog( "close" );
                var msg = $.trim($("#dialogtext").val());
                if(msg==""){
                    $( this ).dialog( "close" );
                    showFlashMessageDialoge("No message was sent.","messenger","error");
                    return;
                }else{
                    sendComment(msg, "commentConver", "", "#dialogtext", cid);
                    $( this ).dialog( "close" );
                }
                
            },
            Close: function() {
                $( this ).dialog( "close" );
            }
                        
        },
        open: function() {
            $("#dialogtext").focus();
        },
        close: function() {
        //            $form[ 0 ].reset();
        }
    });
}
function showGossoutModeldialog(dialodId,postId){
    $("#"+dialodId).html('<input type="checkbox" id="gcheck" checked="checked" value="'+postId+'" /><label for="gcheck" class="width_95">Share with my Gossout Communities</label><!--<input type="checkbox" id="fbcheck"  /><label for="fbcheck" class="width_95">Share with Facebook friends</label>--><script>$(function() {$( "#fbcheck" ).button();$( "#gcheck" ).button();});</script>');
    $("#"+dialodId).dialog({
        autoOpen: true,
        modal: true,
        show: "",
        title: "Gossout Option",
        minHeight: 50,
        resizable: false,
        draggable: true,
        buttons: {
            Gossout: function() {
                if($("#gcheck").attr('checked')?true:false){
                    var gossout = $("#gcheck").val();
                    $.ajax({
                        url: 'exec.php',  
                        data: {
                            action: '',  
                            gossout: gossout
                        }, 
                        cache: false, 
                        dataType: "json", 
                        type: "post",
                        success: function(output) {
                            if(output){
                                if(output.status=="success"){
                                    var result = '<div class="post" id=' + output.id + '><img class="profile_small"src="' +output.imgL+ '"/><p class="name"><a href="page.php?view=profile&uid='+output.sender_id+'">' +output.name+ '</a></p><p class="status">' +output.text+ '</p><p class="time" id="tp'+output.id+'">' +output.time+ '</p><div class="post_activities"> <span>Gossout</span> . <span onclick="showCommentBox(\'box'+output.id+'\',\''+output.id+'\',\''+output.imgS+'\')">comment</span> . <span><a href="page.php?view=community&com='+output.com_id+'">in '+output.com+'</a></span></div><script>setTimeout(timeUpdate,20000,\''+output.rawTime+'\',\'tp'+output.id+'\')</script><span id="comments' +output.id+ '"></span><span id="box'+output.id+'"></span></div>';
                                    $(".posts").html(result+$(".posts").html());
                                    $( "#status" ).animate({
                                        height:30
                                    }, 1000 );
                                    $( "#"+dialodId ).dialog( "close" );
                                    showFlashMessageDialoge(output.message,"messenger","info");
                                }else{
                                    $("#"+dialodId ).dialog( "close" );
                                    showFlashMessageDialoge(output.message,"messenger","error");
                                }
                            }
                        }
                    });
                }
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
                        
        },
        open: function() {
            $("#dialogtext").focus();
        },
        close: function() {
        //            $form[ 0 ].reset();
        }
    });
}
function sendFriendRequest(userid){
    var val = $("#status_"+userid).html();
    if(val=="Send Friend Request"){
        $("#status_"+userid).html("Sending Request");
        $.ajax({
            url: 'exec.php',  
            data: {
                action: '',  
                frq: userid
            }, 
            cache: false, 
            dataType: "json", 
            type: "post",
            success: function(output) {
                if(output){
                    if(output.status=="success"){
                        showFlashMessageDialoge(output.message,"messenger","info");
                        $("#status_"+userid).html("Request Sent!");
                        $( "#p_"+userid ).hide( "drop", {}, 1000);
                    }else{
                        $( "#p_"+userid ).hide( "drop", {}, 1000);
                        showFlashMessageDialoge(output.message,"messenger","error");
                    }
                }
            }
        });
        
    }
    
    
}
function acceptOrDeclineFrq(id,action,key){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: action,  
            acceptfrq: id,
            key:key
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output){
                if(output.status=="success"){
                    showFlashMessageDialoge(output.message,"messenger","info");
                    $("#requestoption"+id).html("<a href='page.php?view=profile&uid="+id+"'>View Profile</a>");
                }else{
                    $( "#p_"+userid ).hide( "drop", {}, 1000);
                    showFlashMessageDialoge(output.message,"messenger","error");
                }
            }
        }
    });
}
function tweakwink(uid,type){
    $.ajax({
        url: 'exec.php',  
        data: {
            action: uid,  
            tweakwink: type
        }, 
        cache: false, 
        dataType: "json", 
        type: "post",
        success: function(output) {
            if(output){
                if(output.status=="success"){
                    showFlashMessageDialoge(output.message,"messenger","info");
                    if(type=="T"){
                        $("#tweak").attr("disabled", "disabled");
                    }else{
                        $("#wink").attr("disabled", "disabled");
                    }
                    
                }else{
                    showFlashMessageDialoge(output.message,"messenger","error");
                }
            }
        }
    });
}
function ajaxFileUpload(str,source){
    //    $("#share_loading").ajaxStart(function(){
    //        $(this).html("<img src='images/load.gif' />");
    //    }).ajaxComplete(function(){
    //        $(this).html("");
    //    });
    $.ajaxFileUpload({
        url:'do_ajaxfileupload_post.php',
        secureuri:false,
        fileElementId:'fileToUpload',
        dataType: 'json',
        data:{
            posts:str
        },
        success: function (data, status){
            if(typeof(data.status) != 'undefined'){
                if(data.status == "success"){
                    $("#status_community").css("display", "none");
                    $(source).removeAttr('disabled');
                    $(source).val("");
                    $("#share_loading").html('');
                    $(source).removeClass("sending");
                    var result = '<div class="post" id=' + data.id + '><img class="profile_small"src="' +data.imgL+ '"/><p class="name"><a href="page.php?view=profile&uid='+data.sender_id+'">' +data.name+ '</a></p><p class="status">' +data.text+ '</p><ul class="box"><li><img src="'+data.imgStatus+'" /></li></ul><p class="time" id="tp'+data.id+'">' +data.time+ '</p><div class="post_activities"> <span onclick="showGossoutModeldialog(\'dialog\',\'' + data.id + '\');">Gossout</span> . <span onclick="showCommentBox(\'box'+data.id+'\',\''+data.id+'\',\''+data.imgS+'\')">comment</span> . <span><a href="page.php?view=community&com='+data.com_id+'">in '+data.com+'</a></span></div><script>setTimeout(timeUpdate,20000,\''+data.rawTime+'\',\'tp'+data.id+'\')</script><span id="comments' +data.id+ '"></span><span id="box'+data.id+'"></span></div>';
                    $(".posts").html(result+$(".posts").html());
                    $( "#status" ).animate({
                        height:30
                    }, 1000 );
                }else{
                    $(source).removeClass("sending");
                    $(source).removeAttr('disabled');
                    $("#share_loading").html('');
                    showFlashMessageDialoge(data.message,"messenger","error");
                }
            }
        },
        error: function (data, status, e)
        {
            showFlashMessageDialoge(e,"messenger","error");
        }
    }
    );
        $("#statusUpdate").reset();
		
//    return false;

}
function refreshCommunityChat(){
    
}