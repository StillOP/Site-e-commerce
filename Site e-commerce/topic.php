<?php

session_start();
require_once("usermanager.inc.php");
require_once("form.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");

if(!isset($_GET['topic_page'])) { echo "<script>document.location.href=\"forum.php?page=1\";</script>"; }
if(!isset($_GET['id'])) { echo "<script>document.location.href=\"forum.php?page=1\";</script>"; }
if(!isset($_GET['title'])) { echo "<script>document.location.href=\"forum.php?page=1\";</script>"; }

$header = new header();
$form = new form();
$footer = new footer();

$database = new database();
$id = $database->format($_GET['id']);

if(isset($_GET['id']) && isset($_GET['title']))
{
    $validation_request = "SELECT topic_id, title FROM topic WHERE topic_id={$id}";
    $validation_result = $database->select($validation_request);
    
    if($validation_result[0]==0) { echo "<script>document.location.href=\"forum.php?page=1\";</script>"; }
    if($validation_result[1]->title != $_GET['title']) { echo "<script>document.location.href=\"forum.php?page=1\";</script>"; }
}

$request = "SELECT * from response WHERE topic_id ={$id} ORDER BY date ASC";
$result = $database->select($request);

$max_page = $result[0] / 20;
$max_page = ceil($max_page);

if(isset($_GET['topic_page']))
{
    if($_GET['topic_page'] < 1  || $_GET['topic_page'] > $max_page || !is_numeric($_GET['topic_page'])) { echo "<script>document.location.href=\"forum.php?page=1\";</script>"; }
}

$avatars = [];
for($i = 1; $i <= $result[0]; $i++)
{
    $avatar_request = "SELECT avatar FROM user WHERE pseudo='{$result[$i]->owner}'";
    $avatar_result = $database->select($avatar_request);
    $avatars[] = $avatar_result[1]->avatar;
}

$statuts = [];
for($i = 1; $i <= $result[0]; $i++)
{
    $statut_result = $database->select("SELECT statut FROM user WHERE pseudo='{$result[$i]->owner}'");
    $statuts[] = $statut_result[1]->statut;
}

$can_remove = $database->select("SELECT owner FROM topic WHERE topic_id={$id}");
$remove_form;
if($can_remove[1]->owner == $_SESSION['pseudo'])
{
    $remove_form = "<form method=\"post\" action=\"remove.php\"><input type=\"text\" name=\"type\" value=\"topic\" hidden /><input type=\"text\" name=\"id\" value=\"{$id}\" hidden /><button type=\"submit\" class=\"btn\" style=\"background-color:white;\"><i class=\"material-icons\" style=\"color:red;font-size:14px;\">delete</i></button></form>";
}
else
{
    $remove_form = "";
}

$header->display($_GET['title'], $_SESSION);
?>

<div class="col-xs-12">
    <div class="col-md-9" style="padding:20px;">
        <h2>Topic : <span style="color:#dc3545;"><?php echo $_GET['title']; ?></span></h2>&nbsp;&nbsp;<?php echo $remove_form; ?>
        <br/>
        <a href="#reply"><button class="btn btn-info">REPLY</button></a>
        <hr style="border-width:2px;">
        <br/>
        <div class="row">
            <div class="col-md-4" id="btn-div1" style="text-align:left;"><button class="btn" id="prev" style="background-color:white;" onclick="previous()"><h4><strong><</strong></h4></button></div>
            <div class="col-md-4" id="btn-div2" style="text-align:center;">
                <nav aria-label="navigation"><ul class="pagination justify-content-center" id="pagination"></ul></nav>
            </div>
            <div class="col-md-4" id="btn-div3" style="text-align:right;"><button class="btn" id="next" style="background-color:white;" onclick="next()"><h4><strong>></strong></h4></button></div>
        </div>
        <hr style="border-width:2px;">
        <div id="post">
        </div>
        <div class="row">
            <div class="col-md-4" id="btn-div1-bottom" style="text-align:left;"><button class="btn" id="prev-bottom" style="background-color:white;" onclick="previous()"><h4><strong><</strong></h4></button></div>
            <div class="col-md-4" id="btn-div2-bottom" style="text-align:center;">
                <nav aria-label="navigation"><ul class="pagination justify-content-center" id="pagination-bottom"></ul></nav>
            </div>
            <div class="col-md-4" id="btn-div3-bottom" style="text-align:right;"><button class="btn" id="next-bottom" style="background-color:white;" onclick="next()"><h4><strong>></strong></h4></button></div>
        </div>
        <br/>
        <hr style="border-width:2px;">
        <br/>
        <div id="reply">
            <h2>Reply</h2>
            <br/>
            <form action="post-validation.php?id=<?php echo $_GET['id']; ?>&title=<?php echo $_GET['title']; ?>&topic_page=<?php echo $_GET['topic_page']; ?>" method="post" enctype="application/x-www-form-urlencoded" id="post-form">
                <div class="form-group">
                    <div>
                        <button type="button" class="btn" style="bacground-color:white;border:none;" onclick="input_style('bold', '#message')"><b>Bold</b></button>
                        <button type="button" class="btn" style="bacground-color:white;border:none;" onclick="input_style('italic', '#message')"><i>Italic</i></button>
                        <button type="button" class="btn" style="bacground-color:white;border:none;" onclick="input_style('quote', '#message')">Quote</button>
                        <button type="button" class="btn" style="bacground-color:white;border:none;" onclick="input_style('link', '#message')">Link</button>
                    </div>
                    <textarea class="form-control" id="message" name="message" rows="10"></textarea>
                </div>
                <button type="button" class="btn btn-info" id="submit-btn">POST</button>
            </form>
        </div>
    </div>
    <div class="col-md-4" id="pub">
    </div>
</div>

<script>
    function formatting(text)
    {
        var regrex = [/\[bold\](.+)\[\/bold\]/g, /\[italic\](.+)\[\/italic\]/g, /\[quote\](.+)\[\/quote\]/g, /\[link\](.+)\[\/link\]/g];
        var by = ["<b>$1</b>", "<i>$1</i>", "<p style=\"background-color:#f8f9fa;color:gray;padding:10px;\">$1</p>", "<a href=\"$1\" target=\"_black\">$1</a>"];
        
        for(var i = 0; i < regrex.length; i++)
        {
            text = text.replace(regrex[i], by[i]);
        }
        return text;
    }

    document.querySelector('#submit-btn').addEventListener('click', function() {
        
        var message = document.querySelector('#message').value;
        var connected = '<?php if(isset($_SESSION['connected'])) { echo $_SESSION['connected']; } else { echo "false"; } ?>';
        
        if(message != "" && connected == "true" && message.length <= 65000)
        {
            document.querySelector('#post-form').submit();
        }
        else
        {
            if(connected=="false")
            {
                document.location.href="connexion.php";
            }
        }
        
    });
    
    var message_num = <?php echo $result[0]; ?>;
    var result = '<?php echo json_encode($result); ?>';
    result = result.replace(/(\r\n|\n|\r|\x0B)/gm,"<br/>");
    var obj = JSON.parse(result);
    var message_per_page=20;
    var page = <?php echo $_GET['topic_page']; ?>;
    var num_page = Math.ceil(message_num / message_per_page);
    
    var navigation = document.querySelector('#pagination');
    var navigation_bottom = document.querySelector('#pagination-bottom');
    for(var i=1; i<= num_page; i++)
    {
        if(i==page) {navigation.innerHTML+= '<li class="page-item active"><a class="page-link" href="#" style="color:#212529;border:none;background-color:#f8f9fa;"><strong>'+i+'</strong></a></li>'; }
        else { navigation.innerHTML += '<li class="page-item"><a class="page-link" href="topic.php?id=<?php echo $_GET['id']; ?>&title=<?php echo $_GET['title']; ?>&topic_page='+i+'" style="color:#212529;border:none;"><strong>'+i+'</strong></a></li>'; }
        
        if(i==page) {navigation_bottom.innerHTML+= '<li class="page-item active"><a class="page-link" href="#" style="color:#212529;border:none;background-color:#f8f9fa;"><strong>'+i+'</strong></a></li>'; }
        else { navigation_bottom.innerHTML += '<li class="page-item"><a class="page-link" href="topic.php?id=<?php echo $_GET['id']; ?>&title=<?php echo $_GET['title']; ?>&topic_page='+i+'" style="color:#212529;border:none;"><strong>'+i+'</strong></a></li>'; }
    }
    
    var post_div = document.querySelector('#post');
    var last_post_id = page * message_per_page;
    var first_post_id = last_post_id - 19;
    
    var avatars = <?php echo json_encode($avatars); ?>;
    var statuts = <?php echo json_encode($statuts); ?>;
    
    for(var i =first_post_id; i <= last_post_id; i++)
    {
        var connected = '<?php if(isset($_SESSION['connected'])) { echo $_SESSION['pseudo']; } else { echo ""; } ?>';
        var remove_form = '';
        if(connected == obj[i].owner)
        {
            remove_form = '<form action="remove.php" method="post">'+
                                '<input type="text" name="type" value="response" hidden/>'+
                                '<input type="text" name="id" value="'+obj[i].response_id+'" hidden/>'+
                                '<button type="submit" class="btn" style="background-color:white;"><i class="material-icons" style="color:red;font-size:14px;">delete</i></button>'+
                            '</form>';
        }
        var style;
        if(statuts[i -1] == "admin") { style="color:#dc3545"; }
        else { style="color:#007bff;" }
        var message = formatting(obj[i].message);
        var post = document.createElement('div');
        post_div.appendChild(post);
        post.style="padding:15px;margin:40px;";
        post.innerHTML +='<div>'+
                            '<div style="display:inline-flex;width:100%;">'+
                                '<div class="col-md-2">'+
                                    '<div style="height:60px;width:60px;background-image:url(avatars/'+avatars[i - 1]+');background-size:cover;background-position:center;border-radius:50%;">'+
                                    '</div>'+
                                '</div>'+
                                '<span class="col-md-6" style="margin-top:10px;">'+
                                    '<a href="profil.php?pseudo='+obj[i].owner+'" style="'+style+'">'+
                                        obj[i].owner+
                                    '</a>'+
                                '</span>'+
                                '<span class="col-md-3" style="color:#868e96;margin-top:10px;"><small>'+
                                    obj[i].date+remove_form+
                                '</small></span>'+
                            '</div>'+
                        '</div>'+
                        '<hr style="border-width:1px;width:90%;background-color:#d3d3d3;">'+
                        '<div>'+
                            message+
                        '</div>';
        
        if(i == message_num) { break; }
    }
    
    var id = '<?php echo $_GET['id']; ?>';
    var title = '<?php echo $_GET['title']; ?>';
    
    function previous()
    {
        if(page > 1)
        {
            page--;
            document.location.href="topic.php?id="+id+"&title="+title+"&topic_page="+page;
        }
    }
    
    function next()
    {
        if(page < num_page)
        {
            page++;
            document.location.href="topic.php?id="+id+"&title="+title+"&topic_page="+page;
        }
    }
    
    function input_style(balise, textarea)
    {
        var insert = '['+balise+']'+'[/'+balise+']';
        document.querySelector(textarea).value += insert;
    }
    
</script>

<?php
    
$footer->display();

?>