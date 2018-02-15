<script>
    var type = '<?php if(isset($_GET['type'])) { echo $_GET['type']; } else {echo "none"; } ?>';
    
    function formatting(text)
    {
        var regrex = [/\[bold\](.+)\[\/bold\]/g, /\[italic\](.+)\[\/italic\]/g];
        var by = ["<b>$1</b>", "<i>$1</i>"];
        
        for(var i = 0; i < regrex.length; i++)
        {
            text = text.replace(regrex[i], by[i]);
        }
        return text;
    }
    
    if(type == "dailypost")
    {
        var dailypost = '<?php $dailypost = $database->select("SELECT * FROM dailypost WHERE dailypost_id='{$_GET['article_id']}'"); echo json_encode($dailypost); ?>';
        dailypost = JSON.parse(dailypost);
        
        var post = '<?php $post = $database->select("SELECT * FROM post WHERE dailypost_id='{$_GET['article_id']}'"); echo json_encode($post); ?>';
        post = post.replace(/(\r\n|\n|\r|\x0B)/gm,"<br/>");
        post = JSON.parse(post);
        
        var container = document.querySelector('#article-div');
        var nav_div = document.createElement('div');
        nav_div.className = "row";
        nav_div.style="margin:0;padding-top:50px;";
        container.appendChild(nav_div)
        var col_div1 = document.createElement('div');
        col_div1.className = "col-md-3";
        nav_div.appendChild(col_div1);
        var nav_post = document.createElement('nav');
        nav_post.id = "post";
        nav_post.className = "navbar navbar-light bg-light flex-column";
        col_div1.appendChild(nav_post)
        
        nav_post.innerHTML += '<a class="navbar-brand" href="#"><h3 style="color:#dc3545">'+dailypost[1].title+'</h3></a>';
        
        var nav_pills = document.createElement('nav');
        nav_pills.className = "nav nav-pills flex-column";
        nav_pills.style = "height:450px;overflow:auto;"
        nav_post.appendChild(nav_pills);
        for (var i = 1; i <= post[0]; i++)
        {
            nav_pills.innerHTML += '<a class="nav-link" href="#item-'+i+'" style="color:#51acc7;">'+post[i].title+'</a>';
        }
        
        var col_div2 = document.createElement('div');
        col_div2.className = "col-md-7";
        nav_div.appendChild(col_div2);
        var post_content = document.createElement('div');
        post_content.style = "position:relative;height:500px;overflow:auto;"
        post_content.setAttribute("data-psy", "scroll");
        post_content.setAttribute("data-target", "#post");
        post_content.setAttribute("data-offset", "0");
        col_div2.appendChild(post_content);
        
         for (var i = 1; i <= post[0]; i++)
         {
            var content = formatting(post[i].content);
            post_content.innerHTML += '<h4 id="item-'+i+'">'+post[i].title+'</h4>';
            post_content.innerHTML += '<p>'+content+'</p>';
         }
        var subcriber = '<?php if(isset($_SESSION['pseudo'])) { $subscriber = $database->select("SELECT pseudo FROM subscriber WHERE pseudo='{$_SESSION['pseudo']}' AND dailypost_id='{$_GET['article_id']}'"); if($subscriber[0] == 0) { echo '<button type="submit" class="btn btn-danger btn-sm" name="subscribe">Subscribe</button>'; } else { echo '<button type="submit" class="btn btn-danger btn-sm" name="unsubscribe">Unsubscribe</button>'; }  } else { echo '<a href="connexion.php"><button type="button" class="btn btn-danger btn-sm">Subscribe</button></a>'; } ?>';
        
        var subscribers = <?php $snb = $database->select("SELECT pseudo FROM subscriber WHERE dailypost_id='{$_GET['article_id']}'"); echo $snb[0]; ?>;
        var snb_label = 'subscriber';
        if(subscribers > 1) { snb_label = 'subscribers'; }
        
        nav_div.innerHTML += '<div class="col-md-2">'+
                                '<h4 style="color:#0070c9"><a href="profil.php?pseudo='+dailypost[1].owner+'">'+dailypost[1].owner+'</a></h4><br/>'+
                                '<h6 class="text-muted">'+dailypost[1].tag+'</h6>'+
                                '<h6 class="text-muted">'+dailypost[1].date+'</h6><br/>'+
                                '<form method="post" action="subscribe.php">'+
                                    '<input type="text" name="pseudo" value="<?php if(isset($_SESSION['pseudo'])) { echo $_SESSION['pseudo']; } else { echo ""; } ?>" hidden />'+
                                    '<input type="text" name="dailypost_id" value="<?php echo $_GET['article_id']; ?>" hidden />'+
                                subcriber+
                                '</form>'+
                                '<small class="text-muted">'+subscribers+' '+snb_label+'</small>'+
                            '</div>';
                
    }
    
</script>
