<?php

session_start();
require_once("usermanager.inc.php");
require_once("form.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");
require_once("mail.param.inc.php");

if(!isset($_SESSION['connected']) || $_SESSION['connected'] == "false") { echo"<script> document.location.href=\"index.php\"</script>"; }

$header = new header();
$footer = new footer();

$database = new database();

$header->display("My account", $_SESSION);

$user = $database->select("SELECT * FROM user WHERE pseudo=\"{$_SESSION['pseudo']}\"");

$pseudo = $user[1]->pseudo;
$mail = $user[1]->mail;
$avatar = $user[1]->avatar;
$paypal = $user[1]->paypal;
$date = $user[1]->date;
$description = $user[1]->description;
$description_button_content;

if($description==null) { $description_button_content = "Add"; $derscription = ""; }
else { $description_button_content = "Edit"; }

if($paypal==null) { $paypal = "<button class=\"btn btn-info btn-sm\" id=\"paypal-btn\" data-toggle=\"modal\" data-target=\"#new-paypal\">Add</button>"; }

$album = $database->select("SELECT * FROM album WHERE owner=\"{$_SESSION['pseudo']}\"");
$book = $database->select("SELECT * FROM book WHERE owner=\"{$_SESSION['pseudo']}\"");
$dailypost = $database->select("SELECT * FROM dailypost WHERE owner=\"{$_SESSION['pseudo']}\"");
$goodies = $database->select("SELECT * FROM goodies WHERE owner=\"{$_SESSION['pseudo']}\"");
$received = $database->select("SELECT message_id FROM message WHERE receiver='{$_SESSION['pseudo']}' AND receiver_statut='unread'");

$mailbox_info;
if($received[0] > 0) { $mailbox_info = '<i class="material-icons">fiber_new</i>'; }
else { $mailbox_info = ""; }

?>

<div class="col-xs-12" style="padding-top:50px;">
    <div class="row" style="margin:0px;">
        <div style="position:absolute;background-image:url(avatars/<?php echo $avatar; ?>);filter:blur(35px);z-index:-2;height:270px;%;width:100%;"></div>
        <div class="col-md-5">
             <div style="height:250px;width:250px;background-image:url(avatars/<?php echo $avatar; ?>);background-size:cover;background-position:center;border-radius:50%;">
            </div>
        </div>
        <div class="col-md-5" style="margin-top:40px;">
            <h5 style="color:white;background-color:black;padding:5px;">Pseudo: <span style="color:#dc3545"><?php echo $pseudo; ?></span></h5>
            <h5 style="color:white;background-color:black;padding:5px;">Mail: <span style="color:#dc3545"><?php echo $mail; ?></span></h5>
            <h5 style="color:white;background-color:black;padding:5px;">Paypal account: <span style="color:#dc3545"><?php echo $paypal; ?></span></h5>
            <h5 style="color:white;background-color:black;padding:5px;">Registered on: <span style="color:#dc3545"><?php echo $date; ?></span></h5><br/>
            <a href="mailbox.php"><button type="button" class="btn" style="background-color:black;"><i class="material-icons" style="color:green;">email</i></button></a><span style="color:green;"><?php echo $mailbox_info; ?></span>
        </div>
    </div>
    <br/><br/>
    <div class="col-md-12" style="margin-top:10px;">
        <div id="items">
            <div>
                <h3>Description <button class="btn btn-sm" id="description-btn" data-toggle="modal" data-target="#new-description" style="background-color:white;"><i class="material-icons">mode_edit</i></button></h3>
                <br/>
                <h6 id="description">
                </h6>
            </div>
            <hr style="border-width:1px;background-color:#d3d3d3;">
            <br/>
            <h3 style="color:#dc3545">Your Kreation</h3>
            <br/>
        </div>
    </div>
</div>
<br/><br/>
<form method="post" action="remove.php">
    <input type="text" name="type" value="user" hidden />
    <input type="text" name="id" value="<?php echo $_SESSION['pseudo']; ?>" hidden />
    <button type="submit" class="btn btn-danger btn-sm" style="font-size:8px;margin-left:10px;">
        Delete account
    </button>
</form>

<div class="modal fade" id="new-description" tabindex="-1" role="dialog" aria-labelledby="new-descriptionlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="new-descriptionlabel">Description</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form method="post" action="account.info.validation.php" id="new-description-form">
                    <div class="form-group">
                        <textarea class="form-control" name="new-description-content" id="new-description-content" rows="10"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="new-description-submit" onclick="new_description()">Continue</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="new-paypal" tabindex="-1" role="dialog" aria-labelledby="new-paypallabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="new-paypallabel">Paypal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <h5 style="color:#dc3545">Payments not recovered due to an incorrect paypal email address will not be refunded and will be punished with a ban.</h5>
                <form method="post" action="account.info.validation.php" id="new-paypal-form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="new-paypal-content" id="new-paypal-content" pattern="(^[a-z0-9._-]+)@([a-z0-9._-])+(\.)([a-z]{2,4})" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="new-paypal-submit" onclick="new_paypal()">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="new-post" tabindex="-1" role="dialog" aria-labelledby="new-postlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="new-postlabel">New post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="dailypost-validation.php" id="new-post-form">
                    <div class="form-group">
                        <label for="new-post-title">Title</label>
                        <input type="text" class="form-control" name="new-post-title" id="new-post-title" />
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="button" class="btn" style="bacground-color:white;border:none;" onclick="input_style('bold', '#new-post-content')"><b>Bold</b></button>
                            <button type="button" class="btn" style="bacground-color:white;border:none;" onclick="input_style('italic', '#new-post-content')"><i>Italic</i></button>
                        </div>
                        <textarea class="form-control" name="new-post-content" id="new-post-content" rows="20"></textarea>
                    </div>
                    <input type="text" name="id" id="dailypostid" value="" hidden>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="new-post-submit" onclick="post()">Post</button>
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
    
    var description = '<?php if($description != "") { echo json_encode($description); } else { echo $description; } ?>';
    description = description.replace(/(\r\n|\n|\r|\x0B)/gm,"<br/>");
    description = description.replace(/"/gm,"");
    document.querySelector('#description').innerHTML = description;
    
    
    if(album[0] > 0)
    {
        var items = document.querySelector('#items');
        items.innerHTML += '<hr style="border-width:1px;background-color:#d3d3d3;width:70%;">';
        items.innerHTML += '<h4>Album</h4>';
        
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
        items.innerHTML += '<h4>Book</h4>';
        
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
        items.innerHTML += '<h4>Daily post</h4><br/>';
        
        var dailypost_div = document.createElement('div');
        items.appendChild(dailypost_div);
        
        for(var i = 1; i <= dailypost[0]; i++)
        {
            dailypost_div.innerHTML += '<div class="form-row" style="margin-left:20px;margin-bottom:5px;">'+
                                                '<div class="col-md-3">'+
                                                    '<a href="article.php?type=dailypost&article_id='+dailypost[i].dailypost_id+'"><h6 style="color:#51acc7;">'+dailypost[i].title+'</h6></a>'+
                                                '</div>'+
                                                '<div class="col-md-1">'+
                                                    '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#new-post" style="font-size:10px;" onclick="set_id('+dailypost[i].dailypost_id+')"> + New chapter</button>'+
                                                '</div>'+
                                            '</div>';
        }
    }
    
    if(goodies[0] > 0)
    {
        var items = document.querySelector('#items');
        items.innerHTML += '<hr style="border-width:1px;background-color:#d3d3d3;width:70%;">';
        items.innerHTML += '<h4>Goodies</h4>';
        
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
    
    function post()
    {
        if(dailypost[0] > 0)
        {
            var title = document.querySelector('#new-post-title').value;
            var content = document.querySelector('#new-post-content').value;
                
            if(title != "" && content != "" && title.length <= 255 && content.length <= 65000)
            {
                document.querySelector('#new-post-form').submit();
            }
        }
    }
    function set_id(id)
    {
        document.querySelector('#dailypostid').value = id;
    }
    
    function new_description()
    {
        var content = document.querySelector('#new-description-content').value;
        if(content.length <= 65000)
        {
            document.querySelector('#new-description-form').submit();
        }
    }
    
    function new_paypal()
    {
        var content = document.querySelector('#new-paypal-content').value;
        if(/(^[a-z0-9._-]+)@([a-z0-9._-])+(\.)([a-z]{2,4})/.test(content))
        {
            document.querySelector('#new-paypal-form').submit();
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

<!--<div class="card" style="width:18rem;height:20rem;">
                <img class="card-img-top" src="..." style="width:18rem;height:15rem;">
                <div class="card-body">
                    <h6 class="card-title">Album name</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Artist</h6>
                </div>
            </div>-->

<!--Voir htmlaccess, htmlpassword et les redirections-->