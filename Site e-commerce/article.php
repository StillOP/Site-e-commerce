<?php

session_start();
require_once("usermanager.inc.php");
require_once("form.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");

if(!isset($_GET['type'])) { echo "<script>document.location.href=\"index.php\";</script>"; }
if(!isset($_GET['article_id'])) { echo "<script>document.location.href=\"index.php\";</script>"; }
if(isset($_GET['type']))
{
    if($_GET['type'] != "album" && $_GET['type'] != "book" && $_GET['type'] != "dailypost" && $_GET['type'] != "goodies")
    {
        echo "<script>document.location.href=\"index.php\";</script>";   
    }
}

$database = new database();

$result = $database->select("SELECT * FROM {$_GET['type']}");
$count = 0;
$property = $_GET['type'].'_id';

for($i = 1; $i <= $result[0]; $i++)
{
    $obj_property = $result[$i]->$property;
    if($obj_property == $_GET['article_id']) { $count++;}
}
if($count == 0 || $count > 1)
{
    echo "<script>document.location.href=\"index.php\";</script>"; 
}

$comments = $database->select("SELECT * FROM comment WHERE article_type='{$_GET['type']}' AND article_id='{$_GET['article_id']}' ORDER BY date DESC");

$avatars = [];
for($i = 1; $i <= $comments[0]; $i++)
{
    $avatar_result = $database->select("SELECT avatar FROM user WHERE pseudo='{$comments[$i]->owner}'");
    $avatars[] = $avatar_result[1]->avatar;
}

$remove_form = "";
if(isset($_SESSION['pseudo']))
{
    $can_remove = $database->select("SELECT owner FROM {$_GET['type']} WHERE {$property}='{$_GET['article_id']}'");
    if($can_remove[1]->owner == $_SESSION['pseudo'])
    {
        $remove_form = "<form method=\"post\" action=\"remove.php\"><input type=\"text\" name=\"type\" value=\"{$_GET['type']}\" hidden /><input type=\"text\" name=\"id\" value=\"{$_GET['article_id']}\" hidden /><button type=\"submit\" class=\"btn\" style=\"background-color:white;\"><i class=\"material-icons\" style=\"color:red;font-size:14px;\">delete</i></button></form>";
    }
}

$header = new header();
$form = new form();
$footer = new footer();
$header->display("Article", $_SESSION);
?>

<div class="col-xs-12" id="article-div">
</div>
<?php echo $remove_form; ?>
<div class="col-xs-12" id="post-comment" style="margin-top:40px;">
    <form method="post" action="comment.validation.php" id="comment-form">
        <div class="row" style="margin:0;">
            <div class="col-md-9">
                <div class="form-group">
                    <textarea class="form-control" name="comment-message" id="comment-message" rows="1" placeholder="Comment.."></textarea>
                </div>
            </div>
            <div class="col-md-2" style="padding-top:15px;">
                <button type="button" class="btn btn-info" id="comment-submit">Add</button>
            </div>
        </div>
        <input type="text" name="type" value="<?php echo $_GET['type']; ?>" hidden />
        <input type="text" name="article_id" value="<?php echo $_GET['article_id']; ?>" hidden />
    </form>
</div>
<div class="col-xs-9" id="comments" style="margin-top:20px;"></div>


<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="payment-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payment-label" style="color:#51acc7;">Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="payment-body">
          <h6 id="price-info">The initial price was set to :</h6>
          <h6> Enter the amount you <strong>want to add.</strong></h6>
          <form id="support-form" action="payment.php" method="post">
              <div class="form-group">
                  <input type="text" class="form-control" pattern="[0-9]+([\.][0-9]{0,2})?" name ="support-price" id="support-price" />
              </div>
              <input type="text" id="owner" name="owner" hidden />
              <input type="text" id="name" name="name" hidden />
              <input type="text" id="id" name="id" hidden />
              <input type="text" id="type" name="type" hidden />
              <input type="text" id="price" name="price" hidden />
              <div id="additional-input"></div>
              <button type="button" class="btn btn-outline-primary" onclick="support()">Continue</button>
          </form>
      </div>
    </div>
  </div>
</div>

<script>
    function support()
    {
        var price = document.querySelector('#support-price').value;
        
        if(price != "" && /^[0-9]*\.?[0-9]{0,2}?$/.test(price))
        {
            var type = '<?php if(isset($_GET['type'])) { echo $_GET['type']; } else {echo "none"; } ?>';
            if(type == "goodies")
            {
                var address = document.querySelector('#address').value;
                if(address == "") { return; }
            }
            document.querySelector('#support-form').submit();
        }
    }
    
    document.querySelector('#comment-submit').addEventListener('click', function() {
        
        var message = document.querySelector('#comment-message').value;
        if(message != "" && message.length <= 65000)
        {
            document.querySelector('#comment-form').submit();
        }
        
    });
    
    var comments = '<?php echo json_encode($comments); ?>';
    comments = comments.replace(/(\r\n|\n|\r|\x0B)/gm,"<br/>");
    comments = JSON.parse(comments);
    var avatars = <?php echo json_encode($avatars); ?>;
    var comments_div = document.querySelector('#comments');
    if(comments[0] > 0)
    {
        for(var i = 1; i <= comments[0]; i++)
        {
            comments_div.innerHTML += '<div class="row" style="margin-left:0px;margin-right:0px;margin-top:10px;">'+
                                        '<div class="col-md-1" id="profil">'+
                                            '<div style="height:60px;width:60px;background-image:url(avatars/'+avatars[i-1]+');background-size:cover;background-position:center;border-radius:50%;">'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-6">'+
                                            '<p><strong><a href="profil.php?pseudo='+comments[i].owner+'">'+comments[i].owner+'</a></strong></p>'+
                                            '<p>'+comments[i].message+'</p>'+
                                        '</div>'+
                                        '<div class="col-md-2"><small style="color:#868e96;">'+comments[i].date+'</small></div>'+
                                    '</div>';
        }
    }
    
</script>

<?php 

if($_GET['type'] == "album") { require_once("article.album.inc.php"); } 
else if($_GET['type'] == "book") { require_once("article.book.inc.php"); }
else if($_GET['type'] == "dailypost") { require_once("article.dailypost.inc.php"); }
else if($_GET['type'] == "goodies") { require_once("article.goodies.inc.php"); }

?>


<?php
    
$footer->display();
?>

<!--'<div class="col-md-5" style="width:800px;height:350px;">'+
                                        '<img src="covers/'+album[1].cover+'" style="width:100%;height:100%;border-radius:5px;"/>'+
                                    '</div>'+