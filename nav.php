<div class="nav nav_gradient">
    <div class="home"><span><a href="page.php?view=home" >Home</a></span></div>
    <div class="gossforums"><span><a href="page.php?view=community" >Communities</a></span></div>
    <div class="logout"><span><a href="page.php?view=home&signout=" >Logout</a></span></div>
    <form method="get" id="searchform" >
        <span id="s_loading"></span><input type="text" style="height: 100%" class="field" name="s" id="s" placeholder="People/Community" onkeyup="lookup(this.value);">
        <input type="submit" class="submit" name="submit" id="searchsubmit" value="Search">
        <div id="suggestions"></div>
    </form>
    
</div>