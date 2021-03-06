function parseURLParams(a,b){
    var c=a.indexOf("?")+1;
    var d=a.indexOf("#")+1||a.length+1;
    var e=a.slice(c,d-1);
    if(e===a||e==="")return;
    var f={};
    
    var g=e.replace(/\+/g," ").split("&");
    for(var h=0;h<g.length;h++){
        var i=g[h].split("=");
        var j=decodeURIComponent(i[0]);
        var k=decodeURIComponent(i[1]);
        if(!(j in f)){
            f[j]=[]
        }
        f[j].push(i.length===2?k:null)
    }
    t=(f.view+"").substring(0,1).toUpperCase()+(f.view+"").substring(1,(f.view+"").length);
    if(b>0){
        document.title=t+" ( "+b+" )"
    }else{
        document.title=t
    }
}

function getValue(a,b){
    if($(a).hasClass("sending")){
        return
    }else{
        $(a).addClass("sending")
    }
    if(b=="posts"){
        var c=$.trim($(a).val());
        if($("#fileToUpload").val()==""){
            if(c==""){
                return
            }else{
                if(c.length>160){
                    showFlashMessageDialoge("Text too long. Please summarize to 160 characters","messenger","error");
                    $(a).removeClass("sending");
                }else{
                    $("#share_loading").html('<img src="images/load.gif" />');
                    $(a).attr("disabled","disabled");
                    sendData(c,b,".posts",a)
                }
            }
        }else{
            if(c.length>160){
                showFlashMessageDialoge("Text too long. Please summarize to 160 characters","messenger","error");
                $(a).removeClass("sending");
            }else{
                $(a).attr("disabled","disabled");
                $("#share_loading").html('<img src="images/load.gif" />');
                ajaxFileUpload(c,a)
            }
        }
    }else if(b=="commentsPost"){
        var d=$.trim($("#c"+a).val());
        if(d==""){
            return
        }
        $("#c"+a).attr("disabled","disabled").addClass("sending");
        $("#c_loading"+a).html("<img src='images/load.gif' />");
        sendComment(d,b,"#comments"+a,"#c"+a,a);
        $("#c"+a).removeAttr("disabled")
    }else if(b=="commentConver"){
        var e=$.trim($("#m"+a).val());
        if(e==""){
            return
        }
        $("#m"+a).attr("disabled","disabled");
        $("#m"+a).addClass("sending");
        $("#conver_loading"+a).html("<img src='images/load.gif' />");
        sendComment(e,b,"#message","#m"+a,a);
        $("#m"+a).removeAttr("disabled")
    }
}
function sendData(a,b,c,d){
    $.ajax({
        url:"exec.php",
        data:{
            action:b,
            posts:a,
            status:$("#post_status").attr('checked')?true:false
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            if(b=="posts"){
                if(a){
                    if(a.status=="success"){
                        var e='<div class="post" id='+a.id+'><img class="profile_small"src="'+a.imgL+'"/><p class="name"><a href="page.php?view=profile&uid='+a.sender_id+'">'+a.name+'</a></p><p class="status">'+a.text+'</p><p class="time" id="tp'+a.id+'">'+a.time+"</p><div class=\"post_activities\"> <span onclick=\"showGossoutModeldialog('dialog','"+a.id+"');\">Gossout</span> . <span onclick=\"showCommentBox('box"+a.id+"','"+a.id+"','"+a.imgS+'\')">comment</span> . <span><a href="page.php?view=community&com='+a.com_id+'">in '+a.com+"</a></span></div><script>setTimeout(timeUpdate,20000,'"+a.rawTime+"','tp"+a.id+"')</script><span id=\"comments"+a.id+'"></span><span id="box'+a.id+'"></span></div>';
                        $(c).html(e+$(c).html());
                        $("#status").animate({
                            height:30
                        },1e3);
                        $("#status_community").css("display","none");
                        $(d).removeAttr("disabled");
                        $(d).val("");
                        $(d).removeClass("sending");
                        showFlashMessageDialoge(a.message,"messenger","info")
                    //                        setTimeout("window.location.reload(true)",1000);
                    }else if(a.status=="failed"){
                        $(d).removeAttr("disabled");
                        showFlashMessageDialoge(a.message,"messenger","error")
                    }
                }
                $("#share_loading").html("")
            }
        }
    })
}
function sendComment(a,b,c,d,e){
    $.ajax({
        url:"exec.php",
        data:{
            action:b,
            posts:a,
            sourceId:e
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            function f(){
                setTimeout(function(){
                    $("shade").removeAttr("style").hide().fadeIn()
                },1e3);
                $(".post").removeClass("shade")
            }
            if(a){
                if(b=="commentsPost"){
                    var g='<div id="comment" class='+a.id+'><img class="profile_small" src="'+a.imgS+'"/><p class="name"><a href="page.php?view=profile&uid='+a.sender_id+'">'+a.name+'</a></p><p class="status">'+a.text+'</p><p class="time" id="tpc'+a.id+'">'+a.time+"</p></div><script>setTimeout(timeUpdate,20000,'"+a.rawTime+"','tpc"+a.id+"')</script>";
                    if(c!=""){
                        $(c).html($(c).html()+g)
                    }
                    $("#c_loading"+e).html("");
                    $("#"+e).removeClass("sending")
                }else if(b=="commentConver"){
                    if(c!=""){
                        g="<div class='post shade' id='inb_conv"+a.id+"'><img class='profile_small' src='"+a.imgL+"'/><p class='name'><a href='#'>"+a.name+"</a></p><p class='status'>"+a.text+"</p><p class='time' id='inb_conv_tc"+a.id+"'>"+a.time+"</p></div><script>setTimeout(timeUpdate,20000,'"+a.rawTime+"','inb_conv_tc"+a.id+"')</script>";
                        $(c).html($(c).html()+g);
                        $(".shade").effect("bounce",{},100,f)
                    }else{
                        showFlashMessageDialoge("Message Sent!","messenger","info")
                    }
                }
                $(d).val("");
                $(d).removeClass("sending")
            }
        }
    })
}
function showCommentBox(a,b,c){
    var d=$("#"+a).html();
    if(d==""){
        $("#"+a).html('<div id="commentbox"><form method="GET" onsubmit="getValue(\''+b+'\',\'commentsPost\');return false"><table class="commentTable"><tr><td class="comment-img-td"><img class="profile_small" src="'+c+'" /></td><td><input class="commenttext" type="text" id="c'+b+'"/></td></tr></table><span id="c_loading'+b+'"></span></form>');
        $("#c"+b).focus()
    }else{
        $("#"+a).html("")
    }
}
function getUpdateCount(){
    $.ajax({
        url:"exec.php",
        data:{
            action:"",
            count:""
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            var b=0;
            if(a){
                if(a.bag>0){
                    $("#gossbag").html("GossBag <sup class='req'>"+a.bag+"</sup>");
                    b=b+a.bag
                }else{
                    $("#gossbag").html("GossBag")
                }
                if(a.msg>0){
                    $("#messages").html("Messages <sup class='req'>"+a.msg+"</sup>");
                    b=b+a.msg
                }else{
                    $("#messages").html("Messages")
                }
                if(a.frq>0){
                    $("#friend_requests").html("Friend Requests <sup class='req'>"+a.frq+"</sup>");
                    b=b+a.frq
                }else{
                    $("#friend_requests").html("Friend Requests")
                }
            }
            parseURLParams(document.URL,b)
        }
    });
    setTimeout(getUpdateCount,10e3)
}
function getInbox(){
    $.ajax({
        url:"exec.php",
        data:{
            action:"Messages",
            flcikr:""
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            var b="";
            $.each(a,function(a,c){
                if(c){
                    var d="";
                    if(c.isUser){
                        d="<img class='profile_small' src='images/reply.png'/>"
                    }else{
                        d=""
                    }
                    var e="";
                    if(c.status=="N"){
                        e=" shade"
                    }else{
                        e=""
                    }
                    b+="<div class='post"+e+"' id='"+c.msgid+"'><img class='profile_small' src='"+c.img+"'/>"+d+"<p class='name'><a href='page.php?view=Messages&open="+c.id+"'>"+c.name+"</a></p><p class='status'>"+c.text+"</p><p class='time' id='tim"+c.msgid+"'>"+c.time+"</p></div><script>setTimeout(timeUpdate,20000,'"+ouput.rawTime+"','tim"+c.msgid+"')<script>"
                }
            });
            b+="";
            $("#inbox").html(b)
        }
    });
    setTimeout(getInbox,3e4)
}
function getUpdateFlicker(a,b){
    if($(a).hasClass("clicked")){
        $(a).removeClass("clicked");
        return
    }
    var c;
    c="page.php?view="+b;
    $.ajax({
        url:"exec.php",
        data:{
            action:b,
            flcikr:""
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(c){
            $(a).addClass("clicked");
            var d="";
            if(c.status=="success"){
                if(b=="GossBag"){
                    $("#gossbagloading").remove();
                    $.each(c.data,function(a,b){
                        if(b.id){
                            if(b.infoType=="gb"){
                                d+="<div class='post' id='"+b.id+"'><img class='profile_small' src='"+b.img+"'/><p class='name'><a href='page.php?view=notification&open="+b.post_id+"'>"+b.fullname+"</a></p><p class='status'>"+b.caption+"</p><div class='post_activities'>"+b.sTime+"</div></div><script>makeMeClickable("+b.id+")</script>"
                            }else{
                                d+="<div class='post' id='tw_f"+b.id+"'><img class='profile_small' src='"+b.img+"'/><p class='name'><a href='page.php?view=tweakwink&open="+b.id+"'>"+b.fullname+"</a></p><p class='status'>"+b.caption+"</p><div class='post_activities'>"+b.sTime+"</div></div>"
                            }
                        }
                    })
                }else if(b=="Messages"){
                    $("#messagesloading").remove();
                    $.each(c,function(a,c){
                        if(c.msgid){
                            var e="";
                            if(c.isUser){
                                e="<img class='profile_small' src='images/reply.png'/>"
                            }else{
                                e=""
                            }
                            var f="";
                            if(c.status=="N"){
                                f=" shade"
                            }else{
                                f=""
                            }
                            d+="<div class='post"+f+"' id='"+c.msgid+"'><img class='profile_small' src='"+c.img+"'/>"+e+"<p class='name'><a href='page.php?view="+b+"&open="+c.id+"'>"+c.name+"</a></p><p class='status'>"+c.text+"</p><div class='post_activities'>"+c.time+"</div></div>"+'<script>makeMeClickable("#'+c.msgid+'");</script>'
                        }
                    })
                }else if(b=="Friend Requests"){
                    $("#friendreqloading").remove();
                    $.each(c,function(a,c){
                        if(c.id){
                            d+="<div class='post' id='frq"+c.id+"'><img class='profile_small' src='"+c.img+"'/><p class='name'><a href='page.php?view="+b+"&open="+c.id+"'>"+c.fullname+"</a></p><p class='status'>"+c.caption+"</p><p class='time'>"+c.time+"</p>"+'<span id="requestoption'+c.id+'"><input type="submit" value="Accept" onclick="acceptOrDeclineFrq(\''+c.id+"','acpt','"+c.rowId+'\')" class="ash_gradient" /><input type="submit" value="Decline" class="ash_gradient" onclick="acceptOrDeclineFrq(\''+c.id+"','decl','"+c.rowId+"')\" /></span>"+"</div>"
                        }
                    })
                }
            }else{
                if(c.status=="failed"){
                    d="<div class='post'><p class='name'>Notification</p><p class='status'>Your bag is empty</p></div>"
                }
            }
            $(a).html(d)
        }
    })
}
function showMenu(a,b){
    $(function(){
        $(b).click(function(){
            if($(b).hasClass("bt")){
                $("#popGossbag").hide();
                $("#popMessage").hide();
                $("#popFriend_Request").hide();
                $("#popSettings").hide();
                $(".menu1").addClass("bt").removeClass("clicked");
                $(".menu2").addClass("bt").removeClass("clicked");
                $(".menu3").addClass("bt").removeClass("clicked");
                $(".menu4").addClass("bt").removeClass("clicked");
                $(b).removeClass("bt").addClass("clicked");
                $(a).show()
            }else{
                $(b).removeClass("clicked").addClass("bt");
                $(a).hide()
            }
        })
    })
}
function getConversationUpdate(a){
    if(a==0){
        $(".content").scrollTop(43535);
        return
    }
    $.ajax({
        url:"exec.php",
        data:{
            action:"conver",
            inbox:a
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            if(a){
                if(a.status=="success"){
                    var b="";
                    $.each(a.data,function(a,c){
                        b+="<div class='post' id='"+c.id+"'><img class='profile_small' src='"+c.img+"'/><p class='name'><a href='#'>"+c.name+"</a></p><p class='status'>"+c.text+"</p><p class='time' id='inb_conv_tc"+c.id+"'>"+c.time+"</p></div>"+"<script>setTimeout(timeUpdate,20000,'"+c.rawTime+"','inb_conv_tc"+c.id+"');</script>"
                    });
                    $("#message").html($("#message").html()+b);
                    $(".content").scrollTop(43535)
                }
            }
        }
    });
    setTimeout(getConversationUpdate,15e3,a)
}
function editInfo(a){
    if(a=="pinfo"){
        if($("#"+a).hasClass("ui-icon-pencil")){
            $("#"+a).removeClass("ui-icon-pencil");
            $("#"+a).addClass("ui-icon-check")
        }else{
            if($("#"+a).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request","messenger","info");
                return
            }else{
                $("#"+a).removeClass("ui-icon-check");
                $("#"+a).addClass("ui-icon-clock")
            }
            var b=$("#editRelationship").val();
            var c=$("#editPhone").val();
            var d=$("#editUrl").val();
            $.ajax({
                url:"exec.php",
                data:{
                    action:"update",
                    phn:c,
                    urls:d,
                    rels:b
                },
                cache:false,
                dataType:"json",
                type:"post",
                success:function(e){
                    if(e){
                        showFlashMessageDialoge(e.status,"messenger","info")
                    }
                    $("#"+a).removeClass("ui-icon-clock");
                    $("#"+a).addClass("ui-icon-pencil");
                    $("#relationship").html(b);
                    $("#phone").html(c);
                    $("#url").html(d)
                }
            });
            return
        }
        var e=$("#relationship").html();
        $("#relationship").html("<select name = 'relationship' id='editRelationship'><option value='Single'>Single</option><option value='In a relationship'>In a relationship</option><option value='Married'>Married</option><option value='Divorced'>Divorced</option><option value='Its Complicated'>Its Complicated</option></select>");
        e=$("#phone").html();
        $("#phone").html("<input type='text' name = 'relationship' value='"+e+"' id='editPhone' />");
        e=$("#url").html();
        $("#url").html("<input type='text' name = 'relationship' value='"+e+"' id='editUrl' />")
    }else if(a=="plike"){
        if($("#"+a).hasClass("ui-icon-pencil")){
            $("#"+a).removeClass("ui-icon-pencil");
            $("#"+a).addClass("ui-icon-check")
        }else{
            if($("#"+a).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request","messenger","info");
                return
            }else{
                $("#"+a).removeClass("ui-icon-check");
                $("#"+a).addClass("ui-icon-clock")
            }
            var f=$("#likes").val();
            $.ajax({
                url:"exec.php",
                data:{
                    action:"update",
                    likes:f
                },
                cache:false,
                dataType:"json",
                type:"post",
                success:function(b){
                    if(b){
                        showFlashMessageDialoge(b.status,"messenger","info")
                    }
                    $("#"+a).removeClass("ui-icon-clock");
                    $("#"+a).addClass("ui-icon-pencil");
                    $("#editlikes").html(f)
                }
            });
            return
        }
        var f=$("#editlikes").html();
        $("#editlikes").html("<textarea id='likes' cols='50' rows='5'>"+f+"</textarea>")
    }else if(a=="pdislikes"){
        if($("#"+a).hasClass("ui-icon-pencil")){
            $("#"+a).removeClass("ui-icon-pencil");
            $("#"+a).addClass("ui-icon-check")
        }else{
            if($("#"+a).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request","messenger","info");
                return
            }else{
                $("#"+a).removeClass("ui-icon-check");
                $("#"+a).addClass("ui-icon-clock")
            }
            var g=$("#dislikes").val();
            $.ajax({
                url:"exec.php",
                data:{
                    action:"update",
                    dislikes:g
                },
                cache:false,
                dataType:"json",
                type:"post",
                success:function(b){
                    if(b){
                        showFlashMessageDialoge(b.status,"messenger","info")
                    }
                    $("#"+a).removeClass("ui-icon-clock");
                    $("#"+a).addClass("ui-icon-pencil");
                    $("#editdislikes").html(g)
                }
            });
            return
        }
        var g=$("#editdislikes").html();
        $("#editdislikes").html("<textarea id='dislikes' cols='50' rows='5'>"+g+"</textarea>")
    }else if(a=="plocate"){
        if($("#"+a).hasClass("ui-icon-pencil")){
            $("#"+a).removeClass("ui-icon-pencil");
            $("#"+a).addClass("ui-icon-check")
        }else{
            if($("#"+a).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request","messenger","info");
                return
            }else{
                $("#"+a).removeClass("ui-icon-check");
                $("#"+a).addClass("ui-icon-clock")
            }
            var h=$("#location").val();
            if(h==""){
                showFlashMessageDialoge("No changes were made","messenger","info");
                return
            }
            $.ajax({
                url:"exec.php",
                data:{
                    action:"update",
                    location:h
                },
                cache:false,
                dataType:"json",
                type:"post",
                success:function(b){
                    if(b){
                        showFlashMessageDialoge(b.status,"messenger","info")
                    }
                    $("#"+a).removeClass("ui-icon-clock");
                    $("#"+a).addClass("ui-icon-pencil");
                    $("#editlocation").html(h)
                }
            });
            return
        }
        function i(a){
            $("#location").val(a);
            return
        }
        $("#editlocation").html('<input type="text" id="location" size=50 value="'+$("#editlocation").html()+'"/><span id="location_loading"></span>');
        var j={},k;
        $("#location").autocomplete({
            source:function(a,b){
                var c=a.term;
                $("#location_loading").html("<img src='images/load.gif' />");
                if(c in j){
                    b($.map(j[c].geonames,function(a){
                        return{
                            label:a.name+(a.adminName1?", "+a.adminName1:"")+", "+a.countryName,
                            value:a.name+(a.adminName1?", "+a.adminName1:"")+", "+a.countryName
                        }
                    }));
                    return
                }
                $.ajax({
                    url:"http://api.geonames.org/searchJSON",
                    dataType:"jsonp",
                    data:{
                        style:"full",
                        maxRows:10,
                        username:"soladnet",
                        name_startsWith:c
                    },
                    success:function(a){
                        j[c]=a;
                        $("#location_loading").html("");
                        b($.map(a.geonames,function(a){
                            return{
                                label:a.name+(a.adminName1?", "+a.adminName1:"")+", "+a.countryName,
                                value:a.name+(a.adminName1?", "+a.adminName1:"")+", "+a.countryName
                            }
                        }))
                    }
                })
            },
            minLength:2,
            select:function(a,b){
                i(b.item.label)
            },
            open:function(){
                $(this).removeClass("ui-corner-all").addClass("ui-corner-top")
            },
            close:function(){
                $(this).removeClass("ui-corner-top").addClass("ui-corner-all")
            }
        })
    }else if(a=="pbio"){
        if($("#"+a).hasClass("ui-icon-pencil")){
            $("#"+a).removeClass("ui-icon-pencil");
            $("#"+a).addClass("ui-icon-check")
        }else{
            if($("#"+a).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request","messenger","info");
                return
            }else{
                $("#"+a).removeClass("ui-icon-check");
                $("#"+a).addClass("ui-icon-clock")
            }
            var l=$("#biography").val();
            $.ajax({
                url:"exec.php",
                data:{
                    action:"update",
                    bio:l
                },
                cache:false,
                dataType:"json",
                type:"post",
                success:function(b){
                    if(b){
                        showFlashMessageDialoge(b.status,"messenger","info")
                    }
                    $("#"+a).removeClass("ui-icon-clock");
                    $("#"+a).addClass("ui-icon-pencil");
                    $("#editbio").html(l)
                }
            });
            return
        }
        var l=$("#editbio").html();
        $("#editbio").html("<textarea id='biography' cols='50' rows='5'>"+l+"</textarea>")
    }else if(a=="pqoute"){
        if($("#"+a).hasClass("ui-icon-pencil")){
            $("#"+a).removeClass("ui-icon-pencil");
            $("#"+a).addClass("ui-icon-check")
        }else{
            if($("#"+a).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request","messenger","info");
                return
            }else{
                $("#"+a).removeClass("ui-icon-check");
                $("#"+a).addClass("ui-icon-clock")
            }
            var m=$("#favoriteqoute").val();
            $.ajax({
                url:"exec.php",
                data:{
                    action:"update",
                    quote:m
                },
                cache:false,
                dataType:"json",
                type:"post",
                success:function(b){
                    if(b){
                        showFlashMessageDialoge(b.status,"messenger","info")
                    }
                    $("#"+a).removeClass("ui-icon-clock");
                    $("#"+a).addClass("ui-icon-pencil");
                    $("#editQuote").html(m)
                }
            });
            return
        }
        var m=$("#editQuote").html();
        $("#editQuote").html("<textarea id='favoriteqoute' cols='50' rows='5'>"+m+"</textarea>")
    }else if(a=="pcommunity"){
        if($("#"+a).hasClass("ui-icon-pencil")){
            $("#"+a).removeClass("ui-icon-pencil");
            $("#"+a).addClass("ui-icon-check")
        }else{
            if($("#"+a).hasClass("ui-icon-clock")){
                showFlashMessageDialoge("Please wait...we're still processing your last request","messenger","info");
                return
            }else{
                $("#"+a).removeClass("ui-icon-check");
                $("#"+a).addClass("ui-icon-clock")
            }
            var n=$.trim($("#com").val());
            var o=$("#comcat").val();
            if($.trim(n)==""){
                showFlashMessageDialoge("No changes were made","messenger","info");
                return
            }
            $.ajax({
                url:"exec.php",
                data:{
                    action:"update",
                    com:n,
                    comcat:o
                },
                cache:false,
                dataType:"json",
                type:"post",
                success:function(b){
                    if(b){
                        showFlashMessageDialoge(b.status,"messenger","info")
                    }
                    $("#"+a).removeClass("ui-icon-clock");
                    $("#"+a).addClass("ui-icon-pencil");
                    $("#editcommunity").html(n);
                    $("#editcomcat").html("")
                }
            });
            return
        }
        var n=$("#editcommunity").html();
        $("#editcomcat").html('<select id="comcat"><option value="adcollege">Campus / College</option><option value="adcity">City</option></select>');
        $("#editcommunity").html("<input  id='com' onkeydown='autoComplete(\"com\")' type='text' value='"+n+"' size=50 />")
    }else if(a=="pexperience"){
        alert("not supported")
    }else if(a=="pentertainment"){
        alert("not supported")
    }
}
function getAlbum(a,b){
    $.ajax({
        url:"exec.php",
        data:{
            action:a,
            album:""
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            var c="";
            var d=0;
            $.each(a,function(a,b){
                if(b!=""){
                    c+="<option value='"+b.id+"'>"+b.album+"</option>";
                    if(d==0){
                        if(b.image){
                            displayImages(b.image,"albumImages")
                        }
                    }
                }
                d++
            });
            if(a!=""){
                $("#"+b).html("<select name='album' onchange='getAlbumImages(this.value)'>"+c+"</select><span id='newAlbum'></span><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"create('newAlbum')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Create new Album</span></span>")
            }else{
                $("#"+b).html("<span id='loading'></span><span id='newAlbum'></span><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"create('newAlbum')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Create new Album</span></span>")
            }
        }
    })
}
function displayImages(a,b){
    $("#"+b).html("");
    $.each(a,function(a,c){
        $("#"+b).html($("#"+b).html()+'<li><img src="'+c.img100100+'" /></li>')
    })
}
function getAlbumImages(a){
    $.ajax({
        url:"exec.php",
        data:{
            action:a,
            album:"getImg"
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            if(a){
                displayImages(a,"albumImages")
            }
        }
    })
}
function create(a){
    if(a=="newAlbum"){
        $("#"+a).html("<span id='loading'></span><input type='text' id='newalbm' style='float:right;height:2.3em'/><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"save('newalbm')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Save</span></span><span class='btn btn-success fileinput-button' style='float:right' ><span onclick=\"emptyElement('newAlbum')\"><span style='float:left' class='ui-icon ui-icon-plusthick'></span>Cancel</span></span>")
    }
}
function save(a){
    var b=$("#"+a).val();
    if(b==""){
        return
    }else{
        $("#"+a).attr("disabled","disabled");
        $("#album_loading").html("<img src='images/load.gif' />")
    }
    $.ajax({
        url:"exec.php",
        data:{
            action:"",
            album:"new",
            name:b
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(b){
            if(b.status){
                window.location.reload(true);
            }else{
                $("#"+a).removeAttr("disabled")
            }
        }
    })
}
function emptyElement(a){
    $("#"+a).html("")
}
function showFlashMessageDialoge(a,b,c){
    if(c=="error"){
        $("#"+b).removeClass("ui-state-highlight");
        $("#"+b).addClass("ui-state-error")
    }else{
        $("#"+b).removeClass("ui-state-error");
        $("#"+b).addClass("ui-state-highlight")
    }
    $("#flashMsg").html(a);
    $("#"+b).show("scale",[],500,function(){
        setTimeout(function(){
            $("#"+b+":visible").fadeOut()
        },8e3)
    })
}
function timeUpdate(a,b){
    $.ajax({
        url:"exec.php",
        data:{
            action:"",
            timeUpdate:a
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(c){
            if(c){
                $("#"+b).html(c.time)
            }
            setTimeout(timeUpdate,2e4,a,b)
        }
    })
}
function join(a){
    $.ajax({
        url:"exec.php",
        data:{
            action:"join",
            join:a
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(b){
            if(b){
                if(b.status=="success"){
                    //                    refreshRightPanel("mycommunity");
                    //                    refreshRightPanel("suggestion");
                    //                    $(".comhome").removeClass("addbutton");
                    //                    $(".comhome").removeClass("ui-state-highlight");
                    //                    $(".comhome").html("");
                    //                    $("#hom"+a).addClass("addbutton");
                    //                    $("#hom"+a).addClass("ui-state-highlight");
                    //                    $("#hom"+a).html('<img src="images/icon_home.png" />');
                    //                    $(".panel").html("<p>Unsubscribe | <span>Join</span></p>");
                    //                    $("#pan"+a).html("This is your current community");
                    //                    $("#status_community").html("Share with "+b.comm);
                    window.location.reload(true);
                    showFlashMessageDialoge(b.message,"messenger","info")
                }else{
                    showFlashMessageDialoge(b.message,"messenger","error")
                }
            }
        }
    })
}
function unsubscribe(a){
    $.ajax({
        url:"exec.php",
        data:{
            action:"unsub",
            unsub:a
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(b){
            if(b){
                if(b.status=="success"){
                    refreshRightPanel("mycommunity");
                    refreshRightPanel("suggestion");
                    $("#pan"+a).html('<p><span onclick="subscribe('+a+')">Subscribe</span> | <span onclick="join('+a+')">Join</span></p>');
                    showFlashMessageDialoge(b.message,"messenger","info")
                }else{
                    showFlashMessageDialoge(b.message,"messenger","error")
                }
            }
        }
    })
}
function subscribe(a){
    $.ajax({
        url:"exec.php",
        data:{
            action:"sub",
            sub:a
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(b){
            if(b){
                if(b.status=="success"){
                    refreshRightPanel("mycommunity");
                    refreshRightPanel("suggestion");
                    $("#pan"+a).html('<p><span onclick="unsubscribe('+a+')">Unsubscribe</span> | <span onclick="join('+a+')">Join</span></p>');
                    showFlashMessageDialoge(b.message,"messenger","info")
                }else{
                    showFlashMessageDialoge(b.message,"messenger","error")
                }
            }
        }
    })
}
function refreshRightPanel(a){
    $.ajax({
        url:"getRightPanel.php",
        data:{
            value:a
        },
        cache:false,
        type:"post",
        success:function(b){
            if(b){
                $("#"+a).html(b)
            }else{
                $("#"+a).html("")
            }
        }
    })
}
function makeMeClickable(a){
    $(a).click(function(){
        window.location=$(this).find("a").attr("href");
        return false
    })
}
function makeProfilePix(a){
    $.ajax({
        url:"exec.php",
        data:{
            action:"",
            ppix:a
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            if(a){
                if(a.status=="success"){
                    showFlashMessageDialoge(a.message,"messenger","info");
                //                    setTimeout("window.location.reload(true)",1000);
                    
                }else{
                    showFlashMessageDialoge(a.message,"messenger","error")
                }
            }
        }
    })
}
function positionMenu(a,b){
    $("#"+b).html("<span onclick='makeProfilePix("+a+")'>Make Profile Pix</span>");
    $("#"+b).css("text-align","center").css("font-size",".9em").css("width","105").css("position","absolute").css("display","block").css("right","0").css("bottom","0").css("background-color","whiteSmoke").css("cursor","pointer");
    $("#"+b).position({
        of:$("#img"+a),
        my:"right bottom",
        at:"right bottom",
        offset:"none",
        collision:"0 0"
    })
}
function hideMenu(a){
    $("#"+a).hide("fold",{},5e3,$(function(){}))
}
function showMessageModelDialog(a,b,c){
    $("#"+a).html("Message: <textarea cols='30' id='dialogtext'></textarea>");
    $("#"+a).dialog({
        autoOpen:true,
        modal:true,
        show:"",
        title:b,
        minHeight:50,
        resizable:false,
        draggable:true,
        buttons:{
            Send:function(){
                var a=$.trim($("#dialogtext").val());
                if(a==""){
                    $(this).dialog("close");
                    showFlashMessageDialoge("No message was sent.","messenger","error");
                    return
                }else{
                    sendComment(a,"commentConver","","#dialogtext",c);
                    $(this).dialog("close")
                }
            },
            Close:function(){
                $(this).dialog("close")
            }
        },
        open:function(){
            $("#dialogtext").focus()
        },
        close:function(){}
    })
}
function showGossoutModeldialog(a,b){
    $("#"+a).html('<input type="checkbox" id="gcheck" value="'+b+'" /><label for="gcheck" class="width_95">Share with my Gossout Communities</label><input type="checkbox" id="fbcheck"  value="'+b+'"/><label for="fbcheck" class="width_95">Share with Facebook friends</label><span id="gossout_loading"></span><script>$(function() {$( "#fbcheck" ).button();$( "#gcheck" ).button();});</script>');
    $("#"+a).dialog({
        autoOpen:true,
        modal:true,
        show:"",
        title:"Gossout Option",
        minHeight:50,
        resizable:false,
        draggable:true,
        buttons:{
            Gossout:function(){
                var a={};
                
                if(($("#gcheck").attr("checked")?true:false)&&($("#fbcheck").attr("checked")?true:false)){
                    var c=$("#gcheck").val();
                    var d=$("#fbcheck").val();
                    a={
                        action:"gossout",
                        gossout:c,
                        facebook:d
                    }
                }else if($("#gcheck").attr("checked")?true:false){
                    c=$("#gcheck").val();
                    a={
                        action:"gossout",
                        gossout:c
                    }
                }else if($("#fbcheck").attr("checked")?true:false){
                    d=$("#fbcheck").val();
                    a={
                        action:"gossout",
                        facebook:d
                    }
                }
                if(a.action){
                    $("#gossout_loading").html("<img src='images/load.gif' />");
                    $.ajax({
                        url:"exec.php",
                        data:a,
                        cache:false,
                        dataType:"json",
                        type:"post",
                        success:function(c){
                            $("#gossout_loading").html("");
                            if(c){
                                if(a.gossout){
                                    if(c.status=="success"){
                                        var d='<div class="post" id='+c.id+'><img class="profile_small"src="'+c.imgL+'"/><p class="name"><a href="page.php?view=profile&uid='+c.sender_id+'">'+c.name+'</a></p><p class="status">'+c.text+'</p><p class="time" id="tp'+c.id+'">'+c.time+'</p><div class="post_activities"> <span onclick=\'showGossoutModeldialog("dialog","'+b+"\")'>Gossout</span> . <span onclick=\"showCommentBox('box"+c.id+"','"+c.id+"','"+c.imgS+'\')">comment</span> . <span><a href="page.php?view=community&com='+c.com_id+'">in '+c.com+"</a></span></div><script>setTimeout(timeUpdate,20000,'"+c.rawTime+"','tp"+c.id+"')</script><span id=\"comments"+c.id+'"></span><span id="box'+c.id+'"></span></div>';
                                        $(".posts").html(d+$(".posts").html());
                                        $("#gossout_loading").html("Shared with community")
                                    }else{}
                                }
                                if(a.facebook){
                                    if(c.fbstatus!="success"){
                                        window.location = "http://gossout.com/page.php?view=facebook&post="+a.facebook;
                                        $("#gossout_loading").html($("#gossout_loading").html()+",Please wait while we authenticate your account.<img src='images/load.gif' />")
                                        
                                    }else if(c.fbstatus=="success"){
                                        if(c.fb_post_id>0||c.fb_post_id!=""){
                                            $("#gossout_loading").html($("#gossout_loading").html()+", "+c.fbmsg)
                                        }else{
                                            $("#gossout_loading").html($("#gossout_loading").html()+", <span style='color:red;'>"+c.fbmsg+".</span> <a href='page.php?view=facebook'>Login here</a>")
                                        }
                                    }
                                }
                            }
                        },
                        complete:function(a,b){}
                    })
                }
            },
            Cancel:function(){
                $(this).dialog("close")
            }
        },
        open:function(){
            $("#dialogtext").focus()
        },
        close:function(){}
    })
}
function sendFriendRequest(a){
    var b=$("#status_"+a).html();
    var c=$("#sfr").val();
    var d={};
    
    if(b=="Send Friend Request"||c=="Send Friend Request"){
        $(".people_loading"+a).html("<img src='images/load.gif' />");
        d={
            action:"",
            frq:a
        }
    }else if(b=="Cancel Request"||c=="Cancel Request"){
        d={
            action:"",
            cfrq:a
        }
    }
    $.ajax({
        url:"exec.php",
        data:d,
        cache:false,
        dataType:"json",
        type:"post",
        success:function(d){
            if(d){
                if(d.status=="success"){
                    showFlashMessageDialoge(d.message,"messenger","info");
                    if(b=="Send Friend Request"||c=="Send Friend Request"){
                        $("#status_"+a).html("Cancel Request");
                        $("#sfr").val("Cancel Request");
                        $(".people_loading"+a).html("<img src='images/load.gif' />");
                        $("#p_"+a).hide("drop",{},1e3)
                    }else if(b=="Cancel Request"||c=="Cancel Request"){
                        $("#status_"+a).html("Send Friend Request");
                        $("#sfr").val("Send Friend Request")
                    }
                }else{
                    showFlashMessageDialoge(d.message,"messenger","error")
                }
            }
        },
        complete:function(b,c){
            $(".people_loading"+a).html("")
            alert(c);
        }
    })
}
function acceptOrDeclineFrq(a,b,c){
    $.ajax({
        url:"exec.php",
        data:{
            action:b,
            acceptfrq:a,
            key:c
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(b){
            if(b){
                if(b.status=="success"){
                    showFlashMessageDialoge(b.message,"messenger","info");
                    $("#requestoption"+a).html("<a href='page.php?view=profile&uid="+a+"'>View Profile</a>")
                }else{
                    $("#p_"+userid).hide("drop",{},1e3);
                    showFlashMessageDialoge(b.message,"messenger","error")
                }
            }
        }
    })
}
function tweakwink(a,b){
    $.ajax({
        url:"exec.php",
        data:{
            action:a,
            tweakwink:b
        },
        cache:false,
        dataType:"json",
        type:"post",
        success:function(a){
            if(a){
                if(a.status=="success"){
                    showFlashMessageDialoge(a.message,"messenger","info");
                    if(b=="T"){
                        $("#tweak").attr("disabled","disabled")
                    }else{
                        $("#wink").attr("disabled","disabled")
                    }
                }else{
                    showFlashMessageDialoge(a.message,"messenger","error")
                }
            }
        }
    })
}
function ajaxFileUpload(a,b){
    $.ajaxFileUpload({
        url:"do_ajaxfileupload_post.php",
        secureuri:false,
        fileElementId:"fileToUpload",
        dataType:"json",
        data:{
            posts:a
        },
        success:function(a,c){
            if(typeof a.status!="undefined"){
                if(a.status=="success"){
                    $("#status_community").css("display","none");
                    $(b).removeAttr("disabled");
                    $(b).val("");
                    $("#share_loading").html("");
                    $(b).removeClass("sending");
                    var d='<div class="post" id='+a.id+'><img class="profile_small"src="'+a.imgL+'"/><p class="name"><a href="page.php?view=profile&uid='+a.sender_id+'">'+a.name+'</a></p><p class="status">'+a.text+'</p><ul class="box"><li><img src="'+a.imgStatus+'" /></li></ul><p class="time" id="tp'+a.id+'">'+a.time+"</p><div class=\"post_activities\"> <span onclick=\"showGossoutModeldialog('dialog','"+a.id+"');\">Gossout</span> . <span onclick=\"showCommentBox('box"+a.id+"','"+a.id+"','"+a.imgS+'\')">comment</span> . <span><a href="page.php?view=community&com='+a.com_id+'">in '+a.com+"</a></span></div><script>setTimeout(timeUpdate,20000,'"+a.rawTime+"','tp"+a.id+"')</script><span id=\"comments"+a.id+'"></span><span id="box'+a.id+'"></span></div>';
                    $(".posts").html(d+$(".posts").html());
                    $("#status").animate({
                        height:30
                    },1e3)
                }else{
                    $(b).removeClass("sending");
                    $(b).removeAttr("disabled");
                    $("#share_loading").html("");
                    showFlashMessageDialoge(a.message,"messenger","error")
                }
            }
        },
        error:function(a,b,c){
            showFlashMessageDialoge(c,"messenger","error")
        }
    });
    $("#statusUpdate").reset()
}
function enlargePostPix(a,b){
    $("#dialog").html("<span id='loader' class='loading'><img src='images/load.gif'/></span>");
    var c=new Image;
    $(c).load(function(){
        $(this).hide();
        $("#loader").html("").append(this);
        $(this).fadeIn()
    }).error(function(){
        $("loader").html("Image cannot be loaded at this time")
    }).attr("src",a);
    $("#dialog").dialog({
        autoOpen:true,
        modal:true,
        show:"",
        title:b,
        
        resizable:false,
        draggable:true,
        buttons:{
            Close:function(){
                $(this).dialog("close")
            }
        },
        open:function(){
            $("#dialogtext").focus()
        },
        close:function(){}
    })
}
function showMoreFTLPost(loadingClass,postContainerId,option){
    //alert(postState);
    
    if((option=="FTL"?FTLPostState:option=="TRD"?TRPostState:CFPostState)<0){
        return
    }
    $(loadingClass).html("<img src='images/load.gif' />");
    $.ajax({
        url:"exec.php",
        data:{
            action:"morePost",
            posts:option=="FTL"?FTLPostState:option=="TRD"?TRPostState:CFPostState,
            tab:option
        },
        cache:false,
        type:"post",
        success:function(b){
            if(b=="false"){
                if(option=="TRD"){
                    TRPostState = -1;  
                }else if(option=="FTL"){
                    FTLPostState = -1;
                }else if(option=="CF"){
                    CFPostState = -1;
                }
            }else{
                if(option=="TRD"){
                    TRPostState += 15;
                }else if(option=="FTL"){
                    FTLPostState += 15;
                }else if(option=="CF"){
                    CFPostState += 15;
                }
                var c = $(postContainerId).html();
                $(postContainerId).html(c+b)
            }
        },
        complete:function(a,b){
            $(loadingClass).html("");
        }
    });
}
function lookup(a){
    if(a.length==0){
        $("#suggestions").fadeOut()
    }else{
        $("#s_loading").html("<img src='images/load.gif' />");
        $.ajax({
            url:"exec.php",
            data:{
                action:"",
                search:a
            },
            cache:false,
            dataType:"json",
            type:"post",
            success:function(a){
                if(a){
                    var b="";
                    $("#suggestions").fadeIn();
                    if(a.people.status=="success"){
                        b+='<div class="heading">PEOPLE</div>';
                        $.each(a.people,function(a,c){
                            if(c.id)b+='<div class="post"><img src="'+c.img+'" alt=""/><p class="name"><a href="page.php?view=profile&uid='+c.id+'">'+c.fullname+'</a></p><p class="status">'+c.location+"</p></div>"
                        })
                    }
                    if(a.community.status=="success"){
                        var c=a.community.img;
                        b+='<div class="heading">COMMUNITIES</div>';
                        $.each(a.community,function(a,d){
                            if(d.id)b+='<div class="post"><img src="'+c+'" alt=""/><p class="name"><a href="page.php?view=community&com='+d.id+'">'+d.fullname+'</a></p><p class="status">Subscribers '+d.subscriber+"</p></div>"
                        })
                    }
                    b+='<div class="heading"> </div>';
                    $("#suggestions").html(b)
                }
            },
            complete:function(a,b){
                $("#s_loading").html("")
            }
        })
    }
}
function deleteFile(fileId){
    
    $("#dialog").html("Are you sure you want to remove image completely?");
    $("#dialog").dialog({
        autoOpen:true,
        modal:true,
        show:"",
        title:"Confirm Deletion",
        resizable:false,
        draggable:false,
        buttons:{
            Yes:function(){
                sendToServer({
                    action:"deletePhoto",
                    fileId:fileId
                });
            },
            "No, Thanks":function(){
                $(this).dialog("close")
            }
        },
        open:function(){
            $("#dialogtext").focus()
        },
        close:function(){}
    });
    
}
function sendToServer(data){
    $.ajax({
        url:"exec.php",
        data:data,
        cache:false,
        dataType:"json",
        type:"post",
        success:function(output){
            if(data.action=="deletePhoto"){
                if(output){
                    if(output.status=="success"){
                        $("#dialog").dialog("close");
                        showFlashMessageDialoge("Operation successfull!","messenger","success");
                        setTimeout("window.location.reload(true)",1000);
                        
                    }else{
                        showFlashMessageDialoge("Your last command was not successfull","messenger","error")       
                    }
                }
            }else if(data.action=="albumCover"){
                if(output){
                    if(output.status=="success"){
                        showFlashMessageDialoge("Operation successfull!","messenger","success");
                    }else{
                        showFlashMessageDialoge("Your last command was not successfull "+output.status,"messenger","error")       
                    }
                }
            }
        },
        complete:function(dataX,status){
            if(status!="success")
                showFlashMessageDialoge(status,"messenger","error");
        //                
        }
    })
}
function makrAlbumCover(fileID,album){
    sendToServer({
        action:"albumCover",
        ola:fileID,
        album:album
    });
}
function useTheme(id){
    alert(id)
}
var FTLPostState = 15;
var TRPostState = 15;
var CFPostState = 15;