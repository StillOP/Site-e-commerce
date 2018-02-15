<?php

session_start();
require_once("usermanager.inc.php");
require_once("form.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");
require_once("mail.param.inc.php");

$header = new header();
$footer = new footer();

$database = new database();
$user = $database->select("SELECT * FROM user WHERE pseudo=\"{$_GET['pseudo']}\"");
if($user[0] == 0)
{
    echo "<script>document.location=\"index.php\";</script>";
}


$header->display("Kreativ.com share your passion! User:{$_GET['pseudo']}", $_SESSION);

$pseudo = $user[1]->pseudo;
$mail = $user[1]->mail;
$avatar = $user[1]->avatar;
$date = $user[1]->date;
$description = $user[1]->description;
if($description == null) { $description = ""; }

$album = $database->select("SELECT * FROM album WHERE owner=\"{$_GET['pseudo']}\"");
$book = $database->select("SELECT * FROM book WHERE owner=\"{$_GET['pseudo']}\"");
$dailypost = $database->select("SELECT * FROM dailypost WHERE owner=\"{$_GET['pseudo']}\"");
$goodies = $database->select("SELECT * FROM goodies WHERE owner=\"{$_GET['pseudo']}\"");

?>

<div class="col-xs-12" style="padding-top:50px;">
    <div class="row" style="margin:0px;">
        <div class="col-md-5">
             <div style="height:300px;width:300px;background-image:url(avatars/<?php echo $avatar; ?>);background-size:cover;background-position:center;border-radius:50%;">
            </div>
        </div>
        <div class="col-md-5" style="margin-top:80px;">
            <h5>Pseudo: <span style="color:#dc3545"><?php echo $pseudo; ?></span></h5>
            <h5>Registered on: <span style="color:#dc3545"><?php echo $date; ?></span></h5><br/>
            <button type="btn" data-toggle="modal" data-target="#new-message" style="background-color:white;border:none;color:gray;padding:0;"><i class="material-icons" style="font-size:30px;">email</i></button>
        </div>
    </div>
    <br/><br/>
    <div class="col-md-12" style="margin-top:10px;">
        <div id="items">
            <div>
                <h2>Description</h2>
                <br/>
                <h5 id="description">
                </h5>
            </div>
            <hr style="border-width:1px;background-color:#d3d3d3;">
            <br/>
            <h2 style="color:#dc3545">Kreation</h2>
            <br/>
        </div>
    </div>
</div>

<div class="modal fade" id="new-message" tabindex="-1" role="dialog" aria-labelledby="new-messagelabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="new-postlabel">Send message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="sendmessage.php" id="new-message-form">
                    <div class="form-group">
                        <label for="new-message-title">Title</label>
                        <input type="text" class="form-control" name="new-message-title" id="new-message-title" />
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="new-message-content" id="new-message-content" rows="20"></textarea>
                    </div>
                    <input type="text" name="receiver" id="receiver" value="<?php echo $_GET['pseudo']; ?>" hidden>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="new-message-submit" onclick="send_message()">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
    
    var album = '<?php echo json_encode($album); ?>';
    album = JSON.parse(album);
    
    var book = '<?php echo json_encode($book); ?>';
    book = JSON.parse(book);
    
    var dailypost = '<?php echo json_encode($dailypost); ?>';
    dailypost = JSON.parse(dailypost);
    
    var goodies = '<?php echo json_encode($goodies); ?>';
    goodies = JSON.parse(goodies);
    
    
    var items = document.querySelector('#items');
    
    var description = '<?php echo json_encode($description); ?>';
    description = description.replace(/(\r\n|\n|\r|\x0B)/gm,"<br/>");
    description = description.replace(/"/gm,"");
    document.querySelector('#description').innerHTML = description;
    
    
    if(album[0] > 0)
    {
        var items = document.querySelector('#items');
        items.innerHTML += '<hr style="border-width:1px;background-color:#d3d3d3;width:70%;">';
        items.innerHTML += '<h3>Album</h3>';
        
        var album_div = document.createElement('div');
        album_div.className="row";
        items.appendChild(album_div);
        
        for(var i = 1; i <= album[0]; i++)
        {
            album_div.innerHTML += '<div class="col-md-3" style="margin:10px;">'+
                                        '<div class="card" style="width:18rem;height:20rem;">'+
                                            '<a href="article.php?type=album&article_id='+album[i].album_id+'">'+
                                                '<div style="width:18rem;height:15rem;background-image:url(covers/'+album[i].cover+');background-size:cover;background-position:center;">'+
                                                '</div>'+
                                            '</a>'+
                                            '<div class="card-body">'+
                                                '<h6 class="card-title">'+album[i].name+'</h6>'+
                                                '<h6 class="card-subtitle text-muted">'+album[i].owner+'</h6>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div><br/>';
        } 
    }
    
    if(book[0] > 0)
    {
        var items = document.querySelector('#items');
        items.innerHTML += '<hr style="border-width:1px;background-color:#d3d3d3;width:70%;">';
        items.innerHTML += '<h3>Book</h3>';
        
        var book_div = document.createElement('div');
        book_div.className="row";
        items.appendChild(book_div);
        
        for(var i = 1; i <= book[0]; i++)
        {
            book_div.innerHTML += '<div class="col-md-3" style="margin:10px;">'+
                                        '<div class="card" style="width:18rem;height:20rem;">'+
                                        '<a href="article.php?type=book&article_id='+book[i].book_id+'">'+
                                            '<div style="width:18rem;height:15rem;background-image:url(covers/'+book[i].cover+');background-size:cover;background-position:center;">'+
                                            '</div>'+
                                        '</a>'+
                                            '<div class="card-body">'+
                                                '<h6 class="card-title">'+book[i].title+'</h6>'+
                                                '<h6 class="card-subtitle text-muted">'+book[i].owner+'</h6>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
        } 
    }
    
    if(dailypost[0] > 0)
    {
        var items = document.querySelector('#items');
        items.innerHTML += '<hr style="border-width:1px;background-color:#d3d3d3;width:70%;">';
        items.innerHTML += '<h3>Daily post</h3><br/>';
        
        var dailypost_div = document.createElement('div');
        items.appendChild(dailypost_div);
        
        for(var i = 1; i <= dailypost[0]; i++)
        {
            dailypost_div.innerHTML += '<div class="form-row">'+
                                                '<div class="col-md-3">'+
                                                    '<a href="article.php?type=dailypost&article_id='+dailypost[i].dailypost_id+'"><h5>'+dailypost[i].title+'</h5></a>'+
                                                '</div>'+
                                        '</div>'+
                                        '<br/>';    
        }
    }
    
    if(goodies[0] > 0)
    {
        var items = document.querySelector('#items');
        items.innerHTML += '<hr style="border-width:1px;background-color:#d3d3d3;width:70%;">';
        items.innerHTML += '<h3>Goodies</h3>';
        
        var goodies_div = document.createElement('div');
        goodies_div.className="row";
        items.appendChild(goodies_div);
        
        for(var i = 1; i <= goodies[0]; i++)
        {
            var image = goodies[i].images.split(",")[0];
            goodies_div.innerHTML += '<div class="col-md-3" style="margin:10px;">'+
                                            '<div class="card" style="width:18rem;height:20rem;">'+
                                                '<a href="article.php?type=goodies&article_id='+goodies[i].goodies_id+'">'+
                                                '<div style="width:18rem;height:15rem;background-image:url(covers/'+image+');background-size:cover;background-position:center;">'+
                                            '</div>'+
                                            '</a>'+
                                            '<div class="card-body">'+
                                                '<h6 class="card-title">'+goodies[i].title+'</h6>'+
                                                '<h6 class="card-subtitle text-muted">'+goodies[i].owner+'</h6>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
        } 
    }
    
    function send_message()
    {
        var connected = '<?php echo $_SESSION['connected']; ?>';
        
        if(connected == 'true')
        {
            var title = document.querySelector('#new-message-title').value;
            var content = document.querySelector('#new-message-content').value;
                
            if(title != "" && content != "" && title.length <= 255 && content.length <= 65000)
            {
                document.querySelector('#new-message-form').submit();
            }
        }
        else
        {
            document.location="connexion.php";
        }
    }
    
</script>

<?php

$footer->display();

?>